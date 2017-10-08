<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileMedia extends Model
{
    protected $table = "user_insta_profile_media";
    protected $primaryKey = "media_id";
    public $timestamps = false;
}
