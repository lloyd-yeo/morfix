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

class SettingsController extends Controller
{
    public function index() {
        //get recent subscription
        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
        
        if (Auth::user()->stripe_id === NULL) {
            $customer = \Stripe\Customer::create(array(
                "email" => Auth::user()->email,
            ));
            
            $user = User::where('email', Auth::user()->email)->first();
            $user->stripe_id = $customer->id;
            $user->save();
            
        } else {
            #\Stripe\Customer::retrieve(Auth::user()->stripe_id);
            #$user = User::where('email', Auth::user()->email)->first();
            $subscriptions_listings = \Stripe\Subscription::all(array('customer'=>Auth::user()->stripe_id));
            $subscriptions = $subscriptions_listings->data;
            foreach ($subscriptions as $subscription) {
                $plan_id = $subscription->plan->id;
                echo $plan_id . "<br/>";
            }
        }
    }
}
