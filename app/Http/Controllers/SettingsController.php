<?php

namespace App\Http\Controllers;

use App\PaymentLog;
use App\StripeActiveSubscription;
use App\StripeDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\AgreementStateDescriptor;
use Response;
use Stripe\Invoice;
use Stripe\Subscription;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use App\PaypalCharges;
use App\PaypalAgreement;
use App\UserCancellationFeedback;

use Braintree_Configuration;
use Braintree_Subscription;

class SettingsController extends Controller
{

	private $apiContext;
	private $client_id;
	private $secret;

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		//get recent subscription
		\Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
		$subscriptions = [];
		$invoices      = [];
		$invoices_     = [];

		if (Auth::user()->paypal != 1) {
			$user_stripe_details = StripeDetail::where('email', Auth::user()->email)->get();
			foreach ($user_stripe_details as $user_stripe_detail) {
				$stripe_id   = $user_stripe_detail->stripe_id;
				$active_subs = StripeActiveSubscription::where('stripe_id', $stripe_id)->get();
				foreach ($active_subs as $active_sub) {
					$subscriptions[] = Subscription::retrieve($active_sub->stripe_subscription_id);
				}
				$invoices_ = Invoice::all([ 'limit' => 100, 'customer' => $stripe_id ]);
			}
		}

		//		if (Auth::user()->stripe_id === NULL) {
		//
		//			$customer        = \Stripe\Customer::create([
		//				"email" => Auth::user()->email,
		//			]);
		//			$user            = User::where('email', Auth::user()->email)->first();
		//			$user->stripe_id = $customer->id;
		//			$user->save();
		//
		//		} else {
		//			//Remove all active subscription
		//			Auth::user()->deleteStripeSubscriptions();
		//			$subscriptions_      = NULL;
		//			$user_stripe_details = StripeDetail::where('email', Auth::user()->email)->get();
		//			foreach ($user_stripe_details as $user_stripe_detail) {
		//				$user_stripe_id         = $user_stripe_detail->stripe_id;
		//				$subscriptions_listings = Subscription::all([ 'customer' => $user_stripe_id ]);
		//
		//				foreach ($subscriptions as $subscription) {
		//					//The Invoices under this subscription
		//					$invoice_listings = Invoice::all([ "subscription" => $subscription->id ]);
		//					$stripe_id        = $subscription->customer;
		//
		//					$invoices[$subscription->id] = $invoice_listings->data[0];
		//
		//					$items = $subscription->items->data;
		//					foreach ($items as $item) {
		//						$plan                                        = $item->plan;
		//						$plan_id                                     = $plan->id;
		//						$active_subscription                         = new StripeActiveSubscription;
		//						$active_subscription->stripe_id              = $stripe_id;
		//						$active_subscription->subscription_id        = $plan_id;
		//						$active_subscription->status                 = $subscription->status;
		//						$active_subscription->start_date             = Carbon::createFromTimestamp($subscription->current_period_start);
		//						$active_subscription->end_date               = Carbon::createFromTimestamp($subscription->current_period_end);
		//						$active_subscription->stripe_subscription_id = $subscription->id;
		//						$active_subscription->save();
		//					}
		//				}
		//			}
		//
		//			$invoices_ = Invoice::all([ 'limit' => 100, 'customer' => Auth::user()->stripe_id ]);
		//		}

		$agreement_id = "";

		if (Auth::user()->paypal == 1) {
			$agreements = PaypalAgreement::where('email', Auth::user()->email)->get();
			foreach ($agreements as $agreement) {
				$agreement_id = $agreement->agreement_id;
			}
		}

		return view('settings.index', [
			'subscriptions' => $subscriptions,
			'invoices'      => $invoices,
			'invoices_'     => $invoices_,
			'agreement_id'  => $agreement_id,
		]);
	}

	public function cancelPaypalAgreement(Request $request)
	{
		$agreement_id = $request->input('agreement_id');

		$this->client_id = config('paypal.live_client_id');
		$this->secret    = config('paypal.live_secret');

		$this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
		$this->apiContext->setConfig(config('paypal.settings'));

		$agreementStateDescriptor = new AgreementStateDescriptor();
		$agreementStateDescriptor->setNote("Cancelled on " . \Carbon\Carbon::now()->toDateTimeString());
		Agreement::get($agreement_id, $this->apiContext)->cancel($agreementStateDescriptor, $this->apiContext);

		return Response::json([ "success" => TRUE, 'message' => "Your subscription has been cancelled." ]);
	}

	public function cancelSubscription($sub_id)
	{
		\Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
		$subscription = Subscription::retrieve($sub_id);
		$subscription->cancel([ 'at_period_end' => TRUE ]);

		$active_sub = StripeActiveSubscription::where('stripe_subscription_id', $sub_id)->first();
		$active_sub->status = 'canceled';
		$active_sub->save();

		return Response::json([ "success" => TRUE, 'message' => "Your subscription has been cancelled." ]);
	}

	public function cancelUserSubscription(Request $request){

		$user_to_cancel = User::where('email', Auth::user()->email)->first();
        $user_to_cancel->tier = 1;
        $user_to_cancel->save();

        $feedback = new UserCancellationFeedback;
        $feedback->email = Auth::user()->email;
        $feedback->first_answer = $request->input('first_question');
        $feedback->second_answer = $request->input('second_question');
        $feedback->third_answer = $request->input('third_question');
        $feedback->fourth_answer = $request->input('fourth_question');
        $feedback->save();


        if(is_null(Auth::user()->braintree_id)):
        //cancell braintree transaction

            Braintree_Configuration::environment('production');
            Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
            Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
            Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');
            $braintree_id = Auth::user()->braintree_id;
            Braintree_Subscription::cancel( $braintree_id );
            $braintree = Braintree_Subscription::where('braintree_id', $braintree_id) ->get();
            foreach ($braintree as $braintree_cancel) {
                $braintree_cancel->status = 'Canceled';
                $braintree_cancel->save();
            }

		elseif($user_to_cancel->paypal == 1) :
		//if paypal user
            $paypal_charges = PaypalCharges::where('email', Auth::user()->email)->get();
            $this->client_id = config('paypal.live_client_id');
            $this->secret    = config('paypal.live_secret');

            $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
            $this->apiContext->setConfig(config('paypal.settings'));

            $agreementStateDescriptor = new AgreementStateDescriptor();
            $agreementStateDescriptor->setNote("Cancelled on " . \Carbon\Carbon::now()->toDateTimeString());

            foreach($paypal_charges as $paypal_charge){
                Agreement::get( $paypal_charge->agreement_id, $this->apiContext)->cancel($agreementStateDescriptor, $this->apiContext);
                $paypal_charge->status = 'Canceled';
                $paypal_charge-save();
            }

        else:
		//stripe user
            \Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
            $stripe_cancellation = StripeActiveSubscription::where('email',Auth::user()->stripe_id)->get();
            foreach ($stripe_cancellation as $stripe_cancel) {

                $subscription = \Stripe\Subscription::retrieve($stripe_cancel->stripe_subscription_id);
                $subscription->cancel();

                $stripe_cancel->status = 'Canceled';
                $stripe_cancel->save();
            }
        endif;

        return Response::json([ "success" => TRUE, 'message' => "Your subscription has been cancelled." ]);

    }
    public function updateAddressCard(Request $request)
    {

        $email = User::where('email', Auth::user()->email)->get();
        foreach ($email as $save_address) {
            $save_address->address = $request->input('address');
            $save_address->save();
        }
        return Response::json([ "success" => TRUE, 'message' => "Address successfully added." ]);

    }

	public function updateCreditCard(Request $request)
	{
		//get recent subscription
		\Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
		$subscriptions = [];
		$invoices      = [];

		$stripeToken = $request->input('stripeToken');
		$user        = Auth::user()->stripe_id;
		$cu          = \Stripe\Customer::retrieve($user); // stored in your application
		$cu->source  = $stripeToken; // obtained with Checkout
		$cu->save();

		$subscriptions_listings = Subscription::all([ 'customer' => Auth::user()->stripe_id ]);
		$subscriptions          = $subscriptions_listings->data;

		//Remove all active subscription
		Auth::user()->deleteStripeSubscriptions();

		foreach ($subscriptions as $subscription) {
			//The Invoices under this subscription
			$invoice_listings = Invoice::all([ "subscription" => $subscription->id ]);
			$stripe_id        = $subscription->customer;

			$invoices[$subscription->id] = $invoice_listings->data[0];

			$items = $subscription->items->data;
			foreach ($items as $item) {
				$plan                                        = $item->plan;
				$plan_id                                     = $plan->id;
				$active_subscription                         = new StripeActiveSubscription;
				$active_subscription->stripe_id              = $stripe_id;
				$active_subscription->subscription_id        = $plan_id;
				$active_subscription->status                 = $subscription->status;
				$active_subscription->start_date             = Carbon::createFromTimestamp($subscription->current_period_start);
				$active_subscription->end_date               = Carbon::createFromTimestamp($subscription->current_period_end);
				$active_subscription->stripe_subscription_id = $subscription->id;
				$active_subscription->save();
			}
		}

		$invoices_ = Invoice::all([ 'limit' => 100, 'customer' => $stripe_id ]);

		//        foreach ($invoices->autoPagingIterator() as $invoice) {
		//            $paid = $invoice->paid;
		//            if (is_bool($paid)) {
		//                if (!$paid) {
		//                    $paid = "Unpaid";
		//                } else {
		//                    $paid = "Paid";
		//                }
		//            }
		//
		//            foreach ($invoice->lines->data as $invoice_lines) {
		//                echo $invoice->id . " [" . $paid . "]\t" . $invoice_lines->plan->id . "\t" .
		//                        \Carbon\Carbon::createFromTimestamp($invoice->date)->toDateTimeString()  . "\n";
		//            }
		//        }

		return view('settings.index', [
			'update_credit_card_response' => "Your card has been updated",
			'subscriptions'               => $subscriptions,
			'invoices'                    => $invoices,
			'invoices_'                   => $invoices_,
		]);
	}

	public function attemptInvoice($invoice_id)
	{
		$payment_log         = new PaymentLog;
		$payment_log->email  = Auth::user()->email;
		$payment_log->plan   = $invoice_id;
		$payment_log->source = "Settings Page - Pay Invoice";
		$payment_log->save();

		try {
			\Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
			$invoice = Invoice::retrieve($invoice_id);
			if ($invoice->pay()->paid == TRUE) {
				$payment_log->log = "invoice_paid";
				$payment_log->save();

				return Response::json([ "success" => TRUE, 'message' => "Your invoice has been paid." ]);
			} else {
				$payment_log->log = "invoice_payment_failed";
				$payment_log->save();

				return Response::json([ "success" => FALSE, 'message' => "Our attempt to charge your invoice has failed." ]);
			}
		}
		catch (\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$payment_log->log = $e->getMessage();
			$payment_log->save();

			return Response::json([ "success" => FALSE, 'message' => $e->getMessage() ]);

		}
		catch (\Stripe\Error\InvalidRequest $e) {
			// Invalid parameters were supplied to Stripe's API
			$payment_log->log = $e->getMessage();
			$payment_log->save();

			return Response::json([ "success" => FALSE, 'message' => $e->getMessage() ]);

		}
		catch (\Stripe\Error\Authentication $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$payment_log->log = $e->getMessage();
			$payment_log->save();

			return Response::json([ "success" => FALSE, 'message' => $e->getMessage() ]);

		}
		catch (\Stripe\Error\ApiConnection $e) {
			// Network communication with Stripe failed
			$payment_log->log = $e->getMessage();
			$payment_log->save();

			return Response::json([ "success" => FALSE, 'message' => $e->getMessage() ]);

		}
		catch (\Stripe\Error\Base $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email
			$payment_log->log = $e->getMessage();
			$payment_log->save();

			return Response::json([ "success" => FALSE, 'message' => $e->getMessage() ]);

		}
		catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$payment_log->log = $e->getMessage();
			$payment_log->save();

			return Response::json([ "success" => FALSE, 'message' => $e->getMessage() ]);
		}

	}

}
