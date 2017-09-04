<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferrerIp extends Model
{
    public $timestamps = false;
    protected $table = 'referral_ip';
}
