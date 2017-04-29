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

class InteractionLike extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:like {offset : The position to start retrieving from.} {limit : The number of results to limit to.} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Like photos of user\'s intended targets.';

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

        if (NULL !== $this->argument("email")) {
            $users = DB::connection('mysql_old')->select("SELECT u.user_id, u.email FROM insta_affiliate.user u WHERE u.email = ?;", [$this->argument("email")]);
        } else {
            $users = DB::connection('mysql_old')->select("SELECT u.user_id, u.email, u.user_tier, u.trial_activation FROM insta_affiliate.user u "
                    . "WHERE u.user_id IN (SELECT user_id FROM user_insta_profile) "
                    . "ORDER BY u.user_id ASC LIMIT ?,?;", [$offset, $limit]);
        }

        foreach ($users as $user) {
            $this->line($user->user_id);
            $instagram_profiles = DB::connection('mysql_old')->select("SELECT DISTINCT(insta_username),
                insta_user_id, 
                id, 
                niche,
                insta_pw, proxy
                FROM insta_affiliate.user_insta_profile WHERE auto_interaction = 1
                AND user_id = ?
                AND auto_like = 1
                AND checkpoint_required = 0 AND account_disabled = 0 AND invalid_user = 0 AND incorrect_pw = 0;", [$user->user_id]);
            try {
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
                        $like_quota = rand(1, 3);
                        $instagram->setUser($ig_username, $ig_password);
                        $explorer_response = $instagram->login();

                        $this->line("Logged in \t quota: " . $like_quota);

                        $engagement_jobs = DB::connection('mysql_old')
                                ->select("SELECT job_id, media_id, action FROM insta_affiliate.engagement_job_queue WHERE action = 0 AND fulfilled = 0 AND insta_username = ?;", [$ig_username]);
                        
                        foreach ($engagement_jobs as $engagement_job) {
                            $media_id = $engagement_job->media_id;
                            $job_id = $engagement_job->job_id;
                            $like_response = NULL;
                            try {
                                DB::connection('mysql_old')
                                    ->update("UPDATE engagement_job_queue SET fulfilled = 1 WHERE job_id = ?;", [$job_id]);
                                $like_response = $instagram->like($media_id);
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
                            
                            $this->info("liked engagement\t" . serialize($like_response));

                            $like_quota--;

                            if ($like_quota == 0) {
                                break;
                            }
                        }
                        
                        if (!($user->user_tier > 1 || $user->trial_activation == 1)) {
                            continue;
                        }
                        
                        $target_usernames = DB::connection('mysql_old')
                                ->select("SELECT target_username FROM insta_affiliate.user_insta_target_username WHERE insta_username = ? ORDER BY RAND();", [$ig_username]);

                        $liked = 0;

                        if ($like_quota == 0) {
                            continue;
                        }

                        foreach ($target_usernames as $target_username) {

                            $this->line("target username: " . $target_username->target_username . "\n\n");

                            $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId(trim($target_username->target_username)));

                            $users_to_follow = $user_follower_response->users;

                            $duplicate = 0;
                            foreach ($users_to_follow as $user_to_follow) {

                                $this->line($user_to_follow->username . "\n\n");
                                $duplicate = 0;

                                $followed_users = DB::connection('mysql_old')
                                        ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_follow->username]);

                                foreach ($followed_users as $followed_user) {
                                    $duplicate = 1;
                                    $this->info("duplicate log found:\t" . $followed_user->log_id);
                                    break;
                                }

                                if ($duplicate == 1) {
                                    continue;
                                }

                                if ($like_quota > 0) {

                                    $user_feed_response = NULL;
                                    try {

                                        if (is_null($user_to_follow)) {
                                            $this->error("null user - target username");
                                            continue;
                                        }

                                        $user_feed_response = $instagram->getUserFeed($user_to_follow->pk);
                                    } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {
                                        $this->error($endpt_ex->getMessage());
                                        continue;
                                    } catch (\Exception $ex) {
                                        $this->error($ex->getMessage());
                                        continue;
                                    }

                                    $user_items = $user_feed_response->items;

                                    foreach ($user_items as $item) {
                                        if ($like_quota == 0) {
                                            break;
                                        }

                                        $like_response = $instagram->like($item->id);

                                        $this->info("liked " . serialize($like_response));

                                        DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                                                . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_follow->username, $item->id, $item->getItemUrl(), serialize($like_response)]);
                                        $like_quota--;
                                    }
                                }

                                if ($like_quota == 0) {
                                    break;
                                }
                            }

                            if ($like_quota == 0) {
                                break;
                            }
                        }

                        if ($like_quota > 0) {

                            $target_hashtags = DB::connection('mysql_old')
                                    ->select("SELECT hashtag FROM insta_affiliate.user_insta_target_hashtag WHERE insta_username = ? ORDER BY RAND();", [$ig_username]);

                            foreach ($target_hashtags as $target_hashtag) {

                                $this->info("target hashtag: " . $target_hashtag->hashtag . "\n\n");

                                $hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));

                                foreach ($hashtag_feed->items as $item) {

                                    $duplicate = 0;

                                    $user_to_follow = $item->user;

                                    if (is_null($user_to_follow)) {
                                        $this->error("null user");
                                        continue;
                                    }

                                    $followed_users = DB::connection('mysql_old')
                                            ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_follow->username]);

                                    foreach ($followed_users as $followed_user) {
                                        $duplicate = 1;
                                        $this->info("duplicate log found:\t" . $followed_user->log_id);
                                        break;
                                    }

                                    if ($duplicate == 1) {
                                        continue;
                                    }

                                    if ($like_quota > 0) {

                                        if ($like_quota == 0) {
                                            break;
                                        }

                                        $like_response = $instagram->like($item->id);

                                        $this->info("liked " . serialize($like_response));

                                        DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                                                . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_follow->username, $item->id, $item->getItemUrl(), serialize($like_response)]);
                                        $like_quota--;
                                    }

                                    if ($like_quota == 0) {
                                        break;
                                    }
                                }
                                if ($like_quota == 0) {
                                    break;
                                }
                            }
                        }

                        if ($like_quota > 0) {

                            $niche_targets = DB::connection("mysql_old")->select("SELECT target_username FROM insta_affiliate.niche_targets WHERE niche_id = ? ORDER BY RAND();", [$ig_profile->niche]);

                            foreach ($niche_targets as $niche_target) {
                                $this->info("niche target:\t" . $niche_target->target_username);
                                $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId(trim($niche_target->target_username)));

                                $users_to_follow = $user_follower_response->users;

                                foreach ($users_to_follow as $user_to_follow) {
                                    $duplicate = 0;
                                    $followed_users = DB::connection('mysql_old')
                                            ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_follow->username]);

                                    foreach ($followed_users as $followed_user) {
                                        $duplicate = 1;
                                        $this->info("duplicate log found:\t" . $followed_user->log_id);
                                        break;
                                    }

                                    if ($duplicate == 1) {
                                        continue;
                                    }

                                    if ($like_quota > 0) {

                                        $user_feed_response = NULL;

                                        if (is_null($user_to_follow)) {
                                            $this->error("null user");
                                            continue;
                                        }

                                        try {
                                            $user_feed_response = $instagram->getUserFeed($user_to_follow->pk);
                                        } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {
                                            $this->error($endpt_ex->getMessage());
                                            continue;
                                        } catch (\Exception $ex) {
                                            $this->error($ex->getMessage());
                                            continue;
                                        }

                                        $user_items = $user_feed_response->items;

                                        foreach ($user_items as $item) {
                                            if ($like_quota == 0) {
                                                break;
                                            }

                                            $like_response = $instagram->like($item->id);

                                            $this->info("liked " . serialize($like_response));

                                            DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                                                    . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_follow->username, $item->id, $item->getItemUrl(), serialize($like_response)]);
                                            $like_quota--;
                                        }
                                    }

                                    if ($like_quota == 0) {
                                        break;
                                    }
                                }
                                if ($like_quota == 0) {
                                    break;
                                }
                            }
                        }

                        if ($like_quota > 0) {

                            $target_hashtags = DB::connection('mysql_old')
                                    ->select("SELECT hashtag FROM insta_affiliate.niche_targets_hashtags WHERE niche_id = ?;", [$ig_profile->niche]);

                            foreach ($target_hashtags as $target_hashtag) {

                                $this->info("target hashtag: " . $target_hashtag->hashtag . "\n\n");

                                $hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));

                                foreach ($hashtag_feed->items as $item) {

                                    $duplicate = 0;

                                    $user_to_follow = $item->user;

                                    if (is_null($user_to_follow)) {
                                        $this->error("null user");
                                        continue;
                                    }

                                    $followed_users = DB::connection('mysql_old')
                                            ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_follow->username]);

                                    foreach ($followed_users as $followed_user) {
                                        $duplicate = 1;
                                        $this->info("duplicate log found:\t" . $followed_user->log_id);
                                        break;
                                    }

                                    if ($duplicate == 1) {
                                        continue;
                                    }

                                    if ($like_quota > 0) {

                                        if ($like_quota == 0) {
                                            break;
                                        }

                                        $like_response = $instagram->like($item->id);

                                        $this->info("liked " . serialize($like_response));

                                        DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                                                . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_follow->username, $item->id, $item->getItemUrl(), serialize($like_response)]);
                                        $like_quota--;
                                    }

                                    if ($like_quota == 0) {
                                        break;
                                    }
                                }
                                if ($like_quota == 0) {
                                    break;
                                }
                            }
                        }
                    } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                        $this->error("checkpt\t" . $checkpoint_ex->getMessage());
                        DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                        continue;
                    } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                        $this->error("network\t" . $network_ex->getMessage());
                        DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
                        continue;
                    } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
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
                } //end loop for ig profile
            } catch (\Exception $ex) {
                $this->error($ex->getLine() . "\t" . $ex->getMessage());
            }
        }
    }

}
