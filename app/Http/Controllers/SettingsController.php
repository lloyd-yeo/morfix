<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\StripeActiveSubscription;
use Stripe\Stripe as Stripe;

class SettingsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        //get recent subscription
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $subscriptions = array();

        if (Auth::user()->stripe_id === NULL) {
            $customer = \Stripe\Customer::create(array(
                        "email" => Auth::user()->email,
            ));

            $user = User::where('email', Auth::user()->email)->first();
            $user->stripe_id = $customer->id;
            $user->save();
        } else {
            $subscriptions_listings = \Stripe\Subscription::all(array('customer' => Auth::user()->stripe_id));
            $subscriptions = $subscriptions_listings->data;
            
            //Remove al active subscription
            $saved_subscriptions = StripeActiveSubscription::where('stripe_id', Auth::user()->stripe_id);
            foreach ($saved_subscriptions as $sub) {
                $sub->delete();
            }
            
            foreach ($subscriptions as $subscription) {
                $stripe_id = $subscription->customer;
                $items = $subscription->items->data;
                foreach ($items as $item) {
                    $plan = $item->plan;
                    $plan_id = $plan->id;

                    $active_subscription = new StripeActiveSubscription;
                    $active_subscription->stripe_id = $stripe_id;
                    $active_subscription->subscription_id = $plan_id;
                    $active_subscription->status = $subscription->status;
                    $active_subscription->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
                    $active_subscription->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
                    if ($active_subscription->save()) {
                    }
                }
            }
            
            
        }

        return view('settings.index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function cancelSubscription($sub_id) {
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $subscription = \Stripe\Subscription::retrieve($sub_id);
        $subscription->cancel(array('at_period_end' => true));
        return Response::json(array("success" => true, 'message' => "Your subscription has been cancelled."));
    }

    public function updateCreditCard(Request $request) {
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $stripeToken = $request->input('stripeToken');
        $user = Auth::user()->stripe_id;
        $cu = \Stripe\Customer::retrieve($user); // stored in your application
        $cu->source = $stripeToken; // obtained with Checkout
        $cu->save();

        return view('settings.index', [
            'updateCreditCardMessage' => "Your card has been updated",
        ]);
    }

}
