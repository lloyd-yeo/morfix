<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BraintreeSubscription extends Model
{
    protected $table = 'braintree_subscriptions';
	protected $primaryKey = "subscription_id";
	public $incrementing = false;
}
