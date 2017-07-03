<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\InstagramProfile;
use App\DmJob;
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
        
        $ig_profile = InstagramProfile::where('id', $id)->first();
        
        if ($ig_profile === NULL) {
            return redirect('home');
        }
        
        if ($ig_profile->email != Auth::user()->email) {
             return redirect('home');
        }
        
        return view('dm.template', [
            'ig_profile' => $ig_profile,
        ]);
        
    }
    
    public function saveGreetingTemplate($id, Request $request) {
        $template_message = $request->input('message');
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->insta_new_follower_template = $template_message;
        
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            
            $dm_jobs = DmJob::where('insta_username', $instagram_profile->insta_username)
                        ->where('fulfilled', 0)
                        ->where('follow_up_order', 0)->get();
            
            foreach ($dm_jobs as $dm_job) {
                $new_follower_template = $instagram_profile->insta_new_follower_template;
                $message = str_replace("\${full_name}", $dm_job->recipient_fullname, $new_follower_template);
                preg_match_all('/{([^}]+)}/', $message, $m);
                $new_message = $message;
                for ($j = 0; $j < count($m[1]); $j++) {
                $selected_opt = "";
                $string_to_replace = $m[1][$j];
                if (strpos($string_to_replace, '|') !== false) {
                        $string_opts = explode("|", $string_to_replace);
                        $max = count($string_opts);
                        $max--;
                        $index = rand(0, $max);
                        $selected_opt = $string_opts[$index];
                    } else {
                        $selected_opt = $string_to_replace;
                    }
                    $new_message = str_replace("\${" . $string_to_replace . "}", $selected_opt, $new_message);
                }
                $new_message = mb_convert_encoding($new_message, "UTF8");
                $dm_job->message = $new_message;
                $dm_job->save();
            }
            
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
            $dm_jobs = DmJob::where('insta_username', $instagram_profile->insta_username)
                        ->where('fulfilled', 0)
                        ->where('follow_up_order', 1)->get();
            
            foreach ($dm_jobs as $dm_job) {
                $new_follower_template = $instagram_profile->follow_up_message;
                $message = str_replace("\${full_name}", $dm_job->recipient_fullname, $new_follower_template);
                preg_match_all('/{([^}]+)}/', $message, $m);
                $new_message = $message;
                for ($j = 0; $j < count($m[1]); $j++) {
                $selected_opt = "";
                $string_to_replace = $m[1][$j];
                if (strpos($string_to_replace, '|') !== false) {
                        $string_opts = explode("|", $string_to_replace);
                        $max = count($string_opts);
                        $max--;
                        $index = rand(0, $max);
                        $selected_opt = $string_opts[$index];
                    } else {
                        $selected_opt = $string_to_replace;
                    }
                    $new_message = str_replace("\${" . $string_to_replace . "}", $selected_opt, $new_message);
                }
                
                $new_message = mb_convert_encoding($new_message, "UTF8");
                $dm_job->message = $new_message;
                $dm_job->save();
            }
            
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
    
    public function toggleAutoDm($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_dm_new_follower = ($instagram_profile->auto_dm_new_follower + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_dm_new_follower == 1) {
                $response = "You have turned <b>ON</b> auto direct messaging.";
            } else {
                $response = "You have turned <b>OFF</b> auto direct messaging.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => false, 'message' => $response));
        }
    }
}
