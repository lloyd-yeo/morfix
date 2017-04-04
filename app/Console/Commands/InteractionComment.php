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

class InteractionComment extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:comment {offset : The position to start retrieving from.} {limit : The number of results to limit to.} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comment on target user\'s photos.';

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

//        $users = DB::connection('mysql_old')->select("SELECT * FROM user ORDER BY user_id ASC LIMIT ?,?;", [$offset, $limit]);
        
        if (NULL !== $this->argument("email")) {
            $users = DB::connection('mysql_old')->select("SELECT u.user_id, u.email FROM insta_affiliate.user u WHERE u.email = ?;", [$this->argument("email")]);
        } else {
            $users = DB::connection('mysql_old')->select("SELECT u.user_id, u.email FROM insta_affiliate.user u WHERE u.email IN (SELECT email FROM user_insta_profile) ORDER BY u.user_id ASC LIMIT ?,?;", [$offset, $limit]);
        }
        
        foreach ($users as $user) {
            $this->line($user->user_id);

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT id, insta_username, insta_pw, proxy, recent_activity_timestamp, insta_new_follower_template, follow_up_message FROM user_insta_profile WHERE auto_interaction = 1 AND auto_comment = 1 AND checkpoint_required = 0 AND user_id = ?;", [$user->user_id]);

            foreach ($instagram_profiles as $ig_profile) {
                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

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

                if ($ig_profile->proxy === NULL) {
                    $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy WHERE assigned = 0 LIMIT 1;");
                    foreach ($proxies as $proxy) {
                        $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set proxy = ? where id = ?;', [$proxy->proxy, $ig_profile->id]);
                        $instagram->setProxy($proxy->proxy);
                        $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy->proxy]);
                    }
                } else {
                    $instagram->setProxy($ig_profile->proxy);
                }

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    $this->line(serialize($instagram->getCurrentUser()));

                    $new_followers = DB::connection("mysql_old")->select("SELECT follower_username, follower_id FROM insta_affiliate.user_insta_profile_follow_log 
                        WHERE follow = 1 AND unfollowed = 0 AND insta_username = ? 
                        AND follower_username NOT IN (SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = ?) ORDER BY date_inserted DESC LIMIT 1;", [$ig_profile->insta_username, $ig_profile->insta_username]);

                    foreach ($new_followers as $new_follower) {
                        $comments = DB::connection("mysql_old")->select("SELECT comment FROM user_insta_profile_comment WHERE insta_username = ? ORDER BY RAND() LIMIT 1;", [$ig_profile->insta_username]);
                        foreach ($comments as $comment) {
                            $comment = $comment->comment;
                            $target_username_posts = $instagram->getUserFeed($new_follower->follower_id);
                            
                            foreach ($target_username_posts->items as $item) {
                                $comment_response = $instagram->comment($item->pk, $comment);
                                $this->info(serialize($comment_response) . "\n\n\n");
                                $rows_affected = DB::connection("mysql_old")->insert("INSERT INTO insta_affiliate.user_insta_profile_comment_log (insta_username, target_username, target_insta_id, target_media, log, date_commented) VALUES (?,?,?,?,?,?);", [$ig_profile->insta_username, $item->user->username, $item->user->pk, $item->pk, serialize($comment_response), \Carbon\Carbon::now()]);
                                break;
                            }
                            break;
                        }
                    }
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
                    $this->error($checkpt_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    $this->error($incorrectpw_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    $this->error($endpoint_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                    $this->error($network_ex->getMessage());
                    continue;
                } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                    $this->error($acctdisabled_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set account_disabled = 1, error_msg = ? where id = ?;', [$acctdisabled_ex->getMessage(), $ig_profile->id]);
                    continue;
                }
            }
        }
    }

}
