<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddProfileRequest extends Model
{
	protected $table = 'add_profile_requests';
	public $timestamps = false;
	protected $connection = 'mysql_add_profile_queues';
}
