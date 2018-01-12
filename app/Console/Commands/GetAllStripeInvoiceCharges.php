<?php

namespace App\Console\Commands;

use App\StripeCharge;
use App\StripeInvoice;
use Illuminate\Console\Command;
use Stripe\Stripe;

class GetAllStripeInvoiceCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:getinvoicecharges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all Stripe invoices & charges';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    \Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
	    $charges = \Stripe\Charge::all(array("limit" => 100));
	    foreach ($charges->autoPagingIterator() as $charge) {
	    	$stripe_charge = StripeCharge::where('stripe_id', $charge->customer)->where('charge_id', $charge->id)->first();
			if ($stripe_charge == NULL) {
				$stripe_charge = new StripeCharge;
			}
		    $stripe_charge->stripe_id = $charge->customer;
		    $stripe_charge->charge_id = $charge->id;
		    $stripe_charge->invoice_id = $charge->invoice;
		    $stripe_charge->charge_created = \Carbon\Carbon::createFromTimestamp($charge->created);
		    $stripe_charge->failure_code = $charge->failure_code;
		    $stripe_charge->failure_msg = $charge->failure_message;
		    $stripe_charge->paid = 0;
		    if ($charge->paid) {
			    $stripe_charge->paid = 1;
		    }
		    $stripe_charge->refunded = 0;
		    if ($charge->refunded) {
			    $stripe_charge->refunded = 1;
		    }
		    if ($stripe_charge->save()) {
		    	dump($stripe_charge);
		    }
	    }
    }
}
