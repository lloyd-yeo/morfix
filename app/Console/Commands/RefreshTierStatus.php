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
        
        $users = User::where('tier', '>=', '2')
                     ->where('vip', false)
                     ->where('admin', false)->get();
        
        $num_active_paying_user = 0;
        
        foreach ($users as $user) {

            $user_tier = 1;

            if ($user->braintree_id != NULL) {

            } else {
            	if ($user->stripeDetails()->count() > 0) {
		            //Stripe User
		            foreach ($user->stripeDetails() as $stripe_detail) {
			            $stripe_id = $stripe_detail->stripe_id;

			            $user_active_subscriptions = StripeActiveSubscription::where('stripe_id', $stripe_id)
			                                                                 ->whereRaw('(status = \'active\' OR status=\'trialing\')')->get();
			            foreach ($user_active_subscriptions as $active_sub) {

				            $plan = $active_sub->subscription_id;

				            if ($plan == "0137") {
					            $user_tier = $user_tier + 1;
				            } else if ($plan == "0297") {
					            $user_tier = $user_tier + 10;
				            } else if ($plan == "MX370") {
					            $user_tier = $user_tier + 2;
				            } else if ($plan == "MX297") {
					            $user_tier = $user_tier + 2;
				            } else if ($plan == "MX970") {
					            $user_tier = $user_tier + 20;
				            } else if ($plan == "0167") {
					            $user_tier = $user_tier + 11;
				            } else if ($plan == "0197") {
					            $user_tier = $user_tier + 11;
				            }
			            }
		            }
	            } else {

	            }
            }

            $user->tier = $user_tier;

            if ($user_tier > 1) {
	            echo $user->email . " [$user_tier] saved!\n";
//                if ($user->save()) {
//                    $num_active_paying_user++;
//                    echo $user->email . " [$user_tier] saved!\n";
//                } else {
//                    echo $user->email . " [$user_tier] failed to save!\n";
//                }
            }

        }
        
        echo "Total number of paying users: $num_active_paying_user";
    }
}
