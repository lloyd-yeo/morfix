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
        foreach ($user_follower_response->getUsers() as $user){
            Redis::hmset(
                "morfix:profile:pk:" . $user->getPk(), $user->asArray()
            );
        }
	}
	public static function saveUsernamePk($user_follower_response)
	{
        foreach ($user_follower_response->getUsers() as $user){
            Redis::set("morfix:profile:username:" . $user->getUsername(), $user->getPk());
        }
	}
	public static function saveUsernameFollowers($user_follower_response, $target_username_id)
	{
        foreach ($user_follower_response->getUsers() as $user){
            Redis::sadd("morfix:profile:" . $target_username_id . ":followers", $user->getPk());
        }
	}

	public static function saveProfileLikeCountSingle($profile_pk, $like_count) {
		Redis::set("morfix:profile:" . $profile_pk . ":likes", $like_count);
	}

	public static function saveProfileLikeCountMap($like_count_map) {

		Redis::pipeline(function ($pipe) use ($like_count_map) {
			foreach ($like_count_map as $profile_pk => $like_count) {
				$pipe->set("morfix:profile:" . $profile_pk . ":likes", $like_count);
			}
		});
	}

	public static function saveBlacklistPk($pk) {
		$bucket = intdiv($pk, 1000);
		Redis::hset("morfix:blacklist:" . $bucket, $pk, 1);
	}

	public static function checkBlacklistPk($pk) {
		$bucket = intdiv($pk, 1000);
		$pk_exists = Redis::hexists("morfix:blacklist:" . $bucket, $pk);
		if ($pk_exists == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public static function saveProfileLikedMedias($profile_pk, $media_pks) {

		$bucket = 1;
		Redis::pipeline(function ($pipe) use ($profile_pk, $media_pks, $bucket) {
			$i = 0;
			foreach ($media_pks as $media_pk => $media_url) {
				try {
					if ($i == 999) {
						$i = 0;
						$bucket = $bucket + 1;
					}
					$pipe->hset("morfix:likes:" . $profile_pk . ":" . "$bucket", $media_pk, $media_url);
					$i++;
				} catch (\Exception $ex) {
					echo("[ERROR] Parameters are: " . $media_pk);
					echo($ex->getMessage());
					continue;
				}
			}
		});
		Redis::set("morfix:likes:" . $profile_pk . ":bucket_id", $bucket);
	}

	public static function saveUserFollowersResponse($user_follower_response, $target_username_id)
	{
        self::saveUsersProfile($user_follower_response);
        self::saveUsernamePk($user_follower_response);
        self::saveUsernameFollowers($user_follower_response, $target_username_id);
	}


}