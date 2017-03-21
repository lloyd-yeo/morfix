<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model {
    
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'proxy';
    protected $table = 'proxies';
    
}
