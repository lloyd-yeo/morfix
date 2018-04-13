<?php

namespace App\Http\Controllers;

use App\AddProfileRequest;
use App\CreateInstagramProfileLog;
use App\Helper;
use App\InstagramHelper;
use App\InstagramProfile;
use App\InstagramProfileMedia;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InstagramAPI\InstagramException as InstagramException;
use Log;
use Response;
use \InstagramAPI\Response\ChallengeSelectVerifyMethodStepResponse;
use \InstagramAPI\Response\GenericResponse;

class InstagramProfileController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function refreshProfileStats(Request $request, $id) {
		$ig_profile = InstagramProfile::where('insta_user_id', $id)->first();

		$instagram = InstagramHelper::initInstagram();

		$guzzle_options                                 = [];
		$guzzle_options['curl']                         = [];
		$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://' . $ig_profile->proxy;
		$guzzle_options['curl'][CURLOPT_PROXYUSERPWD] = 'morfix:dXehM3e7bU';
		$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
		$instagram->setGuzzleOptions($guzzle_options);
		$instagram->login($ig_profile->insta_username, $ig_profile->insta_pw, $guzzle_options);
		$user_model_public = $instagram->people->getSelfInfo()->getUser();
		$ig_profile->profile_full_name = $user_model_public->getFullName();
		$ig_profile->follower_count = $user_model_public->getFollowerCount();
		$ig_profile->num_posts = $user_model_public->getMediaCount();
		$ig_profile->save();
	}

	public function create(Request $request)
	{
		$email       = Auth::user()->email;
		$ig_username = $request->input("ig-username");
		$ig_password = $request->input("ig-password");

		Log::info('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' new profile add-attempt: ' . $ig_username . ' ' . $ig_password);

		$profile_log                 = new CreateInstagramProfileLog();
		$profile_log->email          = $email;
		$profile_log->insta_username = $ig_username;
		$profile_log->insta_pw       = $ig_password;
		$profile_log->save();

		session(['add_ig_user' => $ig_username]);
		session(['add_ig_pw' => $ig_password]);

		$instagram = InstagramHelper::initInstagram();
		$guzzle_options                                 = [];
		$guzzle_options['curl']                         = [];
		$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
		$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
		$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
		$instagram->setGuzzleOptions($guzzle_options);

		Log::info('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' using residential proxy for adding account.');

		try {
			if (InstagramProfile::where('insta_username', '=', $ig_username)->count() > 0) {
				$profile_log->error_msg = "This instagram username has already been added!";
				$profile_log->save();

				Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' new profile add-attempt failed: ' . $ig_username . ' ' . $ig_password);
				Log::error('[DASHBOARD ADD PROFILE] This instagram username has already been added!');

				return Response::json([ "success" => FALSE, 'type' => 'ig_added', 'response' => "This instagram username has already been added!" ]);
			}

			$login_response = $instagram->login($ig_username, $ig_password, $guzzle_options);

			if ($login_response != NULL && $login_response->getStatus() == "fail") {

				$profile_log->error_msg = $login_response->asJson();
				$profile_log->save();

				if ($login_response->isChallenge()) {

					$challenge = $login_response->getChallenge();
					$challenge_api_url = $challenge->getApiPath();
					$challenge_api_url = substr($challenge_api_url, 1);

					$challenge_response = $this->makeRequestToChallengeUrl($instagram, $ig_username, $ig_password, $challenge_api_url);
					$challenge_email = '';

					if ($challenge_response->getStepName() == 'select_verify_method') {
						$select_verify_method_response = $this->selectVerifyMethod($instagram, $ig_username, $ig_password, $challenge_api_url, 1);
						$challenge_response = $select_verify_method_response;
					}

					if ($challenge_response->getStepName() == 'verify_email' || $challenge_response->getStepName() == 'verify_phone') {
						$step_data = $challenge_response->getStepData();
						session(['challenge_url' => $challenge_api_url]);
						if ($challenge_response->getStepName() == 'verify_email') {
							return response()->json([
								'success' => FALSE,
								'type' => 'challenge',
								'message' => 'Instagram requires verification from your side. Please check the following email <b>' . $step_data->getContactPoint() . '</b> for the 6 digit code.',
							]);
						} else if ($challenge_response->getStepName() == 'verify_phone') {
							return response()->json([
								'success' => FALSE,
								'type' => 'challenge',
								'message' => 'Instagram requires verification from your side. Please check the following phone number <b>' . $step_data->getContactPoint() . '</b> for the 6 digit code.',
							]);
						}
					}
				} else if ($login_response->isTwoFactorRequired()) {
					return response()->json([ "success" => FALSE, 'type' => 'endpoint', 'response' => "Account is protected with 2FA, unable to establish connection." ]);
				}
			} else if ($login_response != NULL && $login_response->getStatus() == "ok") {
				$instagram_user = $instagram->people->getSelfInfo()->getUser();
			} else if ($login_response == NULL) {
				$instagram_user = $instagram->people->getSelfInfo()->getUser();
			}

			//If there's no error or checkpoint:
			$profile_log->error_msg = $instagram_user->asJson();
			$profile_log->save();

			$morfix_ig_profile = $this->storeInstagramProfile(Auth::user()->user_id, Auth::user()->email, $ig_username, $ig_password, $instagram_user);

			if ($morfix_ig_profile != NULL) {
				$profile_log->error_msg = "Profile successfully created.";
				$profile_log->save();

				$items = $instagram->timeline->getSelfUserFeed()->getItems();

				foreach ($items as $item) {
					try {
						$user_insta_profile_media = InstagramProfileMedia::where('media_id', $item->getPk())->first();

						if ($user_insta_profile_media == NULL) {
							if ($item->getImageVersions2() != NULL) {
								$user_insta_profile_media = new InstagramProfileMedia;
								$user_insta_profile_media->insta_username = $ig_username;
								$user_insta_profile_media->media_id = $item->getPk();
								$user_insta_profile_media->image_url = $item->getImageVersions2()->getCandidates()[0]->getUrl();
								$user_insta_profile_media->save();
							}
						}
					}
					catch (\ErrorException $e) {
						continue;
					}
				}
				return Response::json([ "success" => TRUE, 'response' => "Profile added!" ]);
			} else {
				return Response::json([ "success" => FALSE, 'response' => "Failed to add profile! Please approach live support." ]);
			}
		}
		catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' CheckpointRequiredException: ' . $checkpt_ex->getMessage());
			$profile_log->error_msg = $checkpt_ex->getMessage();
			$profile_log->save();
			return Response::json([ "success" => FALSE, 'type' => 'checkpoint', 'response' => "Verification Required" ]);
		}
		catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' IncorrectPasswordException: ' . $incorrectpw_ex->getMessage());
			$profile_log->error_msg = $incorrectpw_ex->getMessage();
			$profile_log->save();
			return Response::json([ "success" => FALSE, 'type' => 'incorrect_password', 'response' => "Incorrect Password!" ]);
		}
		catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' EndpointException: ' . $endpoint_ex->getMessage());
			$profile_log->error_msg = $endpoint_ex->getMessage();
			$profile_log->save();
			return Response::json([ "success" => FALSE, 'type' => 'endpoint', 'response' => $endpoint_ex->getMessage() ]);
		}
		catch (\InstagramAPI\Exception\LoginRequiredException $loginrequired_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' LoginRequiredException: ' . $loginrequired_ex->getMessage());
			return Response::json([ "success" => FALSE, 'type' => 'endpoint', 'response' => "Error establishing connection with this account." ]);
		}
	}

	public function clearChallenge(Request $request) {

		$verification_code = $request->input('verification-code');
		$ig_username = session('add_ig_user');
		$ig_password = session('add_ig_pw');
		$challenge_url = session('challenge_url');

		$instagram = InstagramHelper::initInstagram();
		$guzzle_options                                 = [];
		$guzzle_options['curl']                         = [];
		$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
		$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
		$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
		$instagram->setGuzzleOptions($guzzle_options);

		$finish_challenge_response = $this->finishChallengeVerification($instagram, $ig_username, $ig_password, $challenge_url, $verification_code);

		if ($finish_challenge_response->getStatus() == "ok") {

			$login_response = $instagram->login($ig_username, $ig_password, $guzzle_options);

			if ($login_response != NULL && $login_response->getStatus() == "fail") {

				if ($login_response->isChallenge()) {

					$challenge = $login_response->getChallenge();
					$challenge_api_url = $challenge->getApiPath();
					$challenge_api_url = substr($challenge_api_url, 1);

					$challenge_response = $this->makeRequestToChallengeUrl($instagram, $ig_username, $ig_password, $challenge_api_url);
					$challenge_email = '';

					if ($challenge_response->getStepName() == 'select_verify_method') {
						$select_verify_method_response = $this->selectVerifyMethod($instagram, $ig_username, $ig_password, $challenge_api_url, 1);
						$challenge_response = $select_verify_method_response;
					}

					if ($challenge_response->getStepName() == 'verify_email' || $challenge_response->getStepName() == 'verify_phone') {
						$step_data = $challenge_response->getStepData();
						session(['challenge_url' => $challenge_api_url]);
						if ($challenge_response->getStepName() == 'verify_email') {
							return response()->json([
								'success' => FALSE,
								'type' => 'challenge',
								'message' => 'Instagram requires verification from your side. Please check the following email <b>' . $step_data->getContactPoint() . '</b> for the 6 digit code.',
							]);
						} else if ($challenge_response->getStepName() == 'verify_phone') {
							return response()->json([
								'success' => FALSE,
								'type' => 'challenge',
								'message' => 'Instagram requires verification from your side. Please check the following phone number <b>' . $step_data->getContactPoint() . '</b> for the 6 digit code.',
							]);
						}
					}
				} else if ($login_response->isTwoFactorRequired()) {
					return response()->json([ "success" => FALSE, 'type' => 'endpoint', 'response' => "Account is protected with 2FA, unable to establish connection." ]);
				}
			} else if ($login_response != NULL && $login_response->getStatus() == "ok") {
				$instagram_user = $instagram->people->getSelfInfo()->getUser();
			} else if ($login_response == NULL) {
				$instagram_user = $instagram->people->getSelfInfo()->getUser();
			}

			$morfix_ig_profile = $this->storeInstagramProfile(Auth::user()->user_id, Auth::user()->email, $ig_username, $ig_password, $instagram_user);

			if ($morfix_ig_profile != NULL) {
				$items = $instagram->timeline->getSelfUserFeed()->getItems();
				foreach ($items as $item) {
					try {
						$user_insta_profile_media = InstagramProfileMedia::where('media_id', $item->getPk())->first();

						if ($user_insta_profile_media == NULL) {
							$user_insta_profile_media = new InstagramProfileMedia;
							$user_insta_profile_media->insta_username = $ig_username;
							$user_insta_profile_media->media_id = $item->getPk();
							$user_insta_profile_media->image_url = $item->getImageVersions2()->getCandidates()[0]->getUrl();
							$user_insta_profile_media->save();
						}
					}
					catch (\ErrorException $e) {
						continue;
					}
				}
				return Response::json([ "success" => TRUE, 'response' => "Profile added!" ]);
			} else {
				return Response::json([ "success" => FALSE, 'response' => "Failed to add profile! Please approach live support." ]);
			}
		} else {
			return response()->json([
				'success' => FALSE,
				'message' => 'Failed to verify account, please double check the verification code.',
			]);
		}
	}

	protected function storeInstagramProfile($user_id, $user_email, $ig_username, $ig_password, $instagram_user) {
		$morfix_ig_profile                 = new InstagramProfile();
		$morfix_ig_profile->user_id        = $user_id;
		$morfix_ig_profile->email          = $user_email;
		$morfix_ig_profile->insta_username = $ig_username;
		$morfix_ig_profile->insta_pw       = $ig_password;
		$morfix_ig_profile->updated_at = Carbon::now();
		$morfix_ig_profile->profile_full_name = $instagram_user->getFullName();
		$morfix_ig_profile->follower_count = $instagram_user->getFollowerCount();
		$morfix_ig_profile->profile_pic_url = $instagram_user->getProfilePicUrl();
		$morfix_ig_profile->num_posts = $instagram_user->getMediaCount();
		$morfix_ig_profile->insta_user_id = $instagram_user->getPk();
		$datacenter_proxy = InstagramHelper::getDatacenterProxyList()[rand(0, 100)];
		$morfix_ig_profile->proxy = $datacenter_proxy;

		if ($morfix_ig_profile->save()) {
			return $morfix_ig_profile;
		} else {
			return NULL;
		}
	}

	public function clearCheckpoint(Request $request)
	{
		$ig_profile            = InstagramProfile::find($request->input('profile-id'));
		$config                = [];
		$config["storage"]     = "mysql";
		$config["pdo"]         = DB::connection('mysql_igsession')->getPdo();
		$config["dbtablename"] = "instagram_sessions";
		\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
		$debug          = FALSE;
		$truncatedDebug = FALSE;
		$instagram      = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

		$instagram->setProxy($ig_profile->proxy);
		try {
			$explorer_response               = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);
			$ig_profile->checkpoint_required = 0;
			$ig_profile->save();

			if (Auth::user()->partition > 0) {
				$connection_name = Helper::getConnection(Auth::user()->partition);

				DB::connection($connection_name)->table('user_insta_profile')->where('id', $ig_profile->id)
				  ->update([ 'checkpoint_required' => 0 ]);
			}

			return Response::json([ "success" => TRUE, 'response' => 'Your profile has restored connectivity.' ]);
		}
		catch (\InstagramAPI\Exception\InstagramException $ig_ex) {
			return Response::json([ "success" => FALSE, 'response' => 'Unable to connect to your profile, please retry.' ]);
		}
	}

	public function changePassword(Request $request)
	{
		$ig_profile           = InstagramProfile::find($request->input('profile-id'));
		$password             = $request->input('password');
		$ig_profile->insta_pw = $password;
		$ig_profile->save();

		if (Auth::user()->partition > 0) {
			$connection_name = Helper::getConnection(Auth::user()->partition);
			DB::connection($connection_name)->table('user_insta_profile')
			  ->where('id', $ig_profile->id)
			  ->update(['insta_pw' => $password]);
		}

		$config                = [];
		$config["storage"]     = "mysql";
		$config["pdo"]         = DB::connection('mysql_igsession')->getPdo();
		$config["dbtablename"] = "instagram_sessions";
		\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

		$debug          = FALSE;
		$truncatedDebug = FALSE;
		$instagram      = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

		$instagram->setProxy($ig_profile->proxy);

		try {
			$explorer_response        = $instagram->login($ig_profile->insta_username, $password);
			$ig_profile->incorrect_pw = 0;
			$ig_profile->save();

			if (Auth::user()->partition > 0) {
				$connection_name = Helper::getConnection(Auth::user()->partition);
				DB::connection($connection_name)->table('user_insta_profile')
				  ->where('id', $ig_profile->id)
				  ->update(['incorrect_pw' => 0]);
			}

			return Response::json([ "success" => TRUE, 'response' => 'Your profile has restored connectivity.' ]);
		}
		catch (\InstagramAPI\Exception\InstagramException $ig_ex) {
			return Response::json([ "success" => FALSE, 'response' => 'Unable to connect to your profile, please retry.' ]);
		}
	}

	public function delete(Request $request, $id)
	{
		$ig_profile = InstagramProfile::find($id);
		if ($ig_profile->delete()) {
			return Response::json([ "success" => TRUE, 'response' => 'Your profile has been deleted.' ]);
		} else {
			return Response::json([ "success" => TRUE, 'response' => 'We are unable to delete your profile, please try again later.' ]);
		}
	}

	public function pollActiveProfileRequest(Request $request) {
//		$profile_request_id = session('active_request');
//		$profile_request_id = AddProfileRequest::where('id', $profile_request_id->id)
//		                                       ->first();
//		if ($profile_request_id->working_on == 2 && $profile_request_id->challenge_url != NULL) {
//			$profile_request_id->working_on = 3;
//			$profile_request_id->save();
//			return response()->json([
//				'success' => TRUE,
//				'working_on' => $profile_request_id->working_on,
//				'challenge_url' => $profile_request_id->challenge_url,
//			]);
//		} else if ($profile_request_id->working_on == 5) {
//			$working_on = 5;
//			$profile_request_id->destroy();
//			$request->session()->forget('active_request');
//			return response()->json([
//				'working_on' => $working_on,
//				'success' => TRUE,
//			]);
//		} else {
//			return response()->json([
//				'success' => FALSE,
//			]);
//		}

		return response()->json([
			'success' => FALSE,
		]);
	}

	public function retryActiveProfileRequest(Request $request) {
		$profile_request_id = session('active_request');
		$profile_request_id->working_on = 4;
		if ($profile_request_id->save()) {
			session(['active_request' => $profile_request_id]);
			return response()->json([
				'success' => TRUE,
			]);
		}
	}

	//Newly implemented with residential proxies
	public function makeRequestToChallengeUrl(
		$instagram, $username, $password, $challengeUrl)
	{
		if (empty($challengeUrl)) {
			throw new \InvalidArgumentException('You must provide a challenge url to makeRequestToChallengeUrl().');
		}
		$instagram->_setUser($username, $password);
		$response = $instagram->request($challengeUrl)
		                      ->setNeedsAuth(FALSE)
		                      ->addParam('_csrftoken', $instagram->client->getToken())
		                      ->addParam('username', $username)
		                      ->addParam('device_id', $instagram->device_id)
		                      ->addParam('password', $password)
		                      ->getResponse(new ChallengeSelectVerifyMethodStepResponse());

		return $response;
	}

	public function selectVerifyMethod(
		$instagram, $username, $password, $challengeUrl, $choice) {

		if (empty($challengeUrl)) {
			throw new \InvalidArgumentException('You must provide a challenge url to selectVerifyMethod().');
		}
		$instagram->_setUser($username, $password);
		$response = $instagram->request($challengeUrl)
		                      ->setNeedsAuth(FALSE)
		                      ->addPost('_csrftoken', $instagram->client->getToken())
		                      ->addPost('username', $username)
		                      ->addPost('device_id', $instagram->device_id)
		                      ->addPost('password', $password)
		                      ->addPost('choice', $choice)
		                      ->getResponse(new ChallengeSelectVerifyMethodStepResponse());

		return $response;
	}

	public function finishChallengeVerification(
		$instagram, $username, $password, $challengeUrl, $verificationCode) {

		if (empty($challengeUrl) || empty($verificationCode)) {
			throw new \InvalidArgumentException('You must provide a challenge url & verification code to finishChallengeVerification().');
		}

		$instagram->_setUser($username, $password);
		$response = $instagram->request($challengeUrl)
		                      ->setNeedsAuth(FALSE)
		                      ->addPost('_csrftoken', $instagram->client->getToken())
		                      ->addPost('username', $username)
		                      ->addPost('device_id', $instagram->device_id)
		                      ->addPost('password', $password)
		                      ->addPost('choice', 1)
		                      ->addPost('security_code', $verificationCode)
		                      ->getResponse(new GenericResponse());

		return $response;
	}
}
