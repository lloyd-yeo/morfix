<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileComment extends Model
{
    protected $table = "user_insta_profile_comment";
    protected $connection = "mysql_old";
    public $timestamps = false;
}
