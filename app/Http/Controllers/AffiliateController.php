<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    /**
     * @param Request $request
     * @param null $user_id
     * @return View
     */
    public function index(Request $request) {
        
        return view('affiliate.dashboard', [
            
        ]);
    }
}
