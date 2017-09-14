<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * @param Request $request
     * @param null $user_id
     * @return View
     */
    public function index(Request $request) {
        
    }
}
