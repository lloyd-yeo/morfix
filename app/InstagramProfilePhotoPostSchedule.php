<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfilePhotoPostSchedule extends Model {

    protected $table = 'user_insta_photo_post_schedule';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;
}
