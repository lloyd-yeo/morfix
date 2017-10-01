<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\InstagramProfile;
use App\InstagramHelper;
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
    
    public function create(Request $request) {

        $email = Auth::user()->email;
        $ig_username = $request->input("ig-username");
        $ig_password = $request->input("ig-password");

        $profile_log = new CreateInstagramProfileLog();
        $profile_log->email = $email;
        $profile_log->insta_username = $ig_username;
        $profile_log->insta_pw = $ig_password;
        $profile_log->save();

        $instagram = InstagramHelper::initInstagram();
        
        $proxy = Proxy::inRandomOrder()->first();

        try {
            
            if (InstagramProfile::where('insta_username', '=', $ig_username)->count() > 0) {
                $profile_log->error_msg = "This instagram username has already been added!";
                $profile_log->save();
                return Response::json(array("success" => false, 'type' =>'ig_added', 'response' => "This instagram username has already been added!"));
            }

            $instagram->setProxy($proxy->proxy);
            $explorer_response = $instagram->login($ig_username, $ig_password);

            $morfix_ig_profile = new InstagramProfile();
            $morfix_ig_profile->user_id = Auth::user()->user_id;
            $morfix_ig_profile->email = Auth::user()->email;
            $morfix_ig_profile->insta_username = $ig_username;
            $morfix_ig_profile->insta_pw = $ig_password;
            $morfix_ig_profile->proxy = $proxy->proxy;
            
            $profile_log->error_msg = "Profile successfully created.";
            $profile_log->save();
            
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            
//            $user_response = $instagram->getUserInfoByName($ig_username);
            $user_response = $instagram->people->getInfoByName($ig_username);
            $instagram_user = $user_response->user;
            $morfix_ig_profile->profile_pic_url = $instagram_user->profile_pic_url;
            $morfix_ig_profile->save();
            
            DB::connection('mysql_old')->
                    update("UPDATE user_insta_profile SET updated_at = NOW(), follower_count = ?, num_posts = ?, insta_user_id = ? WHERE insta_username = ?;", [$instagram_user->follower_count, $instagram_user->media_count, $instagram_user->pk, $ig_username]);
            
            $items = $instagram->timeline->getSelfUserFeed()->items;
            
            foreach ($items as $item) {
                try {
                    DB::connection('mysql_old')->
                            insert("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) VALUES (?,?,?);", [$ig_username, $item->id, $item->image_versions2->candidates[0]->url]);
                } catch (\ErrorException $e) {
                    break;
                }
            }
            
            return Response::json(array("success" => true, 'response' => "Profile added!"));
            
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
            $profile_log->error_msg = $checkpt_ex->getMessage();
            $profile_log->save();
            return Response::json(array("success" => false, 'type' =>'checkpoint', 'response' => "Verification Required"));
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $profile_log->error_msg = $incorrectpw_ex->getMessage();
            $profile_log->save();
            return Response::json(array("success" => false, 'type' => 'incorrect_password', 'response' => "Incorrect Password!"));
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            $profile_log->error_msg = $endpoint_ex->getMessage();
            $profile_log->save();
            return Response::json(array("success" => false, 'type' => 'endpoint', 'response' => $endpoint_ex->getMessage()));
        } catch (\InstagramAPI\Exception\ChallengeRequiredException $challenge_required_ex) {
            $profile_log->error_msg = $challenge_required_ex->getMessage();
            $profile_log->save();
            return Response::json(array("success" => false, 'type' => 'checkpoint', 'response' => "Verification Required"));
        }
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
        $user_email = Auth::user()->email;
        $ig_username = $request->input('ig-username');
        $ig_password = $request->input('ig-password');

        $log_id = DB::connection("mysql_old")->insertGetId("INSERT INTO insta_affiliate.create_insta_profile_log (insta_username, insta_pw, email ) VALUES (?,?,?);", [$ig_username, $ig_password, $user_email]);

        if (InstagramProfile::where('insta_username', '=', $ig_username)->count() > 0) {
            $error_msg = "This instagram profile already exists in Morfix!";
            DB::connection("mysql_old")->update("UPDATE insta_affiliate.create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$error_msg, $log_id]);
            return Response::json(array("success" => false, 'response' => $error_msg));
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

            $proxies = DB::connection("mysql_old")->select("SELECT proxy FROM insta_affiliate.proxy ORDER BY RAND() LIMIT 1;");
            foreach ($proxies as $proxy) {
                try {
                    $instagram->setProxy($proxy->proxy);
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();

                    $create_log_id = DB::connection('mysql_old')->insertGetId("INSERT INTO `insta_affiliate`.`user_insta_profile`
                        (`user_id`,`email`,`insta_username`,`insta_pw`,`proxy`) VALUES (?,?,?,?,?);", [$user_id, $email, $user, $pw, $proxy->proxy]);
                    DB::connection("mysql_old")->update("UPDATE insta_affiliate.proxy SET assigned = 1 WHERE proxy = ?;", [$proxy->proxy]);
                    $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy->proxy]);

                    $user_response = $instagram->getUserInfoByName($ig_username);
                    $instagram_user = $user_response->user;
                    DB::connection('mysql_old')->
                            update("UPDATE user_insta_profile SET updated_at = NOW(), follower_count = ?, num_posts = ?, insta_user_id = ? WHERE insta_username = ?;", [$instagram_user->follower_count, $instagram_user->media_count, $instagram_user->pk, $ig_username]);
                    $items = $instagram->getSelfUserFeed()->items;
                    $this->info(serialize($items));

                    foreach ($items as $item) {
                        try {
                            DB::connection('mysql_old')->
                                    insert("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) VALUES (?,?,?);", [$ig_username, $item->id, $item->image_versions2->candidates[0]->url]);
                        } catch (\ErrorException $e) {
                            $this->error("ERROR: " . $e->getMessage());
                            break;
                        }
                    }

                    return Response::json(array("success" => true, 'response' => "Profile added!"));
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
                    return Response::json(array("success" => false, 'response' => serialize($checkpt_ex)));
                    $this->error($checkpt_ex->getMessage());
                    DB::connection('mysql_old')->
                            update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$checkpt_ex->getMessage(), $db_log_id]);
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    return Response::json(array("success" => false, 'response' => serialize($incorrectpw_ex)));
                    $this->error($incorrectpw_ex->getMessage());
                    DB::connection('mysql_old')->
                            update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$incorrectpw_ex->getMessage(), $db_log_id]);
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    return Response::json(array("success" => false, 'response' => serialize($endpoint_ex)));
                    $this->error($endpoint_ex->getMessage());
                    DB::connection('mysql_old')->
                            update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$endpoint_ex->getMessage(), $db_log_id]);
                }
            }
        }


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

            $proxy = "";
            $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy WHERE assigned = 0 LIMIT 1;");
            foreach ($proxies as $proxy_) {
                $proxy = $proxy_->proxy;
                $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy_->proxy]);
            }

            $instagram->setProxy($proxy);
            try {
                $explorer_response = $instagram->login($ig_username, $ig_password);
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
                $new_profile->proxy = $proxy;
                $new_profile->save();

                return Response::json(array("success" => true, 'response' => serialize($explorer_response), 'user' => serialize($user_response), 'proxy' => $proxy));
                
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
    
    public function clearCheckpoint(Request $request) {
        $ig_profile = InstagramProfile::find($request->input('profile-id'));
        $config = array();
        $config["storage"] = "mysql";
        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";

        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        
        $instagram->setProxy($ig_profile->proxy);
        try {
            $explorer_response = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);
            $ig_profile->checkpoint_required = 0;
            $ig_profile->save();
            return Response::json(array("success" => true, 'response' => 'Your profile has restored connectivity.'));
        } catch (\InstagramAPI\Exception\InstagramException $ig_ex) {
            return Response::json(array("success" => false, 'response' => 'Unable to connect to your profile, please retry.'));
        }
    }
    
    public function changePassword(Request $request) {
        $ig_profile = InstagramProfile::find($request->input('profile-id'));
        $password = $request->input('password');
        $ig_profile->insta_pw = $password;
        $ig_profile->save();
        
        $config = array();
        $config["storage"] = "mysql";
        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";

        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        
        $instagram->setProxy($ig_profile->proxy);
        
        try {
            $explorer_response = $instagram->login($ig_profile->insta_username, $ig_password);
            $ig_profile->incorrect_pw = 0;
            $ig_profile->save();
            return Response::json(array("success" => true, 'response' => 'Your profile has restored connectivity.'));
        } catch (\InstagramAPI\Exception\InstagramException $ig_ex) {
            return Response::json(array("success" => false, 'response' => 'Unable to connect to your profile, please retry.'));
        }
    }
    
    public function delete(Request $request, $id) {
        $ig_profile = InstagramProfile::find($id);
        if ($ig_profile->delete()) {
            return Response::json(array("success" => true, 'response' => 'Your profile has been deleted.'));
        } else {
            return Response::json(array("success" => true, 'response' => 'We are unable to delete your profile, please try again later.'));
        }
    }

}
