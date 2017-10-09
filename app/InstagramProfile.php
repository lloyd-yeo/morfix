<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class InstagramProfile extends Model
{
    protected $primaryKey = 'id'; 
    protected $table = "user_insta_profile";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'email', 'insta_user_id', 'insta_username', 'insta_pw', 'profile_pic_url', 'follower_count', 'profile_full_name'
    ];
    
    public function owner() {
        return User::find($this->user_id);
    }
}
