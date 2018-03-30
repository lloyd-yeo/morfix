<?php

namespace App\Console\Commands;

use App\StripeDetail;
use App\User;
use Illuminate\Console\Command;
use App\StripeActiveSubscription;

class RefreshStripeSubscription extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:refreshstripesubscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        \Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
        $subscriptions = \Stripe\Subscription::all(array('limit' => 100, 'status' => 'all'));

        //Update our DB with all the active Stripe subscription
        foreach ($subscriptions->autoPagingIterator() as $subscription) {
            $active_sub = new StripeActiveSubscription;
            $active_sub->stripe_id = $subscription->customer;
            if ($subscription->plan !== NULL) {
                $active_sub->subscription_id = $subscription->plan->id;
                $active_sub->status = $subscription->status;
                $active_sub->created_at = \Carbon\Carbon::createFromTimestamp($subscription->created);
                $active_sub->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
                $active_sub->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
                $active_sub->stripe_subscription_id = $subscription->id;
                if ($active_sub->save()) {
                    echo $subscription->id . " [" . $subscription->status . "]\t" . $subscription->plan->id . "\n";
                }
            }
        }

	    //Get all canceled subscriptions
        $canceled_subscriptions = StripeActiveSubscription::where('status', 'canceled')->get();
		foreach ($canceled_subscriptions as $canceled_subscription) {
			$stripe_id = $canceled_subscription->stripe_id;
			$user_stripe_detail = StripeDetail::where('stripe_id', $stripe_id)->first();
			if ($user_stripe_detail != NULL) {
				$user = User::where('email', $user_stripe_detail->email)->first();
				if ($user != NULL) {
					if ($user->paypal = 0 && $user->braintree_id == NULL) {
						$user->tier = 1;
						$user->save();
					}
				}
			}
		}

	    //Get all unpaid subscriptions
	    $unpaid_subscriptions = StripeActiveSubscription::where('status', 'unpaid')->get();
	    foreach ($unpaid_subscriptions as $canceled_subscription) {
		    $stripe_id = $canceled_subscription->stripe_id;
		    $user_stripe_detail = StripeDetail::where('stripe_id', $stripe_id)->first();
		    if ($user_stripe_detail != NULL) {
			    $user = User::where('email', $user_stripe_detail->email)->first();
			    if ($user != NULL) {
				    if ($user->paypal = 0 && $user->braintree_id == NULL) {
					    $user->tier = 1;
					    $user->save();
				    }
			    }
		    }
	    }

	    //Get all past_due subscriptions
	    $past_due_subscriptions = StripeActiveSubscription::where('status', 'past_due')->get();
	    foreach ($past_due_subscriptions as $canceled_subscription) {
		    $stripe_id = $canceled_subscription->stripe_id;
		    $user_stripe_detail = StripeDetail::where('stripe_id', $stripe_id)->first();
		    if ($user_stripe_detail != NULL) {
			    $user = User::where('email', $user_stripe_detail->email)->first();
			    if ($user != NULL) {
				    if ($user->paypal = 0 && $user->braintree_id == NULL) {
					    $user->tier = 1;
					    $user->save();
				    }
			    }
		    }
	    }

	    //Get all active subscriptions
	    $active_subscriptions = StripeActiveSubscription::where('status', 'active')->get();
	    foreach ($active_subscriptions as $active_subscription) {
		    $stripe_id = $active_subscription->stripe_id;
		    $user_stripe_detail = StripeDetail::where('stripe_id', $stripe_id)->first();
		    if ($user_stripe_detail != NULL) {
			    $user = User::where('email', $user_stripe_detail->email)->first();
			    if ($user != NULL) {
//				    if ($user->paypal = 0 && $user->braintree_id == NULL) {
					    $user->tier = 1;
					    $user->save();
//				    }
			    }
		    }
	    }

	    foreach ($active_subscriptions as $active_subscription) {
		    $stripe_id = $active_subscription->stripe_id;
		    $user_stripe_detail = StripeDetail::where('stripe_id', $stripe_id)->first();
		    if ($user_stripe_detail != NULL) {
			    $user = User::where('email', $user_stripe_detail->email)->first();
			    if ($user != NULL) {
			    	switch ($active_subscription->subscription_id) {
					    case '0137':
						    $user->tier = $user->tier + 1;
						    break;
					    case '0167':
						    $user->tier = 12;
						    break;
					    case '0197':
						    $user->tier = 12;
						    break;
					    case '0297':
						    $user->tier = $user->tier + 10;
						    break;
					    case 'MX370':
						    $user->tier = $user->tier + 2;
						    break;
					    case 'MX970':
						    $user->tier = $user->tier + 20;
						    break;
					    case 'MX297':
						    $user->tier = $user->tier + 2;
						    break;
					    case '0247':
						    break;
					    default:
						    break;
				    }
			    }
		    }
	    }
    }

}