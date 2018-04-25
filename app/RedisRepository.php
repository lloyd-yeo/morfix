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
}