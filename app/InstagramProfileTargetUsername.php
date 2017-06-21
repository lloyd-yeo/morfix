<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileTargetUsername extends Model {

    protected $table = 'user_insta_target_username';
    protected $primaryKey = 'target_id'; 
    public $timestamps = false;
}
