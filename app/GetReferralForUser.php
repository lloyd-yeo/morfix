<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GetReferralForUser extends Model
{
    public function scopeFromView($query)
    {
        return $query->from('get_referral_for_user');
    }
}
