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
}