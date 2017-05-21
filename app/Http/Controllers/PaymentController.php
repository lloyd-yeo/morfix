<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\DefaultImageGallery;
use App\UserImages;
use App\InstagramProfilePhotoPostSchedule;
use App\StripeDetail;

class PaymentController extends Controller
{
    function upgrade(Request $request, $plan) {
        $response = array();
        
        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
        $user = User::where('email', Auth::user()->email)->first();
        $stripe_id = $user->stripe_id;
        
        $token = $request->input('stripeToken');
        
        if ($stripe_id === NULL) {
            try {
                // Create the customer first for record purposes.
                $customer = \Stripe\Customer::create(array(
                    "email" => Auth::user()->email,
                ));
                
                // Add the credit card as default payment source for customer
                $customer->source = $token;
                $customer->save();
                $response['customer_created'] = true;
                
            } catch(\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $body = $e->getJsonBody();
                $err  = $body['error'];
                
                return response()->json([
                    "success" => false, 
                    "status" => $e->getHttpStatus(), 
                    "type" => $err['type'], 
                    "code" => $err['code'], 
                    "message" => $err['message']]);
                
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
            }
        }
        
        $plan_id = "0137";
        if ($plan == "Premium") {
            $plan_id = "0137";
        } else if ($plan == "Pro") {
            $plan_id = "MX370";
        } else if ($plan == "Business") {
            $plan_id = "0297";
        } else if ($plan == "Mastermind") {
            $plan_id = "MX970";
        }
        
        $response['plan'] = $plan;
        
        try {
            \Stripe\Subscription::create(array(
                "customer" => $customer->id,
                "plan" => $plan_id,
            ));
            
            $response['subscription_success'] = true;
            
            if ($plan == "Premium") {
               $user->tier = $user->tier + 1;
            } else if ($plan == "Pro") {
               $user->tier = $user->tier + 2;
            } else if ($plan == "Business") {
                $user->tier = $user->tier + 10;
                if ($user->tier < 10) {
                    $user->num_acct = $user->num_acct + 5;
                }
            } else if ($plan == "Mastermind") {
                $user->tier = $user->tier + 20;
                if ($user->tier < 10) {
                    $user->num_acct = $user->num_acct + 5;
                }
            }
            
        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];

            return response()->json([
                "success" => false, 
                "status" => $e->getHttpStatus(), 
                "type" => $err['type'], 
                "code" => $err['code'], 
                "message" => $err['message']]);

        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
        }
        
        return response()->json($response);
    }
}
