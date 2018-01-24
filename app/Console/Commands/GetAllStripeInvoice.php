<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StripeInvoice;


class GetAllStripeInvoice extends Command
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
    protected $description = 'Get & Update all Stripe Invoices';

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
	    $invoices = \Stripe\Invoice::all(array("limit" => 100));
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
    }
}
