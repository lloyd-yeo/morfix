<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
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
        if (NULL !== $this->argument("email")) {
            $users = User::where("email", $this->argument("email"));
        } else {
            foreach (User::where(DB::raw('email IN (SELECT email FROM user_insta_profile) AND user_id IN (SELECT user_id FROM user_insta_profile)'))
                    ->cursor() as $user) {
                $this->line($user->user_id);
            }
        }

        foreach ($users as $user) {
            continue;
            $this->line($user->user_id);

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT id, insta_username, "
                    . "insta_pw, proxy FROM user_insta_profile WHERE auto_interaction = 1 AND auto_comment = 1 "
                    . "AND checkpoint_required = 0 AND invalid_user = 0 AND incorrect_pw = 0 AND user_id = ?;", [$user->user_id]);

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

                    $comments = DB::connection("mysql_old")->select("SELECT comment FROM user_insta_profile_comment WHERE insta_username = ? ORDER BY RAND() LIMIT 1;", [$ig_profile->insta_username]);

                    $engagement_jobs = DB::connection('mysql_old')
                            ->select("SELECT job_id, media_id, action FROM insta_affiliate.engagement_job_queue WHERE action = 1 AND fulfilled = 0 AND insta_username = ? LIMIT 1;", [$ig_username]);
                    $commented = 0;
                    foreach ($engagement_jobs as $engagement_job) {
                        $media_id = $engagement_job->media_id;
                        $job_id = $engagement_job->job_id;
                        $this->line($job_id . "\t" . $media_id);
                        foreach ($comments as $comment) {

                            $comment_response = NULL;

                            try {
                                DB::connection('mysql_old')
                                    ->update("UPDATE engagement_job_queue SET fulfilled = 1 WHERE job_id = ?;", [$job_id]);
                                $comment_response = $instagram->comment($media_id, $comment->comment);
                                $commented = 1;
                            } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                                $this->error("checkpt\t" . $checkpoint_ex->getMessage());
                                DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                                continue;
                            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                                $this->error("network\t" . $network_ex->getMessage());
                                DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
                                continue;
                            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                                DB::connection('mysql_old')
                                        ->update("UPDATE engagement_job_queue SET fulfilled = 2 WHERE media_id = ?;", [$media_id]);
                                $this->error("endpt\t" . $endpoint_ex->getMessage());
                                DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                                continue;
                            } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                                $this->error("incorrectpw\t" . $incorrectpw_ex->getMessage());
                                DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1, error_msg = ? where id = ?;', [$incorrectpw_ex->getMessage(), $ig_profile->id]);
                                continue;
                            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                                $this->error("feedback\t" . $feedback_ex->getMessage());
                                DB::connection('mysql_old')->update('update user_insta_profile set invalid_proxy = 1, error_msg = ? where id = ?;', [$feedback_ex->getMessage(), $ig_profile->id]);
                                continue;
                            } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                                continue;
                            } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                                $this->error("acctdisabled\t" . $acctdisabled_ex->getMessage());
                                DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$acctdisabled_ex->getMessage(), $ig_profile->id]);
                                continue;
                            }
                            $this->info("commented engagement\t" . serialize($comment_response));
                            break;
                        }
                    }

                    if ($commented == 1) {
                        continue;
                    }

                    $new_followers = DB::connection("mysql_old")->select("SELECT follower_username, follower_id FROM insta_affiliate.user_insta_profile_follow_log 
                        WHERE follow = 1 AND unfollowed = 0 AND insta_username = ? 
                        AND follower_username NOT IN (SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = ?) ORDER BY date_inserted DESC LIMIT 1;", [$ig_profile->insta_username, $ig_profile->insta_username]);

                    foreach ($new_followers as $new_follower) {
                        
                        foreach ($comments as $comment) {
                            $comment = $comment->comment;
                            $target_username_posts = $instagram->getUserFeed($new_follower->follower_id);
                            $this->line($new_follower->follower_username . "\t" . $comment);

                            foreach ($target_username_posts->items as $item) {
                                $commented = 1;
                                $comment_response = $instagram->comment($item->pk, $comment);
                                $this->info(serialize($comment_response) . "\n\n\n");
                                $rows_affected = DB::connection("mysql_old")->insert("INSERT INTO insta_affiliate.user_insta_profile_comment_log (insta_username, target_username, target_insta_id, target_media, log, date_commented) VALUES (?,?,?,?,?,?);", [$ig_profile->insta_username, $item->user->username, $item->user->pk, $item->pk, serialize($comment_response), \Carbon\Carbon::now()]);
                                break;
                            }

                            if ($commented == 0) {
                                $comment_response = NULL;
                                $rows_affected = DB::connection("mysql_old")->insert("INSERT INTO insta_affiliate.user_insta_profile_comment_log (insta_username, target_username, target_insta_id, date_commented) VALUES (?,?,?,NOW());", [$ig_profile->insta_username, $new_follower->follower_username, $new_follower->follower_id]);
                            }

                            break;
                        }
                    }
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
                    $this->error("checkpt1 " . $checkpt_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    $this->error("incorrectpw1 " . $incorrectpw_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    $this->error("endpt1 " . $endpoint_ex->getMessage());
//                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                    $this->error("network1 " . $network_ex->getMessage());
                } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                    $this->error("acctdisabled1 " . $acctdisabled_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set account_disabled = 1, error_msg = ? where id = ?;', [$acctdisabled_ex->getMessage(), $ig_profile->id]);
                } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                    $this->error("request1 " . $request_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$request_ex->getMessage(), $ig_profile->id]);
                }
            }
        }
    }

}
