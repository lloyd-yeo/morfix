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
        dispatch((new \App\Jobs\NewFreeTrialUser($request->input('contact.email'), $request->input('contact.name')))
                                        ->onQueue('freetrialuser'));
        return response('[' . $request->input('contact.email') . '] Free Trial Customer Updated', 200);
    }

    public function salesCustomerCreated(Request $request) {
        return response('', 200);
    }
    
    public function salesNewPurchase(Request $request) {
        return response('', 200);
    }

    public function freeTrialCustomerUpdated() {
        return response('Free Trial Customer Updated', 200);
    }

}
