<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionFollow extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:follow {offset : The position to start retrieving from.} {limit : The number of results to limit to.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comment on target user\'s followers photo.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $offset = $this->argument('offset');
        $limit = $this->argument('limit');

//        $execute_script = 1;
//        $execute_flags = DB::connection('mysql_old')->select("SELECT * FROM morfix_settings WHERE setting = 'interaction' AND value = ?;", [$offset]);
//        foreach ($execute_flags as $execute_flag) {
//            $execute_script = 0;
//        }

//        if ($execute_script == 1) {
//            DB::connection('mysql_old')->insert("INSERT INTO morfix_settings (setting, value) VALUES (?,?);", ['interaction', $offset]);
//        } else {
//            exit();
//        }

        $users = DB::connection('mysql_old')->select("SELECT u.user_id, u.email FROM insta_affiliate.user u WHERE (u.user_tier > 1 OR u.trial_activation = 1) ORDER BY u.user_id ASC LIMIT ?,?;", [$offset, $limit]);

        foreach ($users as $user) {
            $this->line($user->user_id);

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT DISTINCT(insta_username),
                insta_user_id, 
                id, 
                insta_pw,
                niche, 
                next_follow_time, 
                niche_target_counter, 
                unfollow, 
                auto_interaction_ban, 
                auto_interaction_ban_time,
                follow_cycle,
                auto_unfollow,
                auto_follow,
                auto_follow_ban,
                auto_follow_ban_time,
                follow_unfollow_delay,
                speed,
                follow_min_followers,
                follow_max_followers,
                unfollow_unfollowed,
                daily_follow_quota,
                daily_unfollow_quota,
                proxy
                FROM insta_affiliate.user_insta_profile 
                WHERE auto_interaction = 1
                AND email = ?
                AND (auto_follow = 1 OR auto_unfollow = 1) 
                AND NOW() >= next_follow_time 
                AND checkpoint_required = 0 AND account_disabled = 0 AND invalid_user = 0 AND incorrect_pw = 0;", [$user->email]);

            foreach ($instagram_profiles as $ig_profile) {

                $follow_unfollow_delay = 0;

                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

                if ($ig_profile->auto_follow_ban == 1) {
                    continue;
                }

                if ($ig_profile->speed == "Fast") {
                    $follow_unfollow_delay = rand(1, 2);
                }
                if ($ig_profile->speed == "Medium") {
                    $follow_unfollow_delay = rand(3, 4);
                }
                if ($ig_profile->speed == "Slow") {
                    $follow_unfollow_delay = rand(5, 6);
                }
                if ($ig_profile->speed == "Ultra Fast") {
                    $follow_unfollow_delay = 0;
                }

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
                $instagram->setProxy($ig_profile->proxy);

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    $this->line(serialize($explorer_response) . "\n\n\n\n");
                    if (($ig_profile->auto_unfollow == 1 && $ig_profile->auto_follow == 0) || ($ig_profile->auto_follow == 1 && $ig_profile->unfollow == 1)) {
                        //unfollow
                        continue;
                    }

                    $target_usernames = DB::connection('mysql_old')->select("SELECT target_username FROM insta_affiliate.user_insta_target_username WHERE insta_username = ? ORDER BY RAND();", [$ig_profile->insta_username]);

//                    $skip_username = 0;
//                    if (count($target_usernames) == 0) {
//                        $skip_username = 1;
//                    }
                    
                    $followed = 0;

                    foreach ($target_usernames as $target_username) {
                        $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId($target_username));
//                        $this->info(serialize($user_follower_response) . "\n\n");
                        
                        $users_to_follow = $user_follower_response->users;

                        foreach ($users_to_follow as $user_to_follow) {
                            if ($followed == 0) {
                                $response = $instagram->follow($user_to_follow->pk);
                                $this->info("following " . $response->friendship_status->following . "\n\n");
                            }
                            if ($response->status == "ok") {
                                DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_follow_log (insta_username, follower_username, follower_id, log, date_inserted) VALUES (?,?,?,?,NOW());", [$ig_profile->insta_username, $user_to_follow->username, $user_to_follow->pk, $followed]);
                            }
                            $followed = 1;
                        }
                    }
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                    $this->error($checkpoint_ex->getMessage());
                } catch (InstagramAPI\Exception\NetworkException $network_ex) {
                    $this->error($network_ex->getMessage());
                } catch (InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    $this->error($endpoint_ex->getMessage());
                }
            }
        }

//        DB::connection('mysql_old')->delete("DELETE FROM morfix_settings WHERE setting = 'interaction' AND value = ?;", [$offset]);
    }

}
