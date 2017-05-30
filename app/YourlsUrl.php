<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YourlsUrl extends Model {

    protected $table = 'yourls_url';
    protected $connection = 'mysql_old';
    public $timestamps = false;
}
