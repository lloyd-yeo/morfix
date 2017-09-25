<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalCharges extends Model
{
    
    protected $table = 'paypal_charges';
    public $timestamps = false;
    public $incrementing = true;
}
