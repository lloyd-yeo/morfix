<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlacklistedUsername extends Model
{
    protected $table = 'blacklisted_username';
    protected $primaryKey = "username";
    protected $connection = "mysql_old";
    public $timestamps = false;
    public $incrementing = false;
}
