<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfilePhotoPostSchedule extends Model {

    protected $table = 'user_insta_photo_post_schedule';
    public $timestamps = false;
    protected $connection = 'mysql_old';
}
