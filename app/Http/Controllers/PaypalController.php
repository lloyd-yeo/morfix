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

    public function paypalRedirect() {
        // Create new agreement
        $agreement = new Agreement();
        $dt = \Carbon\Carbon::now();
        $dt->timezone = new DateTimeZone('UTC');
        $agreement->setName('Morfix Monthly Premium Subscription (Test)')
                ->setDescription('Morfix Monthly Premium Subscription (Test)')
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

    public function paypalReturn(Request $request) {

        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();
        $agreement->setName('Morfix Monthly Premium Subscription (Test)')
                ->setDescription('Morfix Monthly Premium Subscription (Test)')
                ->setStartDate(\Carbon\Carbon::now()->addMinutes(5)->toIso8601String());
        
        // Set plan id
        $plan = new Plan();
        $plan->setId("P-18H64176W28665839BHWBX7Y");
        $agreement->setPlan($plan);
        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->apiContext);
//            $user = Auth::user();
//            $user->role = 'subscriber';
//            $user->paypal = 1;
//            if (isset($result->id)) {
//                $user->paypal_agreement_id = $result->id;
//            }
//            $user->save();
            
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

    public function create_plan() {

        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('Test Morfix Subscription')
                ->setDescription('Monthly Subscription to Morfix (Test)')
                ->setType('infinite');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Monthly Payments')
                ->setType('REGULAR')
                ->setFrequency('MONTH')
                ->setFrequencyInterval('1')
                ->setCycles('0')
                ->setAmount(new Currency(array('value' => 0.01, 'currency' => 'SGD')));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl('https://app.morfix.co/subscribe/paypal/return')
                ->setCancelUrl('https://website.dev/subscribe/paypal/return')
                ->setAutoBillAmount('yes')
                ->setInitialFailAmountAction('CONTINUE')
                ->setMaxFailAttempts('0');

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        //create the plan
        try {
            $createdPlan = $plan->create($this->apiContext);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                        ->setPath('/')
                        ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);

                // Output plan id
                echo 'Plan ID:' . $plan->getId();
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }

}
