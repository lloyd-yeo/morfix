<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\User;
use App\AdminLog;
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
            
            $admin_log = new AdminLog;
            $admin_log->admin_email = Auth::user()->email;
            $admin_log->action = "UPGRADE_USER_TIER";
            $admin_log->message = "Admin tried upgrading " . $request->input('email') . " to " . $request->input('tier');
            $admin_log->save();
            
            $user = User::where('email', $request->input('email'))->first();
            if ($user !== NULL) {
                $user->tier = $request->input('tier');
                if ($user->save()) {
                    return Response::json(array("success" => true, 
                        'response' => "User tier updated!"));
                } else {
                    return Response::json(array("success" => false, 
                        'response' => "User found but updating failed. Immediately inform Lloyd."));
                }
            } else {
                return Response::json(array("success" => false, 
                        'response' => "User not found. Can't update."));
            }
        } else {
            
            return Response::json(array("success" => false, 
                        'response' => "You are not authorized to carry out this operation."));
        }
    }
}
