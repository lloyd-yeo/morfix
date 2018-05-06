<?php

namespace App;

use Log;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class RedisRepository
{
	public static function saveUsers($users)
	{
		//		Redis::hmset(
		//			$payload->id(), [
		//				'status' => 'reserved',
		//				'payload' => $payload->value,
		//				'updated_at' => time(),
		//				'reserved_at' => time(),
		//			]
		//		);
	}

	public static function savePartialMedia($media_pk, $media_url)
	{
		Redis::hmset(
			'morfix:media:' . $media_pk, [
				'image_url' => $media_url,
			]
		);
	}

	public static function savePartialMediaOwner($owner_username, $media_pk)
	{
		Redis::zadd('morfix:profile:' . $owner_username . ":medias", Carbon::now()->timestamp, $media_pk);
	}

	public static function saveUserLikedMediaByUsername($liker_username, $media_pk, $liked_date)
	{
		Redis::zadd('morfix:profile:' . $liker_username . ":liked_medias", Carbon::parse($liked_date)->timestamp, $media_pk);
	}

	public static function saveUserLikedMediaByPk($liker_pk, $media_pk, $liked_date)
	{
		Redis::zadd('morfix:profile:' . $liker_pk . ":liked_medias", Carbon::parse($liked_date)->timestamp, $media_pk);
	}

	public static function saveUserLikedUsername($liker_pk, $liked_username)
	{
		Redis::lpush('morfix:profile:' . $liker_pk . ":liked_users", $liked_username);
	}

	public static function saveUsersProfile($user_follower_response)
	{
		foreach ($user_follower_response->getUsers() as $user) {
			Redis::hmset(
				"morfix:profile:pk:" . $user->getPk(), $user->asArray()
			);
		}
	}

	public static function saveUsernamePk($user_follower_response)
	{
		foreach ($user_follower_response->getUsers() as $user) {
			Redis::set("morfix:profile:username:" . $user->getUsername(), $user->getPk());
		}
	}

	public static function saveUsernameFollowers($user_follower_response, $target_username_id)
	{
		foreach ($user_follower_response->getUsers() as $user) {
			Redis::sadd("morfix:profile:" . $target_username_id . ":followers", $user->getPk());
		}
	}

	public static function saveProfileLikeCountSingle($profile_pk, $like_count)
	{
		Redis::set("morfix:profile:" . $profile_pk . ":likes", $like_count);
	}

	public static function saveProfileLikeCountMap($like_count_map)
	{

		Redis::pipeline(function ($pipe) use ($like_count_map) {
			foreach ($like_count_map as $profile_pk => $like_count) {
				$pipe->set("morfix:profile:" . $profile_pk . ":likes", $like_count);
			}
		});
	}

	public static function saveBlacklistPk($pk)
	{
		$bucket = intdiv($pk, 1000);
		Redis::hset("morfix:blacklist:" . $bucket, $pk, 1);
	}

	public static function checkBlacklistPk($pk)
	{
		$bucket    = intdiv($pk, 1000);
		$pk_exists = Redis::hexists("morfix:blacklist:" . $bucket, $pk);
		if ($pk_exists == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public static function saveProfileLikedMedias($profile_pk, $media_pks)
	{
		$bucket = 1;
		Redis::pipeline(function ($pipe) use ($profile_pk, $media_pks, &$bucket) {
			$i = 0;
			foreach ($media_pks as $media_pk => $media_url) {
				try {
					if ($i == 999) {
						$i      = 0;
						$bucket = $bucket + 1;
					}
					$pipe->hset("morfix:likes:" . $profile_pk . ":" . "$bucket", $media_pk, $media_url);
					$i++;
					echo("\n[HSET] " . "morfix:likes:" . $profile_pk . ":" . "$bucket");
				}
				catch (\Exception $ex) {
					echo("\n[ERROR] Parameters are: " . $media_pk);
					echo($ex->getMessage());
					continue;
				}
			}
		});
		Redis::set("morfix:likes:" . $profile_pk . ":bucket_id", $bucket);
		echo("\nmorfix:likes:" . $profile_pk . ":bucket_id to " . $bucket);
	}

	public static function saveNewProfileLikeLog($profile_pk, $media_pk, $media_url, $timestamp)
	{
		$bucket       = Redis::get("morfix:likes:" . $profile_pk . ":bucket_id");
		$bucket_items = Redis::hlen("morfix:likes:" . $profile_pk . ":" . "$bucket");
		if ($bucket_items < 999) {
			//Save the log itself.
			Redis::hset("morfix:likes:" . $profile_pk . ":" . "$bucket", $media_pk, $media_url . "," . $timestamp);
			//Increment the user's total like count
			Redis::incrby("morfix:profile:" . $profile_pk . ":likes", 1);
			//Increment the user's daily like count
			Redis::incrby("morfix:profile:" . $profile_pk . ":daily_likes", 1);
		}
	}

	public static function checkDuplicateLikeLog($profile_pk, $media_pk)
	{
		$bucket = Redis::get("morfix:likes:" . $profile_pk . ":bucket_id");
		for ($i = 1; $i <= $bucket; $i++) {
			$value = Redis::hexists("morfix:likes:" . $profile_pk . ":" . "$bucket", $media_pk);
			if ($value === 1) {
				//Duplicate
				return TRUE;
			} else {
				//Not duplicate
				return FALSE;
			}
		}
	}

	public static function saveProfileLikedUsers($profile_pk, $liked_user_pk)
	{
		$bucket_exists       = Redis::exists("morfix:liked_users:" . $profile_pk . ":bucket_id");
		$bucket = 1;
		if ($bucket_exists == 0) {
			Redis::set("morfix:liked_users:" . $profile_pk . ":bucket_id", $bucket);
		} else {
			$bucket = Redis::get("morfix:liked_users:" . $profile_pk . ":bucket_id");
		}

		Redis::hset("morfix:likes:" . $profile_pk . ":" . "$bucket", $liked_user_pk, 1);
	}

	public static function checkDuplicateLikedUsers($profile_pk, $liked_user_pk) {
		$bucket = Redis::get("morfix:liked_users:" . $profile_pk . ":bucket_id");
		for ($i = 1; $i <= $bucket; $i++) {
			$value = Redis::hexists("morfix:liked_users:" . $profile_pk . ":" . "$bucket", $liked_user_pk);
			if ($value === 1) {
				//Duplicate
				return TRUE;
			} else {
				//Not duplicate
				return FALSE;
			}
		}
	}

	public static function getProfileTotalLikeCount($profile_pk) {
		$like_count = Redis::get("morfix:profile:" . $profile_pk . ":likes");
		return $like_count;
	}

	public static function getProfileDailyLikeCount($profile_pk) {
		$like_count = Redis::get("morfix:profile:" . $profile_pk . ":daily_likes");
		return $like_count;
	}

	public static function incrementProfileTotalLikeCount($profile_pk) {
		$bucket = Redis::get("morfix:likes:" . $profile_pk . ":bucket_id");
		for ($i = 1; $i <= $bucket; $i++) {
			$bucket_items = Redis::hlen("morfix:likes:" . $profile_pk . ":" . "$bucket");
			Redis::incrby("morfix:profile:" . $profile_pk . ":likes", $bucket_items);
		}
	}

	public static function incrementProfilesTotalLikeCount($profile_pks) {
		Redis::pipeline(function ($pipe) use ($profile_pks) {
			foreach ($profile_pks as $profile_pk) {
				try {
					$bucket = Redis::get("morfix:likes:" . $profile_pk . ":bucket_id");
					for ($i = 1; $i <= $bucket; $i++) {
						$bucket_items = Redis::hlen("morfix:likes:" . $profile_pk . ":" . "$bucket");
						$pipe->incrby("morfix:profile:" . $profile_pk . ":likes", $bucket_items);
					}
				}
				catch (\Exception $ex) {
					echo("\n[ERROR] Parameters are: " . $profile_pk);
					echo($ex->getMessage());
					continue;
				}
			}
		});
	}

	public static function saveUserFollowersResponse($user_follower_response, $target_username_id)
	{
		self::saveUsersProfile($user_follower_response);
		self::saveUsernamePk($user_follower_response);
		self::saveUsernameFollowers($user_follower_response, $target_username_id);
	}
}