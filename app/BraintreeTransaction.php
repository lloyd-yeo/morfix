<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BraintreeTransaction extends Model
{
	protected $table ='braintree_transactions';
	public $incrementing = false;
}
