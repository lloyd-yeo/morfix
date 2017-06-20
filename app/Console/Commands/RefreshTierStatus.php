<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\InstagramProfilePhotoPostSchedule;
use App\StripeDetail;
use Stripe\Stripe as Stripe;
use App\StripeActiveSubscription;

class RefreshTierStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:tier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh tier status of users.';

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
//        $users = User::where('stripe_id', '>', '\'\'')->get();
//        foreach ($users as $user) {
//            
//        }
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        
        $subscriptions = \Stripe\Subscription::all(array('limit'=>100));
        
        foreach ($subscriptions->autoPagingIterator() as $subscription) {
            $plan = $subscription->plan;
            $stripe_id = $subscription->customer;
            echo $plan->id . " " . $stripe_id . "\n";
            
            $active_subscription = new StripeActiveSubscription;
            $active_subscription->stripe_id = $stripe_id;
            $active_subscription->subscription_id = $plan;
            $active_subscription->status = $subscription->status;
            $active_subscription->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
            $active_subscription->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
            $active_subscription->save();
        }
    }
}
