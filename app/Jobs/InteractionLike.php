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

class InteractionLike implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;
    
    protected $user;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();
        echo("\n" . $this->user->user_id);
        $instagram_profiles = InstagramProfile::where('auto_like', true)
                ->where('checkpoint_required', false)
                ->where('account_disabled', false)
                ->where('invalid_user', false)
                ->where('incorrect_pw', false)
                ->where('user_id', $this->user->user_id)
                ->get();

        try {
            foreach ($instagram_profiles as $ig_profile) {

                echo("\n" . $ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

                $config = array();
                $config["storage"] = "mysql";
                $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
                $config["dbtablename"] = "instagram_sessions";

                $debug = false;
                $truncatedDebug = false;
                $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

                if ($ig_profile->proxy === NULL) {
                    $proxy = Proxy::inRandomOrder()->first();
                    $ig_profile->proxy = $proxy->proxy;
                    $ig_profile->save();
                    $proxy->assigned = $proxy->assigned + 1;
                    $proxy->save();
                }

                $instagram->setProxy($ig_profile->proxy);

                try {
                    $like_quota = rand(1, 3);
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    echo("\n" . "Logged in \t quota: " . $like_quota);
                    $engagement_jobs = EngagementJob::where('action', 0)
                            ->where('fulfilled', 0)
                            ->where('insta_username', $ig_username)
                            ->get();

                    /**
                     * Start of engagement jobs.
                     */
                    foreach ($engagement_jobs as $engagement_job) {
                        if ($like_quota > 0) {

                            $media_id = $engagement_job->media_id;
                            $job_id = $engagement_job->job_id;
                            $like_response = NULL;

                            try {

                                $engagement_job->fulfilled = 1;
                                $engagement_job->save();
                                $like_response = $instagram->media->like($media_id);
                            } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {

                                echo("\n" . "checkpt\t" . $checkpoint_ex->getMessage());
                                $ig_profile->checkpoint_required = 1;
                                $ig_profile->save();
                                continue;
                            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {

                                echo("\n" . "network\t" . $network_ex->getMessage());
                                $ig_profile->error_msg = $network_ex->getMessage();
                                $ig_profile->save();
                                continue;
                            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {

                                DB::connection('mysql_old')
                                        ->update("UPDATE engagement_job_queue SET fulfilled = 2 WHERE media_id = ?;", [$media_id]);

                                echo("\n" . "endpt engagement\t" . $endpoint_ex->getMessage());

                                $ig_profile->error_msg = $endpoint_ex->getMessage();
                                $ig_profile->save();
                                continue;
                            } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {

                                echo("\n" . "incorrectpw\t" . $incorrectpw_ex->getMessage());
                                $ig_profile->incorrect_pw = 1;
                                $ig_profile->error_msg = $incorrectpw_ex->getMessage();
                                $ig_profile->save();
                                continue;
                            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {

                                echo("\n" . "feedback\t" . $feedback_ex->getMessage());
                                $ig_profile->invalid_proxy = 1;
                                $ig_profile->error_msg = $feedback_ex->getMessage();
                                $ig_profile->save();
                                continue;
                            } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {

                                echo("\n" . "acctdisabled\t" . $acctdisabled_ex->getMessage());
                                $ig_profile->invalid_user = 1;
                                $ig_profile->error_msg = $acctdisabled_ex->getMessage();
                                $ig_profile->save();
                                continue;
                            } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                                continue;
                            }

                            echo("\n" . "Liked Engagement Job: \t" . serialize($like_response));
                            $like_quota--;
                        } else {
                            break;
                        }
                    }
                    /**
                     * End of engagement jobs.
                     */
                    /*
                     * If user is free tier & not on trial / run out of quota then break.
                     */
                    if ((!($this->user->user_tier > 1 || $this->user->trial_activation == 1)) || !($like_quota > 0)) {
                        continue;
                    }

                    /*
                     * Defined target usernames take precedence.
                     */
                    $target_usernames = InstagramProfileTargetUsername::where('insta_username', $ig_username)->inRandomOrder()->get();
                    foreach ($target_usernames as $target_username) {
                        if ($like_quota > 0) {

                            //Get followers of the target.
                            echo("\n" . "[$ig_username] Target Username: " . $target_username->target_username . "\n");
                            $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId(trim($target_username->target_username)));
                            $target_user_followings = $user_follower_response->users;
                            $duplicate = 0;

                            //Foreach follower of the target.
                            foreach ($target_user_followings as $user_to_like) {

                                if ($like_quota > 0) {

                                    //Blacklisted username.
                                    $blacklisted_username = BlacklistedUsername::find($user_to_like->username);
                                    if ($blacklisted_username !== NULL) {
                                        continue;
                                    }

                                    echo("\n" . $user_to_like->username . "\t" . $user_to_like->id);

                                    //Check for duplicates.
                                    $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                            ->where('target_username', $user_to_like->username)
                                            ->get();

                                    //Duplicate = liked before.
                                    if (count($liked_users) > 0) {
                                        echo("\n" . "Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                        continue;
                                    }

                                    //Check for duplicates.
                                    $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                            ->where('target_username', $user_to_like->username)
                                            ->get();

                                    //Duplicate = liked before.
                                    if (count($liked_users) > 0) {
                                        echo("\n" . "Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                        continue;
                                    }

                                    //Get the feed of the user to like.
                                    $user_feed_response = NULL;
                                    try {
                                        if (is_null($user_to_like)) {
                                            echo("\n" . "Null User - Target Username");
                                            continue;
                                        }
                                        $user_feed_response = $instagram->timeline->getUserFeed($user_to_like->pk);
                                    } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {

                                        echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());

                                        if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                                            $blacklist_username = new BlacklistedUsername;
                                            $blacklist_username->username = $user_to_like->username;
                                            $blacklist_username->save();
                                            echo("\n" . "Blacklisted: " . $user_to_like->username);
                                        }
                                        continue;
                                    } catch (\Exception $ex) {

                                        echo("\n" . "Exception: " . $ex->getMessage());
                                        continue;
                                    }

                                    //Get the media posted by the user.
                                    $user_items = $user_feed_response->items;
                                    //Foreach media posted by the user.
                                    foreach ($user_items as $item) {

                                        if ($like_quota > 0) {

                                            //Check for duplicates.
                                            $liked_logs = LikeLogsArchive::where('insta_username', $ig_username)
                                                    ->where('target_media', $item->id)
                                                    ->get();

                                            //Duplicate = liked media before.
                                            if (count($liked_users) > 0) {
                                                echo("\n" . "Duplicate Log [MEDIA] Found:\t[$ig_username] [" . $item->id . "]");
                                                continue;
                                            }


                                            $like_response = $instagram->media->like($item->id);

                                            if ($like_response->status == "ok") {
                                                echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                                                $like_log = new InstagramProfileLikeLog;
                                                $like_log->insta_username = $ig_username;
                                                $like_log->target_username = $user_to_like->username;
//                                                    $like_log->target_user_insta_id = $user_to_like->id;
                                                $like_log->target_media = $item->id;
                                                $like_log->target_media_code = $item->getItemUrl();
                                                $like_log->log = serialize($like_response);
                                                $like_log->save();
                                                $like_quota--;
                                            }
                                        } else {
                                            break;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        }
                    }

                    if ($like_quota > 0) {

                        /*
                         * Next is to get user-defined hashtags.
                         */
                        $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $ig_username)->inRandomOrder()->get();

                        //Foreach targeted hashtags
                        foreach ($target_hashtags as $target_hashtag) {
                            if ($like_quota > 0) {
                                echo("\n" . "[$ig_username] Target Hashtag: " . $target_hashtag->hashtag . "\n\n");
                                //Get the feed from the targeted hashtag.
                                $hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));
                                //Foreach post under this target hashtag
                                foreach ($hashtag_feed->items as $item) {
                                    //Get user because we need to check if they have been liked before.
                                    $user_to_like = $item->user;
                                    //Weird error, null user. Check to be safe.
                                    if (is_null($user_to_like)) {
                                        echo("\n" . "null user");
                                        continue;
                                    }

                                    //Check for duplicates.
                                    $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                            ->where('target_username', $user_to_like->username)
                                            ->get();

                                    //Duplicate = liked before.
                                    if (count($liked_users) > 0) {
                                        echo("\n" . "duplicate log found:\t[$ig_username] [" . $user_to_like->username . "]");
                                        continue;
                                    }

                                    //Check for duplicates.
                                    $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                            ->where('target_username', $user_to_like->username)
                                            ->get();

                                    //Duplicate = liked before.
                                    if (count($liked_users) > 0) {
                                        echo("\n" . "Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                        continue;
                                    }

                                    if ($like_quota > 0) {

                                        $like_response = $instagram->media->like($item->id);

                                        if ($like_response->status == "ok") {
                                            echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                                            $like_log = new InstagramProfileLikeLog;
                                            $like_log->insta_username = $ig_username;
                                            $like_log->target_username = $user_to_like->username;
                                            $like_log->target_media = $item->id;
                                            $like_log->target_media_code = $item->getItemUrl();
                                            $like_log->log = serialize($like_response);
                                            $like_log->save();
                                            $like_quota--;
                                        }
                                    } else {
                                        break;
                                    }
                                }
                            } else {
                                break;
                            }
                        }
                    } else {
                        break;
                    }

                    /**
                     * Next is to get target usernames by niche
                     */
                    if ($like_quota > 0) {

                        $niche = Niche::find($ig_profile->niche);
                        $niche_targets = $niche->targetUsernames();

                        foreach ($niche_targets as $target_username) {

                            if ($like_quota > 0) {

                                //Get followers of the target.
                                echo("\n" . "Target Username: " . $target_username->target_username . "\n");
                                $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId(trim($target_username->target_username)));
                                $target_user_followings = $user_follower_response->users;
                                $duplicate = 0;

                                //Foreach follower of the target.
                                foreach ($target_user_followings as $user_to_like) {

                                    if ($like_quota > 0) {

                                        //Blacklisted username.
                                        $blacklisted_username = BlacklistedUsername::find($user_to_like->username);
                                        if ($blacklisted_username !== NULL) {
                                            continue;
                                        }

                                        echo("\n" . $user_to_like->username);

                                        //Check for duplicates.
                                        $liked_users = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                                ->where('target_username', $user_to_like->username)
                                                ->get();

                                        //Duplicate = liked before.
                                        if (count($liked_users) > 0) {
                                            echo("\n" . "Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                            continue;
                                        }

                                        //Check for duplicates.
                                        $liked_users = LikeLogsArchive::where('insta_username', $ig_username)
                                                ->where('target_username', $user_to_like->username)
                                                ->get();

                                        //Duplicate = liked before.
                                        if (count($liked_users) > 0) {
                                            echo("\n" . "Duplicate Log Found:\t[$ig_username] [" . $user_to_like->username . "]");
                                            continue;
                                        }

                                        //Get the feed of the user to like.
                                        $user_feed_response = NULL;
                                        try {
                                            if (is_null($user_to_like)) {
                                                echo("\n" . "Null user - target username");
                                                continue;
                                            }
                                            $user_feed_response = $instagram->timeline->getUserFeed($user_to_like->pk);
                                        } catch (\InstagramAPI\Exception\EndpointException $endpt_ex) {

                                            echo("\n" . "Endpoint ex: " . $endpt_ex->getMessage());

                                            if ($endpt_ex->getMessage() == "InstagramAPI\Response\UserFeedResponse: Not authorized to view user.") {
                                                $blacklist_username = new BlacklistedUsername;
                                                $blacklist_username->username = $user_to_like->username;
                                                $blacklist_username->save();
                                                echo("\n" . "Blacklisted: " . $user_to_like->username);
                                            }

                                            continue;
                                        } catch (\Exception $ex) {

                                            echo("\n" . "Exception: " . $ex->getMessage());
                                            continue;
                                        }

                                        //Get the media posted by the user.
                                        $user_items = $user_feed_response->items;
                                        //Foreach media posted by the user.
                                        foreach ($user_items as $item) {

                                            if ($like_quota > 0) {

                                                $like_response = $instagram->media->like($item->id);

                                                if ($like_response->status == "ok") {
                                                    echo("\n" . "[$ig_username] Liked " . serialize($like_response));
                                                    $like_log = new InstagramProfileLikeLog;
                                                    $like_log->insta_username = $ig_username;
                                                    $like_log->target_username = $user_to_like->username;
                                                    $like_log->target_media = $item->id;
                                                    $like_log->target_media_code = $item->getItemUrl();
                                                    $like_log->log = serialize($like_response);
                                                    $like_log->save();
                                                    $like_quota--;
                                                }
                                            } else {
                                                break;
                                            }
                                        }
                                    } else {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    /**
                     * Next is to get target hashtags by niche
                     */
                    if ($like_quota > 0) {

                        $niche = Niche::find($ig_profile->niche);
                        $target_hashtags = $niche->targetHashtags();

                        foreach ($target_hashtags as $target_hashtag) {

                            echo("\n" . "target hashtag: " . $target_hashtag->hashtag . "\n\n");

                            $hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));

                            foreach ($hashtag_feed->items as $item) {

                                $duplicate = 0;

                                $user_to_like = $item->user;

                                if (is_null($user_to_like)) {
                                    echo("\n" . "null user");
                                    continue;
                                }

                                $followed_users = DB::connection('mysql_old')
                                        ->select("SELECT log_id FROM user_insta_profile_like_log WHERE insta_username = ? AND target_username = ?;", [$ig_username, $user_to_like->username]);

                                foreach ($followed_users as $followed_user) {
                                    $duplicate = 1;
                                    echo("\n" . "duplicate log found:\t" . $followed_user->log_id);
                                    break;
                                }

                                if ($duplicate == 1) {
                                    continue;
                                }

                                if ($like_quota > 0) {

                                    if ($like_quota == 0) {
                                        break;
                                    }

                                    $like_response = $instagram->media->like($item->id);

                                    echo("\n" . "liked " . serialize($like_response));

                                    DB::connection('mysql_old')->insert("INSERT INTO user_insta_profile_like_log (insta_username, target_username, target_media, target_media_code, log) "
                                            . "VALUES (?,?,?,?,?);", [$ig_username, $user_to_like->username, $item->id, $item->getItemUrl(), serialize($like_response)]);
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
                    echo("\n" . "checkpt\t" . $checkpoint_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                    echo("\n" . "network\t" . $network_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    echo("\n" . "endpt\t" . $endpoint_ex->getMessage());
                    if ($endpoint_ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                        DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                    }
                    continue;
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    echo("\n" . "incorrectpw\t" . $incorrectpw_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1, error_msg = ? where id = ?;', [$incorrectpw_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                    echo("\n" . "feedback\t" . $feedback_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set invalid_proxy = 1, error_msg = ? where id = ?;', [$feedback_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                    continue;
                } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                    echo("\n" . "acctdisabled\t" . $acctdisabled_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$acctdisabled_ex->getMessage(), $ig_profile->id]);
                    continue;
                }
            } //end loop for ig profile
        } catch (\Exception $ex) {
            echo("\n" . $ex->getLine() . "\t" . $ex->getMessage());
        }
    }

}
