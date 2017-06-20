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
//        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
//        
//        $subscriptions = \Stripe\Subscription::all(array('limit'=>100));
//        
//        foreach ($subscriptions->autoPagingIterator() as $subscription) {
//            echo $subscription . "\n\n\n\n";
//            $stripe_id = $subscription->customer;
//            $items = $subscription->items->data;
//            foreach ($items as $item) {
//                $plan = $item->plan;
//                $plan_id = $plan->id;
//                
//                $active_subscription = new StripeActiveSubscription;
//                $active_subscription->stripe_id = $stripe_id;
//                $active_subscription->subscription_id = $plan_id;
//                $active_subscription->status = $subscription->status;
//                $active_subscription->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
//                $active_subscription->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
//                if ($active_subscription->save()) {
//                    echo $stripe_id . "\t" . $plan_id . "\n";
//                }
//            }
//        }
        
        $users = User::where('stripe_id', '>', '\'\'')->where('vip', false)->where('admin', false)->get();
        
        foreach ($users as $user) {
            $user_tier = 1;
            
            foreach ($user->stripeDetails() as $stripe_detail) {
                $stripe_id = $stripe_detail->stripe_id;
                echo $user->email . "\t" . $stripe_id . "\n";
                
                $user_active_subscriptions = StripeActiveSubscription::where('stripe_id', $stripe_id)->whereRaw('(active = \'active\' OR active=\'trialing\')')->get();
                foreach ($user_active_subscriptions as $active_sub) {
                    echo $user->email . "\t" . $active_sub->subscription_id . "\n";
                }
                
            }
        }
        
    }
}
