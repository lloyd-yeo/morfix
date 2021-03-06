<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\User;

class FunnelWebhookController extends Controller {

    use DispatchesJobs;

    public function test() {
        return response('Hello World', 200);
    }

    public function contactCreated() {
        return response('Contact created', 200);
    }

    public function contactUpdated() {
        return response('Contact Updated', 200);
    }

    public function purchaseCreated() {
        return response('Purchase created', 200);
    }

    public function purchaseUpdated() {
        return response('Purchase Updated', 200);
    }

    public function freeTrialCustomerCreated(Request $request) {
        if (User::where('email', $request->input('contact.email'))->count() == 0) {
            dispatch((new \App\Jobs\NewFreeTrialUser($request->input('contact.email'), $request->input('contact.name'), $request->input('contact.ip')))
                            ->onQueue('freetrialuser'));
            return response('[' . $request->input('contact.email') . '] Free Trial Customer Created', 200);
        } else {
            return response('[' . $request->input('contact.email') . '] Free Trial Customer Exists!', 200);
        }
    }

    public function salesCustomerCreated(Request $request) {

        return response('', 200);
    }

    public function salesNewPurchase(Request $request) {
        
        Log::debug("Rcvd new purchase webhook from ClickFunnels");
        
        $products = $request->input('purchase.products');
        
        $stripe_plan = NULL;
        foreach ($products as $product) {
            $stripe_plan = $product['stripe_plan'];
            Log::debug("Updated stripe plan to: " . $stripe_plan);
        }

        $contact = $request->input('purchase.contact');
        Log::debug("Contact serialized: " . serialize($contact));
        $contact_email = $contact['email'];
        $contact_ip = $contact['ip'];
        Log::debug("Contact email is: " . $contact_email);
        $subscription_id = $request->input('purchase.subscription_id');
        Log::debug("Subscription ID: " . $subscription_id);

        if ($stripe_plan == "0137" || $stripe_plan == "MX370") {
            if (User::where('email', $contact_email)->count() == 0) {
                $contact_name = $contact['name'];
                $contact_ip = $contact['ip'];
                $status = $request->input('purchase.status');
                if ($stripe_plan !== NULL && $status === "paid") {
                    dispatch((new \App\Jobs\NewPaidUser($contact_email, $contact_name, $contact_ip, $stripe_plan, $subscription_id))
                                    ->onQueue('freetrialuser'));
                    return response('Queued for new user [' . $contact_email . ']', 200);
                } else {
                    return response('Failed to queue for new user [' . $contact_email . ']', 200);
                }
            } else {
                dispatch((new \App\Jobs\UpgradeUserTier($contact_email, $subscription_id))
                                ->onQueue('freetrialuser'));
                return response('User exists! [' . $contact_email . ']', 200);
            }
        } else {
            dispatch((new \App\Jobs\UpgradeUserTier($contact_email, $subscription_id))
                                ->onQueue('freetrialuser'));
                return response('Updating tier for user [' . $contact_email . ']', 200);
        }
    }

    public function freeTrialCustomerUpdated() {
        return response('Free Trial Customer Updated', 200);
    }

}
