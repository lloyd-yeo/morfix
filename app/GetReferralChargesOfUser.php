<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GetReferralChargesOfUser extends Model
{
    public function scopeFromView($query)
    {
        return $query->from('get_referral_charges_of_user');
    }
}
