<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FunnelWebhookController extends Controller
{
    public function test() {
        return response('Hello World', 200);
    }
    
    public function contactCreated() {
        return response('Contact created', 200);
    }
}
