<?php

namespace App;

use Illuminate\Support\Facades\DB;
use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\CheckpointRequiredException;
use InstagramAPI\Exception\EndpointException;
use InstagramAPI\Exception\ForcedPasswordResetException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\InstagramException;
use InstagramAPI\Exception\InvalidUserException;
use InstagramAPI\Exception\LoginRequiredException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\NotFoundException;
use InstagramAPI\Exception\SentryBlockException;
use InstagramAPI\Instagram as Instagram;
use App\RedisRepository;
use Log;
use \Redis;

class InstagramHelper extends \InstagramAPI\Request
{
	public static function initInstagram($debug = FALSE)
	{
		try {
			$config            = [];
			$config["storage"] = "redis";
			$config["redishost"] =  config("database.redis.default.host");
			$config["redisport"] =  config("database.redis.default.port");

			Log::info("[INSTAGRAM HELPER INIT] Initializing Instagram API with " . config("database.redis.default.host"));

			\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = TRUE;
			$truncatedDebug                                             = FALSE;
			$instagram                                                  = new Instagram($debug, $truncatedDebug, $config);

			return $instagram;
		}
		catch (\RedisException $redisException) {
			dump($redisException);
			return NULL;
		}
	}

	/**
	 * @param Instagram        $instagram   The Instagram API instance
	 * @param InstagramProfile $ig_profile  The profile to compare & set proxies for the Instagram API
	 * @param                  $return_opts 1 for the Instagram API instance, 2 for an array of [InstagramAPI Instance,
	 *                                      Guzzle-Options]
	 *
	 * @return Instagram       The Instagram API instance only or an array of [InstagramAPI Instance, Guzzle-Options]
	 */
	public static function setProxy(Instagram $instagram, InstagramProfile $ig_profile, $return_opts = 1)
	{
		$guzzle_options = NULL;

		if ($ig_profile->proxy == NULL) {
			$guzzle_options                                 = [];
			$guzzle_options['curl']                         = [];
			$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
			$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
			$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
		} else if (strpos($ig_profile->proxy, 'http') === 0) {
			$guzzle_options                                 = [];
			$guzzle_options['curl']                         = [];
			$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
			$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
			$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
			$ig_profile->proxy                              = NULL;
			$ig_profile->save();
		} else {
			$guzzle_options                                 = [];
			$guzzle_options['curl']                         = [];
			$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://' . $ig_profile->proxy;
			$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'morfix:dXehM3e7bU';
			$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
		}

		$instagram->setGuzzleOptions($guzzle_options);

		switch ($return_opts) {
			case 1:
				return $instagram;
			case 2:
				return [ $instagram, $guzzle_options ];
			default:
				return $instagram;
		}
	}

	/*
	 * TRUE if the login was successful.
	 * FALSE if the the login failed.
	 */
	public static function loginWithCustomProxy(Instagram $instagram, $insta_username, $insta_password, $debug = 1, $proxy)
	{
		$flag    = FALSE;
		$message = '';

		if ($debug == 1) {
			echo("Verifying proxy for profile: [" . $insta_username . "]\n");
		}

		$instagram->setProxy($proxy);

		if ($debug == 1) {
			echo("Logging in profile: [" . $insta_username . "] [" . $insta_password . "]\n");
		}

		try {
			$explorer_response = $instagram->login($insta_username, $insta_password);
			if ($debug == 1) {
				dump($explorer_response);
			}
			$flag = TRUE;
		}
		catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
			//			$ig_profile->checkpoint_required = 1;
			//			$ig_profile->save();
			$message = "CheckpointRequiredException\n";
		}
		catch (\InstagramAPI\Exception\InvalidUserException $invalid_user_ex) {
			//			$ig_profile->invalid_user = 1;
			//			$ig_profile->save();
		}
		catch (NetworkException $network_ex) {
			dump($network_ex);
			//			$ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
			//			$ig_profile->save();
			//			InstagramHelper::verifyAndReassignProxy($ig_profile);
			$message = "NetworkException";
			//			try {
			//				$instagram->login($insta_username, $insta_password);
			//				$flag = true;
			//			} catch (\InstagramAPI\Exception\InstagramException $login_ex) {
			//				$message .= " with InstagramException\n";
			//			}
		}
		catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {

		}
		catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {

		}
		catch (\InstagramAPI\Exception\ForcedPasswordResetException $forcedpwreset_ex) {
			//			$ig_profile->incorrect_pw = 1;
			//			$ig_profile->save();
		}
		catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
			//			$ig_profile->incorrect_pw = 1;
			//			$ig_profile->save();
			$message = "IncorrectPasswordException\n";
		}
		catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
			//			$ig_profile->invalid_user = 1;
			//			$ig_profile->save();
			$message = "AccountDisabledException\n";
		}
		catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
			//			$ig_profile->incorrect_pw = 1;
			//			$ig_profile->save();
			$message = "IncorrectPasswordException\n";
		}
		catch (\InstagramAPI\Exception\ChallengeRequiredException $challengerequired_ex) {
			//			$ig_profile->checkpoint_required = 1;
			//			$ig_profile->save();
			$message = "ChallengeRequiredException\n";
		}
		catch (\InstagramAPI\Exception\SentryBlockException $sentryblock_ex) {
			$flag = FALSE;
		}

		if (!$flag && $debug == 1) {
			echo '[' . $insta_username . '] Error:  ' . $message . "\n";
		} else if ($flag && $debug == 1) {
			echo '[' . $insta_username . '] has been logged in.' . "\n";
		}

		return $flag;
	}

	/*
	 * Wrapper function for logging in a existing Morfix Instagram Profile.
	 * TRUE if the login was successful.
	 * FALSE if the the login failed.
	 */
	public static function login(Instagram $instagram, InstagramProfile $ig_profile, $debug = 1)
	{
		$flag    = FALSE;
		$message = '';

		if ($debug == 1) {
			Log::info("[INSTAGRAM HELPER LOGIN] Verifying proxy for profile: [" . $ig_profile->insta_username . "]");
			echo("[INSTAGRAM HELPER LOGIN] Verifying proxy for profile: [" . $ig_profile->insta_username . "]\n");
		}

		if ($debug == 1) {
			Log::info("[INSTAGRAM HELPER LOGIN] Logging in profile: [" . $ig_profile->insta_username . "] [" . $ig_profile->insta_pw . "]");
			echo("[INSTAGRAM HELPER LOGIN] Logging in profile: [" . $ig_profile->insta_username . "] [" . $ig_profile->insta_pw . "]\n");
		}

		if (strpos($ig_profile->proxy, 'http') === 0) {
			if ($debug == 1) {
				Log::info("[INSTAGRAM HELPER LOGIN] Logging in profile: [" . $ig_profile->insta_username . "] profile hasn't been re-verified.");
				echo("[INSTAGRAM HELPER LOGIN] Logging in profile: [" . $ig_profile->insta_username . "] profile hasn't been re-verified.\n");
			}
			return FALSE;

		} else if ($ig_profile->proxy == NULL) {
			if ($debug == 1) {
				Log::info("[INSTAGRAM HELPER LOGIN] Logging in profile: [" . $ig_profile->insta_username . "] profile hasn't been re-verified, NULL proxy.");
				echo("[INSTAGRAM HELPER LOGIN] Logging in profile: [" . $ig_profile->insta_username . "] profile hasn't been re-verified, NULL proxy.\n");
			}
			return FALSE;
		}

		try {

			$proxy_settings = InstagramHelper::setProxy($instagram, $ig_profile, 2);
			$instagram      = $proxy_settings[0];
			$guzzle_options = $proxy_settings[1];
			$instagram->setGuzzleOptions($guzzle_options);

			if ($ig_profile->ig_throttled == 1) {
				Log::info('[INSTAGRAM HELPER LOGIN] ' . $ig_profile->insta_username . ' is throttled.\n');
				echo('[INSTAGRAM HELPER LOGIN] ' . $ig_profile->insta_username . ' is throttled.\n');
				return FALSE;
			}

			$explorer_response = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);

			if ($explorer_response != NULL) {

				$explorer_response_json = $explorer_response->asJson();

				Log::info('[INSTAGRAM HELPER LOGIN] ' . $ig_profile->insta_username . ' login_resp: ' . $explorer_response_json);

				$response_array = json_decode($explorer_response_json, TRUE);

				if (array_key_exists('logged_in_user', $response_array)) {
					$logged_in_user = $response_array['logged_in_user'];
				}

				if ($explorer_response->isOk()) {
					$flag = TRUE;
				} else {
					if ($explorer_response->isChallenge()) {
						$ig_profile->challenge_required = 1;
						$ig_profile->save();
					} else if ($explorer_response->isTwoFactorRequired()) {
						$ig_profile->challenge_required = 1;
						$ig_profile->save();
					}
					$flag = FALSE;
				}

			} else {
				Log::info('[INSTAGRAM HELPER LOGIN] ' . $ig_profile->insta_username . ' login_resp IS NULL.');
				if ($instagram->isMaybeLoggedIn) {
					$flag = TRUE;
				} else {
					$flag = FALSE;
				}
			}
		}
		catch (CheckpointRequiredException $checkpoint_ex) {

			Log::error("[INSTAGRAM HELPER LOGIN CheckpointRequiredException] " . $ig_profile->insta_username . " " . $checkpoint_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN CheckpointRequiredException] " . $ig_profile->insta_username . " " . $checkpoint_ex->getTraceAsString());

			$ig_profile->checkpoint_required = 1;
			$ig_profile->save();
			$message = "CheckpointRequiredException\n";

			$flag = FALSE;

		}
		catch (InvalidUserException $invalid_user_ex) {

			Log::error("[INSTAGRAM HELPER LOGIN InvalidUserException] " . $ig_profile->insta_username . " " . $invalid_user_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN InvalidUserException] " . $ig_profile->insta_username . " " . $invalid_user_ex->getTraceAsString());

			$ig_profile->invalid_user = 1;
			$ig_profile->save();

			$flag = FALSE;

		}
		catch (NetworkException $network_ex) {

			Log::error("[INSTAGRAM HELPER LOGIN NetworkException] " . $ig_profile->insta_username . " " . $network_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN NetworkException] " . $ig_profile->insta_username . " " . $network_ex->getTraceAsString());

			//			$ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
			//			$ig_profile->save();
			//			InstagramHelper::verifyAndReassignProxy($ig_profile);
			//			$message = "NetworkException";
			//			try {
			//				$instagram->login($ig_profile->insta_username, $ig_profile->insta_pw, NULL);
			//				$flag = true;
			//			} catch (InstagramException $login_ex) {
			//				$message .= " with InstagramException\n";
			//			}

			$flag = FALSE;

		}
		catch (EndpointException $endpoint_ex) {

			Log::error("[INSTAGRAM HELPER LOGIN EndpointException] " . $ig_profile->insta_username . " " . $endpoint_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN EndpointException] " . $ig_profile->insta_username . " " . $endpoint_ex->getTraceAsString());

			$flag = FALSE;

		}
		catch (BadRequestException $badrequest_ex) {

			Log::error("[INSTAGRAM HELPER LOGIN BadRequestException] " . $ig_profile->insta_username . " " . $badrequest_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN BadRequestException] " . $ig_profile->insta_username . " " . $badrequest_ex->getTraceAsString());

			$flag = FALSE;

		}
		catch (ForcedPasswordResetException $forcedpwreset_ex) {

			Log::error("[INSTAGRAM HELPER LOGIN ForcedPasswordResetException] " . $ig_profile->insta_username . " " . $forcedpwreset_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN ForcedPasswordResetException] " . $ig_profile->insta_username . " " . $forcedpwreset_ex->getTraceAsString());
			$ig_profile->incorrect_pw = 1;
			$ig_profile->save();

			$flag = FALSE;

		}
		catch (IncorrectPasswordException $incorrectpw_ex) {
			Log::error("[INSTAGRAM HELPER LOGIN IncorrectPasswordException] " . $ig_profile->insta_username . " " . $incorrectpw_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN IncorrectPasswordException] " . $ig_profile->insta_username . " " . $incorrectpw_ex->getTraceAsString());
			$ig_profile->incorrect_pw = 1;
			$ig_profile->save();
			$message = "IncorrectPasswordException\n";

			$flag = FALSE;

		}
		catch (AccountDisabledException $accountdisabled_ex) {
			Log::error("[INSTAGRAM HELPER LOGIN AccountDisabledException] " . $ig_profile->insta_username . " " . $accountdisabled_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN AccountDisabledException] " . $ig_profile->insta_username . " " . $accountdisabled_ex->getTraceAsString());
			$ig_profile->invalid_user = 1;
			$ig_profile->save();
			$message = "AccountDisabledException\n";

			$flag = FALSE;

		}
		catch (ChallengeRequiredException $challengerequired_ex) {
			Log::error("[INSTAGRAM HELPER LOGIN ChallengeRequiredException] " . $ig_profile->insta_username . " " . $challengerequired_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN ChallengeRequiredException] " . $ig_profile->insta_username . " " . $challengerequired_ex->getTraceAsString());
			$ig_profile->challenge_required = 1;
			$ig_profile->save();
			$message = "ChallengeRequiredException\n";

			$flag = FALSE;
		}
		catch (SentryBlockException $sentryblock_ex) {
			Log::error("[INSTAGRAM HELPER LOGIN SentryBlockException] " . $ig_profile->insta_username . " " . $sentryblock_ex->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN SentryBlockException] " . $ig_profile->insta_username . " " . $sentryblock_ex->getTraceAsString());

			$flag = FALSE;

		}
		catch (LoginRequiredException $loginRequiredException) {
			Log::error("[INSTAGRAM HELPER LOGIN LoginRequiredException] " . $ig_profile->insta_username . " " . $loginRequiredException->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN LoginRequiredException] " . $ig_profile->insta_username . " " . $loginRequiredException->getTraceAsString());

			$flag = FALSE;
		}
		catch (ThrottledException $throttledException) {
			dump($throttledException);
			Log::error("[INSTAGRAM HELPER LOGIN ThrottledException] " . $ig_profile->insta_username . " " . $throttledException->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN ThrottledException] " . $ig_profile->insta_username . " " . $throttledException->getTraceAsString());

			$ig_profile->ig_throttled = 1;
			$ig_profile->save();

			$flag = FALSE;
		}
		catch (InstagramException $instagramException) {
			dump($instagramException);

			if ($instagramException->getMessage() == 'Throttled by Instagram because of too many API requests.') {
				$ig_profile->ig_throttled = 1;
				$ig_profile->save();
			}

			Log::error("[INSTAGRAM HELPER LOGIN InstagramException] " . $ig_profile->insta_username . " " . $instagramException->getMessage());
			Log::error("[INSTAGRAM HELPER LOGIN InstagramException] " . $ig_profile->insta_username . " " . $instagramException->getTraceAsString());

			$flag = FALSE;
		}

		if (!$flag) {
			echo '[' . $ig_profile->insta_username . '] Error:  ' . $message . "\n";
		}

		if (!$flag && $debug == 1) {
			Log::info('[' . $ig_profile->insta_username . '] Error:  ' . $message . "\n");
		} else if ($flag && $debug == 1) {
			Log::info('[' . $ig_profile->insta_username . '] has been logged in.' . "\n");
		}

		return $flag;
	}

	public static function getUserIdForNicheUsername(Instagram $instagram, $target_username)
	{
		$username_id = NULL;
		$username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));

		return $username_id;
	}

	public static function getUserIdForName(Instagram $instagram, $target_username)
	{
		$username_id = NULL;
		try {
			$username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
		}
		catch (NotFoundException $not_found_ex) {
			$target_username->invalid = 1;
			$target_username->save();
		}

		return $username_id;
	}

	public static function getUserInfo(Instagram $instagram, $ig_profile)
	{
		try {
			$user_response = $instagram->people->getInfoById($ig_profile->insta_user_id);

			return $user_response->getUser();
		}
		catch (\InstagramAPI\Exception\InstagramException $insta_ex) {
			echo "[" . $ig_profile->insta_username . "] " . $insta_ex->getMessage() . "\n";
			echo $insta_ex->getTraceAsString() . "\n";
		}
	}

	public static function getTargetUsernameFollowers(Instagram $instagram, $target_username, $username_id)
	{
		$rank_token = \InstagramAPI\Signatures::generateUUID(TRUE);
		echo("\n[GET TARGET USERNAME FOLLOWERS] UUID Generated: " . $rank_token);
		$user_follower_response = $instagram->people->getFollowers($username_id, $rank_token, NULL, NULL);
		$users_to_follow        = $user_follower_response->getUsers();

		return $users_to_follow;
	}

	public static function getUserFeed(Instagram $instagram, $user_to_like)
	{
		//Get the feed of the user to like.
		try {
			if ($user_to_like == NULL) {
				echo("\n" . "Null User - Target Username");

				return NULL;
			}

			return $instagram->timeline->getUserFeed($user_to_like->getPk());
		}
		catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {
			echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());

			if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
				RedisRepository::saveBlacklistPk($user_to_like->getPk());
				echo("\n" . "Blacklisted: " . $user_to_like->getPk());
			}

			return NULL;
		}
		catch (\Exception $ex) {
			echo("\n" . "Exception: " . $ex->getMessage());

			return NULL;
		}
	}

	public static function getTargetHashtagFeed(Instagram $instagram, $hashtag)
	{
		$hashtag_feed = NULL;
		try {
			$rank_token = \InstagramAPI\Signatures::generateUUID(TRUE);

			$trimmed_hashtag = trim($hashtag->hashtag);
			$hash_pos        = strpos($trimmed_hashtag, '#');
			if ($hash_pos !== FALSE) {
				$trimmed_hashtag  = str_replace('#', '', $trimmed_hashtag);
				$hashtag->hashtag = $trimmed_hashtag;
				$hashtag->save();
			}

			$hashtag_feed = $instagram->hashtag->getFeed(trim($hashtag->hashtag), $rank_token);

			//            dump($hashtag_feed);
			return $hashtag_feed;
		}
		catch (NotFoundException $ex) {
			$hashtag->invalid = 1;
			$hashtag->save();

			return NULL;
		}
		catch (NetworkException $network_ex) {
			$ig_profile = InstagramProfile::where('insta_user_id', $instagram->account_id)->first();
			if ($ig_profile !== NULL) {
				$ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
				$ig_profile->save();
			}

			return NULL;
		}
	}

	public static function getHashtagFeed(Instagram $instagram, $hashtag)
	{
		$hashtag_feed = NULL;
		try {
			$rank_token      = \InstagramAPI\Signatures::generateUUID(TRUE);
			$trimmed_hashtag = trim($hashtag->hashtag);
			$hash_pos        = strpos($trimmed_hashtag, '#');
			if ($hash_pos !== FALSE) {
				$trimmed_hashtag  = str_replace('#', '', $trimmed_hashtag);
				$hashtag->hashtag = $trimmed_hashtag;
				$hashtag->save();
			}

			$hashtag_feed = $instagram->hashtag->getFeed($trimmed_hashtag, $rank_token);

			return $hashtag_feed;
		}
		catch (NotFoundException $ex) {
			dump($ex);

			return NULL;
		}
		catch (NetworkException $network_ex) {
			$ig_profile = InstagramProfile::where('insta_user_id', $instagram->account_id)->first();
			if ($ig_profile !== NULL) {
				$ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
			}
			dump($network_ex);

			return NULL;
		}
	}

	public static function getFollowersViaProfileId(Instagram $instagram, InstagramProfile $ig_profile, $target_username_id, $next_max_id)
	{
		try {
			$rank_token = \InstagramAPI\Signatures::generateUUID(TRUE);
			echo("\n[GET FOLLOWERS VIA PROFILE ID] UUID Generated: " . $rank_token);
			$user_follower_response = $instagram->people->getFollowers($target_username_id, $rank_token, NULL, $next_max_id);

			return $user_follower_response;
		}
		catch (NetworkException $network_ex) {
			$ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
			$ig_profile->save();

			//			InstagramHelper::verifyAndReassignProxy($ig_profile);

			return NULL;
		}
	}

	public static function validForInteraction($ig_profile)
	{
		if ($ig_profile->proxy == NULL) {
			echo("\n[" . $ig_profile->insta_username . "] does not have a proxy.\n");

			$ig_profile->challenge_required = 1;
			$ig_profile->save();

			return FALSE;
		}

		if (strpos($ig_profile->proxy, 'http') === 0) {

			echo("\n[" . $ig_profile->insta_username . "] is using the old proxy.\n");

			$ig_profile->proxy              = NULL;
			$ig_profile->challenge_required = 1;
			$ig_profile->save();

			return FALSE;
		}

		if ($ig_profile->challenge_required == 1) {
			echo("\n[" . $ig_profile->insta_username . "] requires verification.\n");

			return FALSE;
		}

		if ($ig_profile->challenge_required_phone == 1) {
			echo("\n[" . $ig_profile->insta_username . "] requires a phone verification.\n");

			return FALSE;
		}

		if ($ig_profile->checkpoint_required == 1) {
			echo("\n[" . $ig_profile->insta_username . "] has a checkpoint.\n");

			return FALSE;
		}

		if ($ig_profile->account_disabled == 1) {
			echo("\n[" . $ig_profile->insta_username . "] account has been disabled.\n");

			return FALSE;
		}

		if ($ig_profile->invalid_user == 1) {
			echo("\n[" . $ig_profile->insta_username . "] is a invalid instagram user.\n");

			return FALSE;
		}

		if ($ig_profile->incorrect_pw == 1) {
			echo("\n[" . $ig_profile->insta_username . "] is using an incorrect password.\n");

			return FALSE;
		}

		if ($ig_profile->ig_throttled == 1) {
			echo("\n[" . $ig_profile->insta_username . "] is being throttled at the moment.\n");

			return FALSE;
		}

		return TRUE;
	}

	public static function randomizeUseHashtags(Instagram $instagram, InstagramProfile $ig_profile, $targeted_hashtags, $targeted_usernames)
	{
		$use_hashtags = rand(0, 1);
		if ($use_hashtags == 1 && count($targeted_hashtags) == 0) {
			$use_hashtags = 0;
		} else if ($use_hashtags == 0 && count($targeted_usernames) == 0) {
			$use_hashtags = 1;
		}

		if (count($targeted_hashtags) == 0 && count($targeted_usernames) == 0) {
			$use_hashtags = 2;
		}

		echo "[Use Hashtags] Value: " . $use_hashtags . "\n";

		return $use_hashtags;
	}

	public static function refreshUserMedia(Instagram $instagram, InstagramProfile $ig_profile)
	{

	}

	public static function saveUsersProfileToRedis($users)
	{
		foreach ($users as $user) {
			Redis::hmset(
				"morfix:profile:pk:" . $user->getPk(), $user->asArray()
			);
		}
	}

	public static function getDatacenterProxyList()
	{
		return [
			"104.164.41.0:60000",
			"104.164.41.2:60000",
			"104.164.41.4:60000",
			"104.164.41.6:60000",
			"104.164.41.11:60000",
			"104.164.41.13:60000",
			"104.164.41.15:60000",
			"104.164.41.17:60000",
			"104.164.41.19:60000",
			"104.164.41.21:60000",
			"104.164.41.26:60000",
			"104.164.41.28:60000",
			"104.164.41.30:60000",
			"104.164.41.32:60000",
			"104.164.41.34:60000",
			"104.164.41.36:60000",
			"104.164.41.38:60000",
			"104.164.41.43:60000",
			"104.164.41.45:60000",
			"104.164.41.47:60000",
			"104.164.41.49:60000",
			"104.164.41.51:60000",
			"104.164.41.53:60000",
			"104.164.41.58:60000",
			"104.164.41.60:60000",
			"104.164.41.62:60000",
			"104.164.41.64:60000",
			"104.164.41.66:60000",
			"104.164.41.68:60000",
			"104.164.41.70:60000",
			"104.164.41.75:60000",
			"104.164.41.77:60000",
			"104.164.41.79:60000",
			"104.164.41.81:60000",
			"104.164.41.83:60000",
			"104.164.41.85:60000",
			"104.164.41.90:60000",
			"104.164.41.92:60000",
			"104.164.41.94:60000",
			"104.164.41.96:60000",
			"104.164.41.98:60000",
			"104.164.41.100:60000",
			"104.164.41.107:60000",
			"104.164.41.109:60000",
			"104.164.41.111:60000",
			"104.164.41.113:60000",
			"104.164.41.115:60000",
			"104.164.41.117:60000",
			"104.164.41.122:60000",
			"104.164.41.124:60000",
			"104.164.41.126:60000",
			"104.164.41.128:60000",
			"104.164.41.130:60000",
			"104.164.41.132:60000",
			"104.164.41.139:60000",
			"104.164.41.141:60000",
			"104.164.41.143:60000",
			"104.164.41.145:60000",
			"104.164.41.147:60000",
			"104.164.41.149:60000",
			"104.164.41.154:60000",
			"104.164.41.156:60000",
			"104.164.41.158:60000",
			"104.164.41.160:60000",
			"104.164.41.162:60000",
			"104.164.41.164:60000",
			"104.164.41.171:60000",
			"104.164.41.173:60000",
			"104.164.41.175:60000",
			"104.164.41.177:60000",
			"104.164.41.179:60000",
			"104.164.41.181:60000",
			"104.164.41.186:60000",
			"104.164.41.188:60000",
			"104.164.41.190:60000",
			"104.164.41.192:60000",
			"104.164.41.194:60000",
			"104.164.41.196:60000",
			"104.164.41.203:60000",
			"104.164.41.205:60000",
			"104.164.41.207:60000",
			"104.164.41.209:60000",
			"104.164.41.211:60000",
			"104.164.41.213:60000",
			"104.164.41.218:60000",
			"104.164.41.220:60000",
			"104.164.41.222:60000",
			"104.164.41.224:60000",
			"104.164.41.226:60000",
			"104.164.41.228:60000",
			"104.164.41.230:60000",
			"104.164.41.235:60000",
			"104.164.41.237:60000",
			"104.164.41.239:60000",
			"104.164.41.241:60000",
			"104.164.41.243:60000",
			"104.164.41.245:60000",
			"104.164.41.250:60000",
			"104.164.41.252:60000",
			"104.164.41.254:60000" ];
	}

}