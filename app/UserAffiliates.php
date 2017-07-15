<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAffiliates extends Model {

    protected $table = 'user_affiliate';
    protected $primaryKey = 'affiliate_id'; 
    public $timestamps = false;
}
