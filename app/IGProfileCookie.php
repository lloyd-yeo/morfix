<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IGProfileCookie extends Model
{
    protected $table = "instagram_sessions";
    protected $connection = "mysql_igsession";
    public $timestamps = false;
    public $incrementing = false;
}
