<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileComment extends Model
{
    protected $primaryKey = 'comment_id'; 
    protected $table = "user_insta_profile_comment";
    protected $connection = "mysql_old";
    public $timestamps = false;
}
