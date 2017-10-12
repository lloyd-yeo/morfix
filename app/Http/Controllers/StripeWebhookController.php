<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StripeWebhookLog;
use Carbon\Carbon;
use App\StripeCharge;
use App\StripeDetail;
use App\GetReferralForUser;
use App\User;

class StripeWebhookController extends Controller
{

    public function chargeRefunded(Request $request)
    {

        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");

        StripeWebhookController::saveWebhookLog($request);
        $data = $webhook_log = $request->input('data.object');
        $customer_stripe_id = $data['customer'];
        $customer_charge_id = $data['id'];
        $stripe_charge = StripeCharge::where('charge_id', $customer_charge_id)->first();

        if ($stripe_charge !== NULL) {
            $stripe_charge->testing_commission_given = 1;
            //testing column fod refund = 1;
        }
        $user = StripeDetail::where('stripe_id', $customer_stripe_id)->first();

        $referrers = GetReferralForUser::fromView()
            ->where('referred', $user->email)
            ->first();
        $referrer = User::where('email', $referrers->referrer)->first();
        if ($referrer !== NULL) {
            $commissions = 0;
            $downgrade = 0;
            switch ($data['amount']) {
                case "37":
                    $commissions = 20;
                    $downgrade = 1;
                    break;
                case "97":
                    $commissions = 50;
                    $downgrade = 10;
                    break;
                case "297":
                    $commissions = 120;
                    $downgrade = 1;
                    break;
                case "370":
                    $commissions = 200;
                    $downgrade = 1;
                    break;
                case "670":
                    $commissions = 268;
                    $downgrade = 20;
                    break;
                case "0":
                    $commissions = 0;
                    break;
            }

            $referrer->testing_pending_commission = $referrer->pending_commission - $commissions;
            $referrer->tier = $referrer->tier - $downgrade;
        }
    }

    public function chargeSucceeded(Request $request)
    {
        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");

        StripeWebhookController::saveWebhookLog($request);
        $charge_data = $webhook_log = $request->input('data.object');
        $customer_stripe_id = $charge_data['customer'];
        $customer_charge_id = $charge_data['id'];
        $invoice_id = $charge_data["invoice"];
        $charge_date = Carbon::createFromTimestamp($charge_data["created"]);
        $charge_card = $charge_data["source"];
        $card_id = $charge_card["id"];
        $card_brand = $charge_card["brand"];
        $card_last4 = $charge_card["last4"];
        $fail_msg = $charge_data['failure_message'];
        $fail_code = $charge_data['failure_code'];

        //insert new customer //
        $stripe_detail = new StripeCharge;
        $stripe_detail->stripe_id = $customer_stripe_id;
        $stripe_detail->charge_id = $customer_charge_id;
        $stripe_detail->invoice_id = $invoice_id;
        $stripe_detail->charges_paid = 1;
        $stripe_detail->charge_created = $charge_date;
        $stripe_detail->paying_card_id = $card_id;
        $stripe_detail->paying_card_brand = $card_brand;
        $stripe_detail->paying_card_lastfourdigit = $card_last4;
        $stripe_detail->failure_msg = $fail_msg;
        $stripe_detail->failure_code = $fail_code;
        $stripe_detail->save();

    }

    public function invoicePaymentFailed(Request $request)
    {

        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");

        StripeWebhookController::saveWebhookLog($request);
    }

    public static function saveWebhookLog(Request $request)
    {
        $webhook_log = new StripeWebhookLog;
        $webhook_log->log = serialize($request->all());
        $webhook_log->date_logged = Carbon::createFromTimestamp($request->input('created'));
        $webhook_log->save();
    }

}
