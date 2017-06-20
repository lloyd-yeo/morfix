<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeActiveSubscription extends Model {

    protected $table = 'user_stripe_active_subscription';
    public $timestamps = false;
}
