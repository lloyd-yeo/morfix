<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use App\User;
use App\MorfixPlan;
use App\PaypalAgreement;
use App\StripeDetail;
use App\PaymentLog;
use App\UserAffiliates;
use Response;
use AWeberAPI;
use \DateTimeZone;

class PaymentController extends Controller {

    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;
    
    // Create a new instance with our paypal credentials
    public function __construct() {
        // Detect if we are running in live mode or sandbox
        if (config('paypal.settings.mode') == 'live') {
            $this->client_id = config('paypal.live_client_id');
            $this->secret = config('paypal.live_secret');
        } else {
            $this->client_id = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
        }

        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));
    }
    
    public function index(Request $request) {
        return view('payment.index', [
        ]);
    }
    
    public function processCreditCardPayment(Request $request) {
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $stripeToken = $request->input('stripeToken');
        
        $email = $request->input('email');
        $password = $request->input('pw');
        $name = $request->input('name');
        $referrer = $request->cookie('referrer');
        
        $user_tier = 1;
        $verification_token = bin2hex(random_bytes(18));
        $plan_id = "0137";
        
        if ($request->plan == 1) {
            $plan_id = "0137";
            $user_tier = 2;
        } else {
            $plan_id = "MX370";
            $user_tier = 3;
        }

        try {
            
            $customer = \Stripe\Customer::create(array(
                "source" => $stripeToken,
                "plan" => $plan_id,
                "email" => $email)
            );
            
            $customer_id = $customer->id;
            
            $user = new User;
            $user->email = $email;
            $user->password = $password;
            $user->num_acct = 1;
            $user->active = 1;
            $user->verification_token = $verification_token;
            $user->user_tier = $user_tier;
            $user->name = $name;
            $user->stripe_id = $customer_id;
            $user->save();
            
            $stripe_detail = new StripeDetail;
            $stripe_detail->email = $email;
            $stripe_detail->stripe_id = $customer_id;
            $stripe_detail->save();
            
            $user_affiliate = new UserAffiliates;
            $user_affiliate->referrer = $referrer;
            $user_affiliate->referred = $user->user_id;
            $user_affiliate->save();
            
            $consumerKey = "AkAxBcK3kI1q0yEfgw4R4c77";
            $consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

            $aweber = new AWeberAPI($consumerKey, $consumerSecret);
            $account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");
            
            foreach ($account->lists as $offset => $list) {

                $list_id = $list->id;

                if ($list_id != 4485376 OR $list_id != 4631962) {
                    continue;
                }

                # create a subscriber
                $params = array(
                    'email' => $email,
                    'name' => $name,
                    'ip_address' => $request->ip(),
                    'ad_tracking' => 'morfix_registration',
                    'last_followup_message_number_sent' => 1,
                    'misc_notes' => 'MorifX Registration Page'
                );

                try {
                    $subscribers = $list->subscribers;
                    $new_subscriber = $subscribers->create($params);
                } catch (Exception $ex) {
                    $error_msg = $ex->getMessage();
                }
            }
            
            Auth::login($user);
            return redirect()->action('HomeController@index');
            
        } catch (\Exception $ex) {
            $success = false;
            $error_msg = $ex->getMessage();
            echo $error_msg;
        }
    }
    
    public function processPaypalPayment(Request $request) {
        
        $email = $request->input('email');
        $password = $request->input('pw');
        $name = $request->input('name');
        $referrer = $request->cookie('referrer');
        $verification_token = bin2hex(random_bytes(18));
        
        $user = new User;
        $user->email = $email;
        $user->password = $password;
        $user->num_acct = 1;
        $user->active = 1;
        $user->verification_token = $verification_token;
        $user->user_tier = 1;
        $user->name = $name;
        $user->paypal = 1;
        $user->save();
        
        $user_affiliate = new UserAffiliates;
        $user_affiliate->referrer = $referrer;
        $user_affiliate->referred = $user->user_id;
        $user_affiliate->save();
        
        Auth::login($user);

        $consumerKey = "AkAxBcK3kI1q0yEfgw4R4c77";
        $consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

        $aweber = new AWeberAPI($consumerKey, $consumerSecret);
        $account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

        foreach ($account->lists as $offset => $list) {

            $list_id = $list->id;

            if ($list_id != 4485376 OR $list_id != 4631962) {
                continue;
            }

            # create a subscriber
            $params = array(
                'email' => $email,
                'name' => $name,
                'ip_address' => $request->ip(),
                'ad_tracking' => 'morfix_registration',
                'last_followup_message_number_sent' => 1,
                'misc_notes' => 'MorifX Registration Page'
            );

            try {
                $subscribers = $list->subscribers;
                $new_subscriber = $subscribers->create($params);
            } catch (Exception $ex) {
                $error_msg = $ex->getMessage();
            }
        }
        
        $paypal_plan_id = MorfixPlan::where('name', 'Premium New')->first()->paypal_id;
        
        if ($request->plan == 2) { //Pro
            $paypal_plan_id = MorfixPlan::where('name', 'Pro New')->first()->paypal_id;
        }
        
        // Instantiate Plan
        $plan = new Plan();
        $plan->setId($paypal_plan_id);

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Morfix Monthly Premium Subscription (37/month)')
                ->setDescription('This subscription is for the Premium package of Morfix, amounting to $37USD per month.')
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());
        
        if ($request->plan == 2) { //Pro
            $agreement->setName('Morfix Yearly Pro Subscription (370/year)')
                ->setDescription('This subscription is for the Pro package of Morfix, amounting to $370USD per year.')
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());
        }
        
        // Set plan id
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
            // Create agreement on Paypal
            $agreement = $agreement->create($this->apiContext);

            // Extract approval URL to redirect user
            $approvalUrl = $agreement->getApprovalLink();

            return redirect($approvalUrl);
            
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }
    
    public function paypalReturnPremium(Request $request) {
        $paypal_plan_id = MorfixPlan::where('name', 'Premium New')->first()->paypal_id;
        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();
        // Set plan id
        $plan = new Plan();
        $plan->setId($paypal_plan_id);
        $agreement->setPlan($plan);
        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->apiContext);
            $user = Auth::user();
            $user->tier = 2;
            $user->paypal = 1;
            if (isset($result->id)) {
                $_agreement = new PaypalAgreement;
                $_agreement->agreement_id = $result->id;
                $_agreement->email = Auth::user()->email;
                $_agreement->save();
            }
            $user->save();

            //redirect to Success page.
            return redirect()->action('HomeController@index');
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //redirect to fail page
        }
    }
    
    public function paypalReturnPro(Request $request) {
        $paypal_plan_id = MorfixPlan::where('name', 'Pro New')->first()->paypal_id;
        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();
        // Set plan id
        $plan = new Plan();
        $plan->setId($paypal_plan_id);
        $agreement->setPlan($plan);
        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->apiContext);
            $user = Auth::user();
            $user->tier = 3;
            $user->paypal = 1;
            if (isset($result->id)) {
                $_agreement = new PaypalAgreement;
                $_agreement->agreement_id = $result->id;
                $_agreement->email = Auth::user()->email;
                $_agreement->save();
            }
            $user->save();

            //redirect to Success page.
            return redirect()->action('HomeController@index');
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //redirect to fail page
        }
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
