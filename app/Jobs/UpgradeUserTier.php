<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use AWeberAPI;
use App\User;
use App\Mail\NewPassword;
use App\Mail\ProCongrats;
use App\Mail\BusinessCongrats;
use App\StripeDetail;

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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Upgrading customer: " . $this->email . " with subscription: " . $this->subscription_id . "\n";
        
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
//        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
        
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
        if ($plan->id == "0137") {
            $user = User::where('email', $this->email)->first();
            $user->tier = $user->tier + 1;
            $user->partition = 3;
            if ($user->save()) {
            }
        } else if ($plan->id == "MX370") {
            
            $user = User::where('email', $this->email)->first();
            $user->partition = 3;
            if ($user->tier % 10 == 2) {
                $user->tier = $user->tier + 1;
            } else {
                $user->tier = $user->tier + 2;
            }
            if ($user->save()) {
            }
            
        } else if ($plan->id == "0297") {
            $user = User::where('email', $this->email)->first();
            $user->tier = $user->tier + 10;
            $user->num_acct = 6;
            $user->partition = 3;
            if ($user->save()) {
                //Pro
                Mail::to($user->email)->send(new BusinessCongrats($user));
            }
        } else if ($plan->id == "MX297") {
            $user = User::where('email', $this->email)->first();
            $user->tier = $user->tier + 1;
            $user->partition = 3;
            if ($user->save()) {
                //Pro
                Mail::to($user->email)->send(new ProCongrats($user));
            }
            
            //change subscription prorate to false;
            $subscription->prorate = false;
            $new_date = $subscription->current_period_start + 34128000;
            $subscription->trial_end = $new_date;
            $subscription = $subscription->save();
        }
    }
}
