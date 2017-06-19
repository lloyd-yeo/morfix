<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeDetail extends Model
{
    protected $table = 'user_stripe_details';
    protected $primaryKey = 'stripe_id';
    public $timestamps = false;
    public $incrementing = false;
}
