<?php

namespace App\Http\Controllers;

use Auth;
use Response;
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
        if (Auth::user()->admin != 1) {
            
        } else {
            return view('admin.index');
        }
    }
    
    public function upgradeUserTier(Request $request) {
        if (Auth::user()->admin == 1) {
            
        } else {
            //not enough permission.
        }
    }
}
