<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IGProfileCookie extends Model
{
    protected $table = "instagram_sessions";
    public $timestamps = false;
    public $incrementing = false;
}
