<?php

namespace App\Http\Controllers;

use Auth;
use Artisan;
use Response;
use App\User;
use App\AdminLog;
use App\StripeDetail;
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
    
    
    private function logAdminActions($admin_email, $action, $message) {
        $admin_log = new AdminLog;
        $admin_log->admin_email = $admin_email;
        $admin_log->action = $action;
        $admin_log->message = $message;
        $admin_log->save();
    } 
    
    public function upgradeUserTier(Request $request) {
        if (Auth::user()->admin == 1) {
            
            $this->logAdminActions(Auth::user()->email, "UPGRADE_USER_TIER", "Admin tried upgrading " . $request->input('email') . " to " . $request->input('tier'));
            
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
                        'response' => "You are not authorized to carry out this operation. Try again & we will hunt you down via your IP."));
        }
    }
    
    public function getStripeDetails(Request $request) {
        if (Auth::user()->admin == 1) {
            
            $this->logAdminActions(Auth::user()->email, 
                    "GET_STRIPE_DETAILS", 
                    "Admin tried retrieving stripe details for: " . $request->input('email'));
            
            $user = User::where('email', $request->input("email"))->first();
            if ($user !== NULL) {
                $stripe_details = StripeDetail::where('email', $request->input("email"))->get();
                $html = "";
                foreach ($stripe_details as $stripe_detail) {
                    $html .= "<li>" . $stripe_detail->stripe_id . "</li>";
                }
                
                return Response::json(array("success" => true, 
                        'response' => "Sucessfully retrieved!",
                        'html' => $html));
                
            } else {
                return Response::json(array("success" => false, 
                        'response' => "User not found. Nothing retrieved."));
            }
        } else {
            return Response::json(array("success" => false, 
                        'response' => "You are not authorized to carry out this operation. "
                . "Try again & we will hunt you down via your IP."));
        }
    }
    
    public function runInteractionLike(Request $request) {
        if (Auth::user()->admin == 1) {
            
            $this->logAdminActions(Auth::user()->email, 
                    "RUN_INTERACTION_LIKE", 
                    "Admin tried to run interaction:like for: " . $request->input('email'));
            
            $user = User::where('email', $request->input("email"))->first();
            if ($user !== NULL) {
                
                $exitCode = Artisan::call('interaction:like', [
                    'email' => $request->input("email")
                ]);
                
                return Response::json(array("success" => true, 
                        'response' => "Ran interaction:like for " . $request->input('email') . "!"));
                
            } else {
                return Response::json(array("success" => false, 
                        'response' => "User not found! Can't run anything."));
            }
            
        } else {
            return Response::json(array("success" => false, 
                        'response' => "You are not authorized to carry out this operation. "
                . "Try again & we will hunt you down via your IP."));
        }
    }
    
    public function runInteractionComment(Request $request) {
        if (Auth::user()->admin == 1) {
            
            $this->logAdminActions(Auth::user()->email, 
                    "RUN_INTERACTION_COMMENT", 
                    "Admin tried to run interaction:comment for: " . $request->input('email'));
            
            $user = User::where('email', $request->input("email"))->first();
            if ($user !== NULL) {
                
                $exitCode = Artisan::call('interaction:comment', [
                    'email' => $request->input("email")
                ]);
                
                return Response::json(array("success" => true, 
                        'response' => "Ran interaction:comment for " . $request->input('email') . "!"));
                
            } else {
                
                return Response::json(array("success" => false, 
                        'response' => "User not found! Can't run anything."));
            }
        } else {
            return Response::json(array("success" => false, 
                        'response' => "You are not authorized to carry out this operation. "
                . "Try again & we will hunt you down via your IP."));
        }
    }
}
