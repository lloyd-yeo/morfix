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

class LegacyInstagramProfileController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        $user = $request->input("iuser");
        $pw = $request->input("ipw");
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
//            $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set proxy = ? where id = ?;', [$proxy->proxy, $ig_profile->id]);
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

}
