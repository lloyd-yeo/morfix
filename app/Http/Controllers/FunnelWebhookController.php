<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Jobs\NewFreeTrialUser;


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
        dispatch((new \App\Jobs\NewFreeTrialUser($request->input('contact.email'), $request->input('contact.name'), $request->input('contact.ip')))
                                        ->onQueue('freetrialuser'));
        return response('[' . $request->input('contact.email') . '] Free Trial Customer Updated', 200);
    }

    public function salesCustomerCreated(Request $request) {
        
        return response('', 200);
    }
    
    public function salesNewPurchase(Request $request) {
        
        $products = $request->input('purchase.products');
        
        $stripe_plan = NULL;
        foreach ($products as $product) {
            $stripe_plan = $product->stripe_plan;
        }
        
        $contact = $request->input('purchase.contact');
        $contact_email = $contact->email;
        $contact_name = $contact->name;
        $contact_ip = $contact->ip;
        $subscription_id = $request->input('purchase.subscription_id');
        $status = $request->input('purchase.status');
        
        if ($stripe_plan !== NULL && $status === "paid") {
            dispatch((new \App\Jobs\NewPaidUser($contact_email, $contact_name, $contact_ip, $stripe_plan, $subscription_id))
                                           ->onQueue('freetrialuser'));
            return response('Queued for new user [' . $contact_email . ']', 200);
        } else {
            return response('Failed to queue for new user [' . $contact_email . ']', 200);
        }
        
        
    }

    public function freeTrialCustomerUpdated() {
        return response('Free Trial Customer Updated', 200);
    }

}
