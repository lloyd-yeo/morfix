<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstagramProfileTargetHashtag extends Model {
    protected $table = 'user_insta_target_hashtag';
    protected $connection = 'mysql_old';
    public $timestamps = false;
}
