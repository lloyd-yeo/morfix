<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\InstagramProfile;
use Response;

class DirectMessageTemplatesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {
        
        $instagram_profiles = InstagramProfile::where('id', $id)
                                ->get();
        
        return view('dm.template', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
    
    public function saveGreetingTemplate($id, Request $request) {
        $template_message = $request->input('message');
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->insta_new_follower_template = $template_message;
        
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            $response = "Your new follower greetings template has been saved!";
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }
    
    public function saveFollowupTemplate($id, Request $request) {
        $template_message = $request->input('message');
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->follow_up_message = $template_message;
        
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            $response = "Your follow-up template has been saved!";
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }
    
    public function toggleAutoDmDelay($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_dm_delay = ($instagram_profile->auto_dm_delay + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_dm_delay == 1) {
                $response = "You have turned <b>ON</b> auto delay for your follow-up message.";
            } else {
                $response = "You have turned <b>OFF</b> auto delay for your follow-up message.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }
}
