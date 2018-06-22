<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StripeCharge;
use App\StripeInvoice;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Invoice;
use Carbon\Carbon;

class StripeGetAllInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:getinvoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get & update all the Stripe Invoices';

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
	    Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");

	    $invoices = Invoice::all(array("limit" => 100));
	    foreach ($invoices->autoPagingIterator() as $invoice) {
		    foreach ($invoice->lines->data as $line) {
			    $subscription_id = $line->plan->id;
			    $stripe_invoice = StripeInvoice::where('invoice_id', $invoice->id)
			                                   ->where('stripe_id', $invoice->customer)
			                                   ->where('subscription_id', $subscription_id)
			                                   ->first();
			    if ($stripe_invoice == NULL) {
				    $stripe_invoice = new StripeInvoice;
			    }

			    $stripe_invoice->stripe_id = $invoice->customer;
			    $stripe_invoice->invoice_id = $invoice->id;
			    $stripe_invoice->charge_id = $invoice->charge;
			    $stripe_invoice->invoice_date = \Carbon\Carbon::createFromTimestamp($invoice->date);

			    $stripe_invoice->subscription_id = $subscription_id;
			    $period = $line->period;
			    $period_start = \Carbon\Carbon::createFromTimestamp($period->start);
			    $period_end = \Carbon\Carbon::createFromTimestamp($period->end);
			    $stripe_invoice->start_date = $period_start;
			    $stripe_invoice->expiry_date = $period_end;

			    if ($invoice->paid) {
				    $stripe_invoice->paid = 1;
			    } else {
				    $stripe_invoice->paid = 0;
			    }

			    if ($stripe_invoice->save()) {
				    dump($stripe_invoice);
			    }
		    }
	    }

	    $charges = Charge::all(array("limit" => 100));
	    foreach ($charges->autoPagingIterator() as $charge) {
		    $stripe_charge = StripeCharge::where('stripe_id', $charge->customer)
		                                 ->where('charge_id', $charge->id)
		                                 ->first();
		    if ($stripe_charge == NULL) {
			    $stripe_charge = new StripeCharge;
		    }

		    if ($charge->customer == NULL) {
			    continue;
		    }

		    $stripe_charge->stripe_id = $charge->customer;
		    $stripe_charge->charge_id = $charge->id;
		    $stripe_charge->invoice_id = $charge->invoice;
		    $stripe_charge->charge_created = Carbon::createFromTimestamp($charge->created);
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
