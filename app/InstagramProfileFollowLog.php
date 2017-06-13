<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileFollowLog extends Model
{
    protected $table = "user_insta_profile_follow_log";
    protected $primaryKey = "log_id";
    public $timestamps = false;
}
