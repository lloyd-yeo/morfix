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
use Stripe\Stripe as Stripe;

class SettingsController extends Controller {

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
        $stripeToken = $request->input['stripeToken'];
        $user = Auth::user()->stripe_id;
        $cu = \Stripe\Customer::retrieve($user); // stored in your application
        $cu->source = $stripeToken; // obtained with Checkout
        $cu->save();


        return view('settings.index', [
            'updateCreditCardMessage' => "Your card has been updated",
        ]);
    }

}
