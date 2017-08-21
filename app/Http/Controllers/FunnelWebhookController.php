<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use AWeberAPI;
use App\User;
use App\Mail\NewPassword;
use App\Jobs\NewFreeTrialUser;


class FunnelWebhookController extends Controller {

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
        
        $job = new NewFreeTrialUser($request);
        $job->onQueue('freetrialuser');
        dispatch($job);
        return response('[' . $request->input('contact.email') . '] Free Trial Customer Updated', 200);
    }

    public function salesCustomerCreated() {
        
    }

    public function freeTrialCustomerUpdated() {
        return response('Free Trial Customer Updated', 200);
    }

}
