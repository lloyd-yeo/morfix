<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use AWeberAPI;
use App\User;
use App\Mail\NewPassword;
use App\StripeDetail;
use Carbon\Carbon;

class UpgradeUserTier implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;
    
    protected $email;
    protected $subscription_id;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $subscription_id)
    {
        $this->email = $email;
        $this->subscription_id = $subscription_id;
//        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscription = \Stripe\Subscription::retrieve($this->subscription_id);
        $stripe_customer_id = $subscription->customer;
        
        //Save the stripe details first if there's no records of it
        if (StripeDetail::where('stripe_id', $stripe_customer_id)->count() == 0) {
            $stripe_details = new StripeDetail;
            $stripe_details->stripe_id = $stripe_customer_id;
            $stripe_details->email = $this->email;
            $stripe_details->save();
        }
        
        //After saving, do the necessary upgrades
        $plan = $subscription->items->data[0]->plan;
        if ($plan->id == "0297") {
            $user = User::where('email', $this->email)->first();
            $user->tier = $user->tier + 10;
            $user->num_acct = 6;
            $user->save();
            
        } else if ($plan->id == "MX297") {
            
            $user = User::where('email', $this->email)->first();
            $user->tier = $user->tier + 1;
            $user->save();
            
            //change subscription prorate to false;
            $subscription->prorate = false;
            $new_date = $subscription->current_period_start + 34128000;
            $subscription->trial_end = $new_date;
            $subscription = $subscription->save();
            
        }
    }
}
