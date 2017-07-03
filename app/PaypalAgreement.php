<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalAgreement extends Model
{
    protected $table = 'user_paypal_agreements';
    public $timestamps = false;
}
