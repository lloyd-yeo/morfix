<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileLikeLog extends Model
{
     #protected $table = "morfix_profile_like_logs";
     protected $table = "user_insta_profile_like_log";
     protected $primaryKey = "log_id";
     protected $connection = "mysql_old";
     
}
