<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GetReferrerForUser extends Model
{
        public function scopeFromView($query)
    {
        return $query->from('get_referral_for_user');
    }
}
