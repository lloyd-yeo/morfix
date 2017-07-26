<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileTargetHashtag;
use App\EngagementJob;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\Niche;

class RefreshIgProfile implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 180;
    
    protected $profile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InstagramProfile $profile) {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();

        $config = array();
        $config["storage"] = "mysql";
        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";

        $debug = false;
        $truncatedDebug = false;

        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        $ig_profile = $this->profile;
        echo($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);

        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;

        if ($ig_profile->proxy === NULL) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->error_msg = NULL;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
        }

        $instagram->setProxy($ig_profile->proxy);

        try {
            
            $instagram->setUser($ig_username, $ig_password);
            $login_response = $instagram->login();
            $user_response = $instagram->people->getInfoByName($ig_username);
            $instagram_user = $user_response->user;
            
            
            DB::update("UPDATE user_insta_profile "
                    . "SET profile_full_name = ?, updated_at = NOW(), follower_count = ?, "
                    . "num_posts = ?, insta_user_id = ?, profile_pic_url = ? WHERE insta_username = ?;", 
                    [$instagram_user->full_name, $instagram_user->follower_count, 
                        $instagram_user->media_count, $instagram_user->pk, $instagram_user->profile_pic_url, $ig_username]);

            $items = $instagram->timeline->getSelfUserFeed()->items;
            #var_dump($items);

            foreach ($items as $item) {

                try {
                    var_dump($item);
                    $image_url = "";
                    if (is_null($item->image_versions2)) {
                        //is carousel media
                        $image_url = $item->carousel_media[0]->image_versions2->candidates[0]->url;
                    } else {
                        $image_url = $item->image_versions2->candidates[0]->url;
                    }
                    
                    DB::connection('mysql_old')->
                            insert("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) VALUES (?,?,?);", [$ig_username, $item->id, $image_url]);
                } catch (\ErrorException $e) {
                    echo("ERROR: " . $e->getMessage());
                    break;
                }
            }
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            echo($checkpoint_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            echo($network_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            echo($endpoint_ex->getMessage());
            if (stripos(trim($endpoint_ex->getMessage()), "The username you entered doesn't appear to belong to an account. Please check your username and try again.") !== false) {
                $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
                $instagram->setUser("entrepreneur_xyz", "instaffiliates123");
                $instagram->login();
                $resp = serialize($instagram->getUserInfoById($ig_profile->insta_user_id));
                DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$resp, $ig_profile->id]);
            } else {
                DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
            }
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            echo($incorrectpw_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1, error_msg = ? where id = ?;', [$incorrectpw_ex->getMessage(), $ig_profile->id]);
        } catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
            echo($accountdisabled_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set account_disabled = 1, error_msg = ? where id = ?;', [$accountdisabled_ex->getMessage(), $ig_profile->id]);
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
            echo($request_ex->getMessage());
            DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$request_ex->getMessage(), $ig_profile->id]);
        }
    }

}
