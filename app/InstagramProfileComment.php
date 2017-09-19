<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileComment extends Model
{
    protected $primaryKey = 'comment_id'; 
    protected $table = "user_insta_profile_comment";
    protected $fillable = ['comment_id', 'insta_username', 'ig_profile_id', 'comment', 'general'];
    public $timestamps = false;
}
