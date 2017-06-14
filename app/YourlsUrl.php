<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YourlsUrl extends Model {
    protected $table = 'yourls_url';
    public $timestamps = false;
    protected $primaryKey = "keyword";
}
