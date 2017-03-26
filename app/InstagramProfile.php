<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfile extends Model
{
    protected $table = "morfix_instagram_profiles";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'email', 'insta_user_id', 'insta_username', 'insta_pw', 'profile_pic_url', 'follower_count', 'profile_full_name'
    ];
}
