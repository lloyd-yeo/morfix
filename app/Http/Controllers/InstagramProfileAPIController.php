<?php

namespace App\Http\Controllers;

use App\CreateInstagramProfileLog;
use App\Helper;
use App\InstagramHelper;
use App\InstagramProfile;
use App\InstagramProfileMedia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Exception\BadRequestException;
use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Exception\CheckpointRequiredException;
use InstagramAPI\Exception\EndpointException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\LoginRequiredException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\SentryBlockException;
use InstagramAPI\Response\ChallengeSelectVerifyMethodStepResponse;
use InstagramAPI\Response\GenericResponse;
use InstagramAPI\Response\MsisdnHeaderResponse;
use Log;
use Response;

class InstagramProfileAPIController extends Controller
{
	public function create(Request $request)
	{
		$email       = $request->input("email");
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

		if (!session()->has('proxy_session_id')) {
			session(['proxy_session_id' => str_random(9)]);
		}

		$instagram = InstagramHelper::initInstagram();

		$guzzle_options                                 = [];
		$guzzle_options['curl']                         = [];
		$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
		$residential_proxy_userpass = 'customer-rmorfix-cc-US-city-san_jose-sessid-' . session('proxy_session_id') . ':dXehM3e7bU';
		$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = $residential_proxy_userpass;
		$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;

		$instagram->setGuzzleOptions($guzzle_options);

		Log::info('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' using residential proxy [' . $residential_proxy_userpass . '] for adding account.');

		try {
			if (InstagramProfile::where('insta_username', '=', $ig_username)->count() > 0) {
				$profile_log->error_msg = "This instagram username has already been added!";
				$profile_log->save();

				Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' new profile add-attempt failed, profile exists: ' . $ig_username . ' ' . $ig_password);
				Log::error('[DASHBOARD ADD PROFILE] This instagram username has already been added!');

				return Response::json([ "success" => FALSE, 'type' => 'ig_added', 'message' =>"This instagram username has already been added!" ]);
			}

			$login_response = $instagram->login($ig_username, $ig_password);

			if ($login_response != NULL && $login_response->getStatus() == "fail") {
				Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' new profile add-attempt failed: ' . $ig_username . ' ' . $ig_password);
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
								'message' => 'Instagram requires verification from your side. Please check your email (<b>' . $step_data->getContactPoint() . '</b>) for a email from Instagram containing the 6 digit code & enter it below. NOTE: If you get a notification from New York, San Jose just click "It was me".',
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
					Log::info('[DASHBOARD ADD PROFILE] ' . $ig_username . ' account is protected with 2FA.');
					session(['2fa_identifier' => $login_response->getTwoFactorInfo()->getTwoFactorIdentifier()]);
					return response()->json([ "success" => FALSE, 'type' => '2fa', 'message' => "Account is protected with 2FA, unable to establish connection. Please enter the verification code below:" ]);
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
				return Response::json([ "success" => TRUE, 'message' => "Profile added!" ]);
			} else {
				return Response::json([ "success" => FALSE, 'message' => "Failed to add profile! Please approach live support." ]);
			}
		}
		catch (CheckpointRequiredException $checkpt_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' CheckpointRequiredException: ' . $checkpt_ex->getMessage());
			$profile_log->error_msg = $checkpt_ex->getMessage();
			$profile_log->save();
			return Response::json([ "success" => FALSE, 'type' => 'checkpoint', 'message' => "Verification Required" ]);
		}
		catch (IncorrectPasswordException $incorrectpw_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' IncorrectPasswordException: ' . $incorrectpw_ex->getMessage());
			$profile_log->error_msg = $incorrectpw_ex->getMessage();
			$profile_log->save();

			return Response::json([ "success" => FALSE, 'type' => 'incorrect_pw', 'message' => "You've entered an incorrect password!" ]);
		}
		catch (EndpointException $endpoint_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' EndpointException: ' . $endpoint_ex->getMessage());
			$profile_log->error_msg = $endpoint_ex->getMessage();
			$profile_log->save();
			return Response::json([ "success" => FALSE, 'type' => 'endpoint', 'message' => $endpoint_ex->getMessage() ]);
		}
		catch (LoginRequiredException $loginrequired_ex) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' LoginRequiredException: ' . $loginrequired_ex->getMessage());

			return Response::json([ "success" => FALSE, 'type' => 'endpoint', 'message' => "Error establishing connection with this account." ]);
		} catch (SentryBlockException $sentryBlockException) {
			Log::error('[DASHBOARD ADD PROFILE] ' . Auth::user()->email . ' SentryBlockException: ' . $sentryBlockException->getMessage());

			return Response::json([ "success" => FALSE, 'type' => 'server', 'message' => "Temporarily lost connection. Do try again!" ]);
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

	protected function updateInstagramProfileChallengeSuccess($morfix_ig_profile, $instagram_user) {
		$morfix_ig_profile->updated_at = Carbon::now();
		$morfix_ig_profile->profile_full_name = $instagram_user->getFullName();
		$morfix_ig_profile->follower_count = $instagram_user->getFollowerCount();
		$morfix_ig_profile->profile_pic_url = $instagram_user->getProfilePicUrl();
		$morfix_ig_profile->num_posts = $instagram_user->getMediaCount();
		$morfix_ig_profile->insta_user_id = $instagram_user->getPk();
		$morfix_ig_profile->challenge_required = 0;
		$datacenter_proxy = InstagramHelper::getDatacenterProxyList()[rand(0, 100)];
		$morfix_ig_profile->proxy = $datacenter_proxy;

		if ($morfix_ig_profile->save()) {
			return $morfix_ig_profile;
		} else {
			return NULL;
		}
	}
}
