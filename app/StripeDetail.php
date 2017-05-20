<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeDetail extends Model
{
    public $timestamps = false;
    protected $table = 'user_stripe_details';
    protected $connection = 'mysql_old';
}
