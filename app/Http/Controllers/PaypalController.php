<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use \DateTimeZone;
use App\User;
use App\MorfixPlan;
use App\PaypalAgreement;

class PaypalController extends Controller {

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

    public function paypalRedirectPremium() {

        $paypal_plan_id = MorfixPlan::where('name', 'Premium')->first()->paypal_id;

        // Instantiate Plan
        $plan = new Plan();
        $plan->setId($paypal_plan_id);

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Morfix Monthly Premium Subscription (37/month)')
                ->setDescription('This subscription is for the Premium package of Morfix, amounting to $37USD per month.')
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());

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

    public function paypalRedirectBusiness() {

        $paypal_plan_id = MorfixPlan::where('name', 'Business')->first()->paypal_id;

        // Instantiate Plan
        $plan = new Plan();
        $plan->setId($paypal_plan_id);

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Morfix Monthly Business Subscription (97/month)')
                ->setDescription('This subscription is for the Business add-on package of Morfix, amounting to $97USD per month.')
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());

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

    public function paypalRedirectPro() {

        $paypal_plan_id = MorfixPlan::where('name', 'Pro')->first()->paypal_id;

        // Instantiate Plan
        $plan = new Plan();
        $plan->setId($paypal_plan_id);

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Morfix Yearly Pro Subscription (370/yr)')
                ->setDescription('This subscription is for the annual Pro package of Morfix, amounting to $370USD per year.')
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());

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

    public function paypalRedirectMastermind() {

        $paypal_plan_id = MorfixPlan::where('name', 'Mastermind')->first()->paypal_id;

        // Instantiate Plan
        $plan = new Plan();
        $plan->setId($paypal_plan_id);

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Morfix Yearly Mastermind Subscription (970/yr)')
                ->setDescription('This subscription is for the annual Mastermind package of Morfix, amounting to $970USD per year.')
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());

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
        $paypal_plan_id = MorfixPlan::where('name', 'Premium')->first()->paypal_id;
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
            return view('payment.index', [
                "upgrade_message" => "You have been successfully upgraded to Premium!"
            ]);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //redirect to fail page
//            var_dump($ex->getData());
//            echo $ex->getTraceAsString() . "\n";
//            echo $ex->getMessage() . "\n";
//            echo 'You have either cancelled the request or your session has expired';
//            echo '<br/><br/>' . \Carbon\Carbon::now()->addMinutes(5)->toIso8601String();
        }
    }

    public function paypalReturnPro(Request $request) {
        $paypal_plan_id = MorfixPlan::where('name', 'Pro')->first()->paypal_id;
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
            return view('payment.index', [
                "upgrade_message" => "You have been successfully upgraded to Pro!"
            ]);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //redirect to fail page
        }
    }

    public function paypalReturnBusiness(Request $request) {
        $paypal_plan_id = MorfixPlan::where('name', 'Business')->first()->paypal_id;
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
            $user->tier = $user->tier + 10;
            $user->num_acct = $user->num_acct + 5;
            $user->paypal = 1;
            if (isset($result->id)) {
                $_agreement = new PaypalAgreement;
                $_agreement->agreement_id = $result->id;
                $_agreement->email = Auth::user()->email;
                $_agreement->save();
            }
            $user->save();

            //redirect to Success page.
            return view('payment.index', [
                "upgrade_message" => "You have been successfully upgraded to Business!"
            ]);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //redirect to fail page
        }
    }

    public function paypalReturnMastermind(Request $request) {
        $paypal_plan_id = MorfixPlan::where('name', 'Mastermind')->first()->paypal_id;
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
            $user->tier = $user->tier + 20;
            $user->num_acct = $user->num_acct + 5;
            $user->paypal = 1;
            if (isset($result->id)) {
                $_agreement = new PaypalAgreement;
                $_agreement->agreement_id = $result->id;
                $_agreement->email = Auth::user()->email;
                $_agreement->save();
            }
            $user->save();

            //redirect to Success page.
            return view('payment.index', [
                "upgrade_message" => "You have been successfully upgraded to Mastermind!"
            ]);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //redirect to fail page
        }
    }

    public function paypalReturn(Request $request) {
        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();
        // Set plan id
        $plan = new Plan();
        $plan->setId("P-18H64176W28665839BHWBX7Y");
        $agreement->setPlan($plan);
        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->apiContext);
            $user = Auth::user();
            $user->user_tier = 200000;
            $user->save();
            echo 'New Subscriber Created and Billed';
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            var_dump($ex->getData());
            echo $ex->getTraceAsString() . "\n";
            echo $ex->getMessage() . "\n";
            echo 'You have either cancelled the request or your session has expired';
            echo '<br/><br/>' . \Carbon\Carbon::now()->addMinutes(5)->toIso8601String();
        }
    }

    public function paypalRedirect() {
        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Morfix Monthly Test Subscription (TestR)')
                ->setDescription('Morfix Monthly TestPremium Subscription (TestR)') //this is the one that shows.
                ->setStartDate(\Carbon\Carbon::now()->addDay(1)->toIso8601String());

        // Set plan id
        $plan = new Plan();
        $plan->setId("P-18H64176W28665839BHWBX7Y");
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
            // Create agreement
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

    public function listPlans() {
        #$params = array();
//        $planList = Plan::all($params, $this->apiContext);
//        $plans = $planList->getPlans();
//        var_dump($planList);
//        var_dump($plans);
        $plan = Plan::get("P-18H64176W28665839BHWBX7Y", $this->apiContext);
        var_dump($plan);
    }


}
