<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreateInstagramProfileLog extends Model
{
    protected $primaryKey = 'log_id';
    protected $table = 'create_insta_profile_log';
    protected $connection = 'mysql_old';
}
