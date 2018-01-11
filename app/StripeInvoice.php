<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeInvoice extends Model
{
    protected $table = "user_stripe_invoice";
	protected $primaryKey = 'invoice_id';
    public $timestamps = false;
	public $incrementing = false;
}
