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

class LegacyInstagramProfileController extends Controller {

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        
        $user = $request->input("iuser");
        $ig_username = $request->input("iuser");
        $pw = $request->input("ipw");
        $ig_password = $request->input("ipw");
        $email = $request->input("user_email");
        $user_id = $request->input("user_id");
        $db_log_id = $request->input("log_id");

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

        $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy WHERE assigned = 0 LIMIT 1;");
        foreach ($proxies as $proxy) {
            
            try {
                $instagram->setProxy($proxy->proxy);
                $instagram->setUser($ig_username, $ig_password);
                $explorer_response = $instagram->login();

                $create_log_id = DB::connection('mysql_old')->insert("INSERT INTO `insta_affiliate`.`user_insta_profile`
                        (`user_id`,`email`,`insta_username`,`insta_pw`,`proxy`) VALUES (?,?,?,?,?);", [$user_id, $email, $user, $pw, $proxy->proxy]);
                DB::connection("mysql_old")->update("UPDATE insta_affiliate.proxy SET assigned = 1 WHERE proxy = ?;", [$proxy->proxy]);
                $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy->proxy]);

                $user_response = $instagram->getUserInfoByName($ig_username);
                $instagram_user = $user_response->user;
                DB::connection('mysql_old')->
                        update("UPDATE user_insta_profile SET updated_at = NOW(), follower_count = ?, num_posts = ?, insta_user_id = ?, profile_pic_url = ?, profile_full_name = ? WHERE insta_username = ?;", [$instagram_user->follower_count, $instagram_user->media_count, $instagram_user->pk, $instagram_user->profile_pic_url, $instagram_user->full_name, $ig_username]);
                $items = $instagram->getSelfUserFeed()->items;
                
                foreach ($items as $item) {
                    try {
                        DB::connection('mysql_old')->
                                insert("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) VALUES (?,?,?);", [$ig_username, $item->id, $item->image_versions2->candidates[0]->url]);
                    } catch (\ErrorException $e) {
                        $this->error("ERROR: " . $e->getMessage());
                        break;
                    }
                }
                
                return Response::json(array("success" => true, 'response' => "Profile added!", 'type' => 'profile_added'));
                
            } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
                
                $this->error($checkpt_ex->getMessage());
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$checkpt_ex->getMessage(), $db_log_id]);
                return Response::json(array("success" => false, 'response' => $checkpt_ex->getMessage(), 'type' => 'checkpoint'));
                
            } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                
                $this->error($incorrectpw_ex->getMessage());
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$incorrectpw_ex->getMessage(), $db_log_id]);
                return Response::json(array("success" => false, 'response' => $incorrectpw_ex->getMessage(), 'type' => 'incorrectpw'));
                
            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                
                $this->error($endpoint_ex->getMessage());
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$endpoint_ex->getMessage(), $db_log_id]);
                return Response::json(array("success" => false, 'response' => $endpoint_ex->getMessage(), 'type' => 'endpoint'));
                
            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$feedback_ex->getMessage(), $db_log_id]);
                $this->error($feedback_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $feedback_ex->getMessage(), 'type' => 'feedback'));
                
            } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$emptyresponse_ex->getMessage(), $db_log_id]);
                $this->error($emptyresponse_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $emptyresponse_ex->getMessage(), 'type' => 'empty_response'));
                
                
            } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$acctdisabled_ex->getMessage(), $db_log_id]);
                $this->error($acctdisabled_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $acctdisabled_ex->getMessage(), 'type' => 'account_disabled'));
                
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$network_ex->getMessage(), $db_log_id]);
                $this->error($network_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $network_ex->getMessage(), 'type' => 'network'));
                
            } catch (\InstagramAPI\Exception\SentryBlockException $sentryblock_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$sentryblock_ex->getMessage(), $db_log_id]);
                $this->error($sentryblock_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $sentryblock_ex->getMessage(), 'type' => 'sentryblock'));
                
            } catch (\InstagramAPI\Exception\InternalException $internal_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$internal_ex->getMessage(), $db_log_id]);
                $this->error($internal_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $internal_ex->getMessage(), 'type' => 'internal'));
                
            } catch (\InstagramAPI\Exception\LoginRequiredException $loginrequired_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$loginrequired_ex->getMessage(), $db_log_id]);
                $this->error($loginrequired_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $loginrequired_ex->getMessage(), 'type' => 'loginrequired'));
                
            } catch (\InstagramAPI\Exception\InvalidUserException $invaliduser_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$invaliduser_ex->getMessage(), $db_log_id]);
                $this->error($invaliduser_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $invaliduser_ex->getMessage(), 'type' => 'invaliduser'));
                
            } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$request_ex->getMessage(), $db_log_id]);
                $this->error($request_ex->getMessage());
                return Response::json(array("success" => false, 'response' => $request_ex->getMessage(), 'type' => 'request'));
                
            } catch (\ErrorException $ex) {
                
                DB::connection('mysql_old')->
                        update("UPDATE create_insta_profile_log SET error_msg = ? WHERE log_id = ?;", [$ex->getMessage(), $db_log_id]);
                $this->error($ex->getMessage());
                return Response::json(array("success" => false, 'response' => $ex->getMessage(), 'type' => 'exception'));
                
            }
        }
    }

}
