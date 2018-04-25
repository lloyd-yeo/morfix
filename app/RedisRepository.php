<?php

namespace App;

use Log;
use Illuminate\Support\Facades\Redis;

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
	public static function saveUsersProfile($users)
	{
        foreach ($users->getUsers() as $user){
            Redis::hmset(
                "morfix:profile:pk:" . $user->getPk(), $user->asArray()
            );
        }
	}
	public static function saveUsernamePk($users)
	{
        foreach ($users->getUsers() as $user){
            Redis::hmset(
                "morfix:profile:username:" . $user->getUsername(),[
				'pk' => $user->getPk(),
			]
            );
        }
	}
	public static function saveUsernameFollowers($users)
	{
        foreach ($users->getUsers() as $user){
            Redis::sadd(
                "morfix:profile:" . $user->getPk() . ":followers", $user->getPk());
        }
	}

}