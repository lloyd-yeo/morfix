<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeCharge extends Model
{
    protected $table ='user_stripe_charges';
    protected $primaryKey = 'charge_id'; 
    public $timestamps = false;
}
