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
use App\PaymentLog;

class PaymentController extends Controller {

    public function index(Request $request) {
        return view('payment.index', [
            
        ]);
    }
    
    public function processCreditCardPayment(Request $request) {
        
    }
    
    public function processPaypalPayment(Request $request) {
        
    }
    
    public function upgrade(Request $request, $plan) {
        $response = array();

        $payment_log = new PaymentLog;
        $payment_log->email = Auth::user()->email;
        $payment_log->plan = $plan;
        $payment_log->source = "Upgrade Page";
        $payment_log->save();

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

        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $user = User::where('email', Auth::user()->email)->first();
        $stripe_id = $user->stripe_id;

        $token = $request->input('stripeToken');
        $customer = NULL;
        
        if ($stripe_id === NULL) {
            try {

                // Create the customer first for record purposes.
                $customer = \Stripe\Customer::create(array(
                            "email" => Auth::user()->email,
                            "source" => $token,
                            'plan' => $plan_id
                ));

                $user->stripe_id = $customer->id;
                $user->save();

                $stripe_details = new StripeDetail;
                $stripe_details->email = Auth::user()->email;
                $stripe_details->stripe_id = $customer->id;
                $stripe_details->save();
                
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                $body = $e->getJsonBody();
                $err = $body['error'];

                return response()->json([
                            "success" => false,
                            "status" => $e->getHttpStatus(),
                            "type" => $err['type'],
                            "code" => $err['code'],
                            "message" => $err['message']]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
                $payment_log->log = $e->getMessage();
                $payment_log->save();
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $payment_log->log = $e->getMessage();
                $payment_log->save();
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
                $payment_log->log = $e->getMessage();
                $payment_log->save();
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                $payment_log->log = $e->getMessage();
                $payment_log->save();
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
                $payment_log->log = $e->getMessage();
                $payment_log->save();
            }
        } else {

            try {
                \Stripe\Subscription::create(array(
                    "customer" => $stripe_id,
                    "source" => $token,
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
                $user->save();
                
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                $body = $e->getJsonBody();
                $err = $body['error'];

                return response()->json([
                            "success" => false,
                            "status" => $e->getHttpStatus(),
                            "type" => $err['type'],
                            "code" => $err['code'],
                            "message" => $err['message']]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                $body = $e->getJsonBody();
                $err = $body['error'];

                return response()->json([
                            "success" => false,
                            "status" => $e->getHttpStatus(),
                            "type" => $err['type'],
                            "code" => $err['code'],
                            "message" => $err['message']]);
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                $body = $e->getJsonBody();
                $err = $body['error'];

                return response()->json([
                            "success" => false,
                            "status" => $e->getHttpStatus(),
                            "type" => $err['type'],
                            "code" => $err['code'],
                            "message" => $err['message']]);
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                $body = $e->getJsonBody();
                $err = $body['error'];

                return response()->json([
                            "success" => false,
                            "status" => $e->getHttpStatus(),
                            "type" => $err['type'],
                            "code" => $err['code'],
                            "message" => $err['message']]);
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                $body = $e->getJsonBody();
                $err = $body['error'];

                return response()->json([
                            "success" => false,
                            "status" => $e->getHttpStatus(),
                            "type" => $err['type'],
                            "code" => $err['code'],
                            "message" => $err['message']]);
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
                $payment_log->log = $e->getMessage();
                $payment_log->save();
                return response()->json([
                            "success" => false,
                            "message" => $e->getMessage()]);
            }
        }
        return response()->json($response);
    }
}
