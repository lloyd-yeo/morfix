<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileCommentLog extends Model
{
    #protected $table = "morfix_profile_comment_logs";
    protected $table = "user_insta_profile_comment_log";
    protected $primaryKey = "log_id";
    public $timestamps = false;
}
