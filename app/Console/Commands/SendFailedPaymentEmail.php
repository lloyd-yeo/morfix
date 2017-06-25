<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PaymentFailed;
use App\StripeDetail;
use Illuminate\Support\Facades\Mail;

class SendFailedPaymentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:failedpayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Failed Payment Email';

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
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        
        $subscriptions = \Stripe\Subscription::all(array('limit'=>100, 'status'=>'past_due'));
        
        $unpaid_customers = array();
        
        foreach ($subscriptions->autoPagingIterator() as $subscription) {
            #echo $subscription . "\n\n\n\n";
            $stripe_id = $subscription->customer;
            $items = $subscription->items->data;
            
            foreach ($items as $item) {
                $plan = $item->plan;
                $plan_id = $plan->id;
                
//                $active_subscription = new StripeActiveSubscription;
//                $active_subscription->stripe_id = $stripe_id;
//                $active_subscription->subscription_id = $plan_id;
//                $active_subscription->status = $subscription->status;
//                $active_subscription->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
//                $active_subscription->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
//                if ($active_subscription->save()) {
                    echo $stripe_id . "\t" . $plan_id . "\t" . "\t" . $subscription->id . "\t" . $subscription->status . "\t" . \Carbon\Carbon::createFromTimestamp($item->created) . "\n";
//                }
                $unpaid_customers[] = $stripe_id;
            }
            
//            $sub_invoices = \Stripe\Invoice::all(array('limit'=>100, "subscription" => $subscription->id));
//            foreach ($sub_invoices->autoPagingIterator() as $invoice) {
//                if ($invoice->paid == false) {
//                    $invoice_paid = 'false';
//                    echo $stripe_id . "\t" . $invoice->id . "\t" . $invoice_paid . "\n";
//                }
//            }
        }
        
        $unpaid_customers = array_unique($unpaid_customers); 
        foreach ($unpaid_customers as $unpaid_customer) {
            $stripe_details = StripeDetail::where('stripe_id', $unpaid_customer)->first();
            echo $unpaid_customer . "\t". $stripe_details->email . "\n";
            Mail::to($stripe_details->email)->send(new PaymentFailed());
        }
//        
    }
}
