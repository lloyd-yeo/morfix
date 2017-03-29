<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;

class InstagramProfileController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        $user = $request->input("iuser");
        $pw = $request->input("ipw");
        $email = $request->input("user_email");
        
        DB::table("")
        
        $instagram_profiles = DB::table('morfix_instagram_profiles')
                ->where('email', Auth::user()->email)
                ->take(10)
                ->get();
        
        
        
        $config = array();
        $config["storage"] = "mysql";
        $config["dbusername"] = "root";
        $config["dbpassword"] = "inst@ffiliates123";
        $config["dbhost"] = "52.221.60.235:3306";
        $config["dbname"] = "morfix";
        $config["dbtablename"] = "instagram_sessions";

        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        $user_id = $request->input('user-id');
        $user_email = $request->input('user-email');
        $ig_username = $request->input('ig-username');
        $ig_password = $request->input('ig-password');
        
        $log = new CreateInstagramProfileLog;
        $log->insta_username = $ig_username;
        $log->insta_pw = $ig_password;
        $log->email = $user_email;
        $log->save();
        $last_inserted_log_id = $log->log_id;
        
        if (InstagramProfile::where('insta_username', '=', $ig_username)->count() > 0) {
            return Response::json(array("success" => false, 'response' => "This instagram profile already exists in Morfix!"));
        } else {
            $config = array();
            $config["storage"] = "mysql";
            $config["dbusername"] = "root";
            $config["dbpassword"] = "inst@ffiliates123";
            $config["dbhost"] = "52.221.60.235:3306";
            $config["dbname"] = "morfix";
            $config["dbtablename"] = "instagram_sessions";

            $debug = false;
            $truncatedDebug = false;
            $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
            
            $proxy = Proxy::where('assigned', '=', 0)->first();
            $instagram->setProxy($proxy->proxy);
            $proxy->assigned = 1;
            $proxy->save();
            
            try {
                $instagram->setUser($ig_username, $ig_password);
                $explorer_response = $instagram->login();
                $user_response = $instagram->getUserInfoByName($ig_username);
                $instagram_user = $user_response->user;
                
                $log = CreateInstagramProfileLog::find($last_inserted_log_id);
                $log->success_msg = serialize($explorer_response);
                $log->save();
                
                $new_profile = new InstagramProfile;
                $new_profile->user_id = Auth::user()->id;
                $new_profile->email = Auth::user()->email;
                $new_profile->insta_user_id = $instagram_user->pk;
                $new_profile->insta_username = $ig_username;
                $new_profile->insta_pw = $ig_password;
                $new_profile->profile_pic_url = $instagram_user->profile_pic_url;
                $new_profile->profile_full_name = $instagram_user->full_name;
                $new_profile->follower_count = $instagram_user->follower_count;
                $new_profile->num_posts = $instagram_user->media_count;
                $new_profile->proxy = $proxy->proxy;
                $new_profile->save();
                
                return Response::json(array("success" => true, 'response' => serialize($explorer_response), 'user' => serialize($user_response), 'proxy' => $proxy->proxy));
            } catch (InstagramException $ig_ex) {
                $log = CreateInstagramProfileLog::find($last_inserted_log_id);
                $log->error_msg = $ig_ex->getTraceAsString();
                $log->save();
                $message = $ig_ex->getMessage();
                $array = explode(':', $message);
                return Response::json(array("success" => false, 'response' => trim($array[1]), "log" => $last_inserted_log_id));
            }
        }
    }
}
