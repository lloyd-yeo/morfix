<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\Niche;
use App\NicheTarget;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionFollow extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:follow {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Follow user\'s intended targets.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     *
     *  Local Variables
     *
     */
    protected $ig_username;
    protected $ig_password;
    protected $insta_username;
    protected $insta_user_id;
    protected $insta_id;
    protected $insta_pw;
    protected $niche;
    protected $next_follow_time; //
    protected $unfollow; // Not use
    protected $follow_cycle;
    protected $auto_unfollow;
    protected $auto_follow;
    protected $auto_follow_ban;
    protected $auto_follow_ban_time;
    protected $follow_unfollow_delay;
    protected $speed;
    protected $follow_min_follower;
    protected $follow_max_follower;
    protected $unfollow_unfollowed;
    protected $follow_quota;
    protected $unfollow_quota;
    protected $proxy;
    protected $delay;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $users = NULL;
        if (NULL !== $this->argument("email")) {
            $this->line('email: ' . $this->argument("email"));
            $users = User::where('email', $this->argument("email"))->get();
        } else {
            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile)')
                    ->orderBy('user_id', 'asc')
                    ->get();
        }

        foreach ($users as $user) {
            $this->line($user->user_id);
            $instagram_profiles = InstagramProfile::whereRaw('(auto_follow = 1 OR auto_unfollow = 1) '
                            . 'AND checkpoint_required = 0 '
                            . 'AND account_disabled = 0 '
                            . 'AND invalid_user = 0 '
                            . 'AND incorrect_pw = 0 '
                            . 'AND (NOW() >= next_follow_time OR next_follow_time IS NULL) '
                            . 'AND user_id = ' . $user->user_id)->get();
            if (NULL === $this->argument("email")) {
                if ($user->tier > 1 || $user->trial_activation == 1) {
                    foreach ($instagram_profiles as $ig_profile) {
                        dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
                                        ->onQueue('follows'));
                        $this->line("queued profile: " . $ig_profile->insta_username);
                    }
                }
            } else {
                foreach ($instagram_profiles as $ig_profile) {
                    $this->jobHandle($ig_profile);
                }
            }
        }
    }

    private function jobHandle($ig_profile) {
        
        if ($ig_profile->feedback_required == 1) {
            echo "[" . $ig_profile->insta_username . "] has feedback_required flag on.\n";
        }
        
        $this->initVariables($ig_profile);
        
        $followed_logs = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->where('follow', 1)
                ->where('unfollowed', 0)
                ->get();

        $followed_count = count($followed_logs);

        echo "[" . $ig_profile->insta_username . "] number of follows: " . $followed_count . "\n";
        
        if ($followed_count >= $ig_profile->follow_cycle) {
            echo "[" . $ig_profile->insta_username . "] number of follows exceeded follow cycle.\n";
            $ig_profile->unfollow = 1;
            $this->unfollow = 1;
            $ig_profile->save();
            $ig_profile = InstagramProfile::find($ig_profile->id);
        }
        
        
        $target_username = "";
        $target_follow_username = "";
        $target_follow_id = "";
        $custom_target_defined = false;
        
        $auto_follow = $this->auto_follow;
        $auto_unfollow = $this->auto_unfollow;
        $unfollow = $this->unfollow;
        $speed = $this->speed;
        
        echo "[" . $this->insta_username . "] Niche: " . $this->niche . " Auto_Follow: " . $auto_follow . " Auto_Unfollow: " . $auto_unfollow . "\n";

        if ($unfollow == 1) {
            echo "[" . $this->insta_username . "] will be unfollowing this round.\n";
        } else {
            echo "[" . $this->insta_username . "] will be following this round.\n";
        }

        /*
          Get Unfollow Delay
         */

        $follow_unfollow_delay = $this->followUnfollowDelay($speed);

        $this->delay = rand($follow_unfollow_delay, $follow_unfollow_delay + 2); //randomize the delay to escape detection from IG.
        
        //go into unfollowing mode if user is entirely on unfollow OR on the unfollowing cycle.
        if (($auto_unfollow == 1 && $auto_follow == 0) || ($auto_follow == 1 && $auto_unfollow == 1 && $unfollow == 1)) {
            $this->unFollowing($ig_profile);
        } else if (($unfollow == 0 && $auto_follow == 1) || ($auto_follow == 1 && $auto_unfollow == 0)) {
            $this->autoFollow($ig_profile);
        }
    }

    private function initVariables($ig_profile) {
        /*
          1. Initialize instagram account
         */
        echo($ig_profile->insta_username . "\t" . $ig_profile->insta_pw . "\n");
        $this->ig_username = $ig_profile->insta_username;
        $this->ig_password = $ig_profile->insta_pw;
        $this->insta_username = $ig_profile->insta_username;
        $this->insta_user_id = $ig_profile->insta_user_id;
        $this->insta_id = $ig_profile->insta_id;
        $this->insta_pw = $ig_profile->insta_pw;
        $this->niche = $ig_profile->niche;
        $this->next_follow_time = $ig_profile->next_follow_time;
        $this->unfollow = $ig_profile->unfollow;
        $this->follow_cycle = $ig_profile->follow_cycle;
        $this->auto_unfollow = $ig_profile->auto_unfollow;
        $this->auto_follow = $ig_profile->auto_follow;
        $this->auto_follow_ban = $ig_profile->auto_follow_ban;
        $this->auto_follow_ban_time = $ig_profile->auto_follow_ban_time;
        $this->follow_unfollow_delay = $ig_profile->follow_unfollow_delay;
        $this->speed = $ig_profile->speed;
        $this->follow_min_follower = $ig_profile->follow_min_follower;
        $this->follow_max_follower = $ig_profile->follow_max_follower;
        $this->unfollow_unfollowed = $ig_profile->unfollow_unfollowed;
        $this->follow_quota = $ig_profile->follow_quota;
        $this->unfollow_quota = $ig_profile->unfollow_quota;
        $this->proxy = $ig_profile->proxy;
    }

    private function followUnfollowDelay($speed) {
        switch ($speed) {
            case 'Fast': return 2;
            case 'Medium': return 3;
            case 'Slow': return 5;
            case 'Ultra Fast': return 0;
            case 'weikian_': return 0;
            default: return 5;
        }
    }

    public function loginSegment(Instagram $instagram, $ig_profile) {
        //[LOGIN segment]
        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;
        $instagram->setProxy($ig_profile->proxy);
        $instagram->setUser($ig_username, $ig_password);

        try {
            $instagram->login();
            echo "[$ig_username] logged in.\n";
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {

            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            $instagram->setProxy($ig_profile->proxy);
            $instagram->login();

//                    var_dump($network_ex);
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            exit();
        }
    }

    private function forcedToUnfollow(Instagram $instagram, $ig_profile) {
        $insta_username = $this->insta_username;
        echo "[" . $insta_username . "] has no follows to unfollow.\n\n";

        #forced unfollow
        if ($this->auto_unfollow == 1 && $this->auto_follow == 0) {
            echo "[" . $insta_username . "] adding new unfollows..\n";
            #$followings = $instagram->getSelfUsersFollowing();
            $followings = $instagram->people->getSelfFollowing();
            foreach ($followings->users as $user) {

                try {
                    if (InstagramProfileFollowLog::where('insta_username', $insta_username)->where('follower_id', $user->pk)->count() > 0) {
                        continue;
                    }
                    $follow_log = new InstagramProfileFollowLog;
                    $follow_log->insta_username = $insta_username;
                    $follow_log->follower_username = $user->username;
                    $follow_log->follower_id = $user->pk;
                    $follow_log->follow_success = 1;
                    $follow_log->save();
                } catch (Exception $ex) {
                    echo "[" . $insta_username . "] " . $ex->getMessage() . "..\n";
                    continue;
                }
            }
        } else {
            $ig_profile->unfollow = 0;
            if ($ig_profile->save()) {
                echo "[" . $insta_username . "] is following next round.\n\n";
            }
        }
    }

    private function unFollowing($ig_profile) {
        /*
                - configure database
                - set proxy
            try:
                - Login
                - Get Users to unfollow
                    - 0, force to unfollow
                    - > 0, 
            catch:
                - CheckpointRequiredException
                - NetworkException
                - EndpointException
                - IncorrectPasswordException
                - FeedbackRequiredException
                - EmptyResponseException
                - ThrottledException
                - RequestException
        */
        $insta_username = $this->insta_username;
        $unfollow_quota = $this->unfollow_quota;
        
        if ($unfollow_quota < 1) {
            echo "[" . $insta_username . "] has reached quota for unfollowing today.\n";
            exit();
        }

        echo "[" . $insta_username . "] beginning unfollowing sequence.\n";
        DB::reconnect();
        $config = array();
        $config['pdo'] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        $config["storage"] = "mysql";

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

        $current_log_id = "";
        $current_user_to_unfollow = NULL;

        try {
            $ig_username = $insta_username;
            $ig_password = $this->insta_pw;


            //[LOGIN segment]
            $this->loginSegment($instagram, $ig_profile);
            //[get users to UNFOLLOW]
            $users_to_unfollow = InstagramProfileFollowLog::where('insta_username', $ig_username)
                    ->where('unfollowed', false)
                    ->where('follow', true)
                    ->orderBy('date_inserted', 'asc')
                    ->take(2)
                    ->get();



            if (count($users_to_unfollow) == 0) {
                //Forced to unfollow
                $this->forcedToUnfollow($instagram, $ig_profile);
            } else {
                foreach ($users_to_unfollow as $user_to_unfollow) {
                    echo "[" . $insta_username . "] retrieved: " . $user_to_unfollow->follower_username . "\n";
                    $current_log_id = $user_to_unfollow->log_id;

                    if ($unfollow_unfollowed == 1) {
                        $friendship = $instagram->people->getFriendship($user_to_unfollow->follower_id);
                        if ($friendship->followed_by == true) {
                            echo "[" . $insta_username . "] is followed by " . $user_to_unfollow->follower_username . "\n";
                            $user_to_unfollow->unfollowed = 1;
                            $user_to_unfollow->date_unfollowed = \Carbon\Carbon::now();
                            if ($user_to_unfollow->save()) {
                                echo "[" . $insta_username . "] marked as unfollowed & updated log: " . $user_to_unfollow->log_id . "\n\n";
                            }
                            continue;
                        }
                    }

                    #$resp = $instagram->unfollow($user_to_unfollow->follower_id);
                    $resp = $instagram->people->unfollow($user_to_unfollow->follower_id);
                    echo "[" . $insta_username . "] ";
//                        var_dump($resp);

                    if ($resp->friendship_status->following === false) {
                        $user_to_unfollow->unfollowed = 1;
                        $user_to_unfollow->date_unfollowed = \Carbon\Carbon::now();
                        if ($user_to_unfollow->save()) {
                            echo "[" . $insta_username . "] marked as unfollowed & updated log: " . $user_to_unfollow->log_id . "\n\n";
                        }

                        $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
                        $ig_profile->unfollow_quota = $ig_profile->unfollow_quota - 1;
                        if ($ig_profile->save()) {
                            echo "[$insta_username] added $delay minutes of delay & new unfollow quota = " . $ig_profile->unfollow_quota;
                        }
                        break;
                    }
                }
            }
            //[END get users to UNFOLLOW]
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            echo "[" . $insta_username . "] checkpoint_ex: " . $checkpoint_ex->getMessage() . "\n";
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            echo "[" . $insta_username . "] network_ex: " . $network_ex->getMessage() . "\n";
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            echo "[" . $insta_username . "] endpoint_ex: " . $endpoint_ex->getMessage() . "\n";

            if (stripos(trim($endpoint_ex->getMessage()), "Requested resource does not exist.") !== false) {
                $unfollow_log_to_update = InstagramProfileFollowLog::find($current_log_id);
                $unfollow_log_to_update->unfollowed = 1;
                $unfollow_log_to_update->save();
                $followed = 1;
                exit();
            }
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            echo "[" . $insta_username . "] incorrectpw_ex: " . $incorrectpw_ex->getMessage() . "\n";
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
            echo "[" . $insta_username . "] feedback_ex: " . $feedback_ex->getMessage() . "\n";
        } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
            echo "[" . $insta_username . "] emptyresponse_ex: " . $emptyresponse_ex->getMessage() . "\n";
            if (stripos(trim($emptyresponse_ex->getMessage()), "No response from server. Either a connection or configuration error") !== false) {
                $unfollow_log_to_update = InstagramProfileFollowLog::find($current_log_id);
                $unfollow_log_to_update->unfollowed = 1;
                $unfollow_log_to_update->save();
                $followed = 1;
                exit();
            }
        } catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
            echo "[" . $insta_username . "] throttled_ex: " . $throttled_ex->getMessage() . "\n";
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
            echo "[" . $insta_username . "] request_ex: " . $request_ex->getMessage() . "\n";
            if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                $ig_profile->feedback_required = 1;
                $ig_profile->save();
                $followed = 1;
                exit();
            }
        }
    }

    private function autoFollow($ig_profile) {
        
        $insta_username = $ig_profile->insta_username;
        $follow_quota = $this->follow_quota;
        $unfollow_quota = $this->unfollow_quota;
        $follow_min_follower = $this->follow_min_follower;
        $follow_max_follower = $this->follow_max_follower;
        $follow_cycle = $this->follow_cycle;
        $delay = $this->delay;
        
        if ($follow_quota < 1) {
            echo "[" . $insta_username . "] has reached quota for following today.\n";
            exit();
        }

        echo "[" . $insta_username . "] beginning following sequence.\n";
        
        DB::reconnect();
        $config = array();
        $config['pdo'] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        $config["storage"] = "mysql";

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

        $ig_username = $insta_username;
        $ig_password = $this->insta_pw;

        //[LOGIN segment]
        $instagram->setProxy($ig_profile->proxy);
        $instagram->setUser($ig_username, $ig_password);

        try {
            $instagram->login();
            echo "[$ig_username] logged in.\n";
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            $instagram->setProxy($ig_profile->proxy);
            $instagram->login();
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            exit();
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
            exit();
        } catch (\InstagramAPI\Exception\SentryBlockException $sentry_block_ex) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            exit();
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            if (strpos($endpoint_ex->getMessage(), 'The username you entered doesn\'t appear to belong to an account.') !== false) {
                $ig_profile->invalid_user = 1;
                $ig_profile->save();
            }
        }
        
        //[End LOGIN]
        //start with targeted usernames/hashtags
        $use_hashtags = rand(0, 1);
        echo "[" . $insta_username . "] random use hashtag: $use_hashtags\n";
        $target_hashtags = NULL;
        $target_usernames = NULL;

        if ($use_hashtags == 1) {
            $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $insta_username)->get();
            $target_hashtags->shuffle();
            if (count($target_hashtags) == 0) {
                $target_usernames = InstagramProfileTargetUsername::where('insta_username', $insta_username)
                        ->where('invalid', 0)
                        ->where('insufficient_followers', 0)
                        ->get();
                $target_usernames->shuffle();
                $use_hashtags = 0;
            }
        } else if ($use_hashtags == 0) {
            $target_usernames = InstagramProfileTargetUsername::where('insta_username', $insta_username)
                    ->where('invalid', 0)
                    ->where('insufficient_followers', 0)
                    ->get();
            $target_usernames->shuffle();
            if (count($target_usernames) == 0) {
                $target_hashtags = InstagramProfileTargetHashtag::where('insta_username', $insta_username)->get();
                $target_hashtags->shuffle();
                if (count($target_hashtags) > 0) {
                    $use_hashtags = 1;
                } else {
                    $use_hashtags = 0;
                }
            }
        }

        echo "[" . $insta_username . "] AFTER random use hashtag: $use_hashtags\n";
        echo "[" . $insta_username . "] [target_hashtag_size: " . count($target_hashtags) . "] [target_usernames_size: " . count($target_usernames) . "] [niche: " . $this->niche . "]\n";
        $followed = 0;
        $throttle_limit = 41;

        if ($use_hashtags == 1 && count($target_hashtags) > 0) {

            $throttle_count = 0;

            try {
                foreach ($target_hashtags as $target_hashtag) {

                    echo "[" . $insta_username . "] using hashtag: " . $target_hashtag->hashtag . "\n";
                    #$hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));
                    $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));

                    foreach ($hashtag_feed->items as $item) {

                        $throttle_count++;

                        if ($throttle_count == $throttle_limit) {
                            break;
                        }

                        $user_to_follow = $item->user;

                        if (InstagramProfileFollowLog::where('insta_username', $insta_username)->where('follower_id', $user_to_follow->pk)->count() > 0) {
                            //user exists aka duplicate
                            echo "[" . $insta_username . "] has followed [$user_to_follow->username] before.\n";
                            continue;
                        } else {
                            if ($user_to_follow->is_private) {
                                echo "[" . $insta_username . "] [$user_to_follow->username] is private.\n";
                                continue;
                            } else if ($user_to_follow->has_anonymous_profile_picture) {
                                echo "[" . $insta_username . "] [$user_to_follow->username] has no profile pic.\n";
                                continue;
                            } else {
                                try {
                                    #$user_info = $instagram->getUserInfoById($user_to_follow->pk);
                                    $user_info = $instagram->people->getInfoById($user_to_follow->pk);
                                    $user_to_follow = $user_info->user;

                                    if ($user_to_follow->media_count == 0) {
                                        echo "[$insta_username] [$user_to_follow->username] does not meet requirement: > 0 photos \n";
                                        continue;
                                    }

                                    if ($follow_min_follower != 0 && $user_to_follow->follower_count < $follow_min_follower) {
                                        echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] < [$follow_min_follower] \n";
                                        continue;
                                    }

                                    if ($follow_max_follower != 0 && $user_to_follow->follower_count > $follow_max_follower) {
                                        echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] > [$follow_max_follower] \n";
                                        continue;
                                    }

                                    #$follow_resp = $instagram->follow($user_to_follow->pk);
                                    $follow_resp = $instagram->people->follow($user_to_follow->pk);

                                    if ($follow_resp->friendship_status->following == true) {

                                        $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
                                        $ig_profile->follow_quota = $ig_profile->follow_quota - 1;

                                        if ($ig_profile->save()) {
                                            echo "[$insta_username] HASHTAG added $delay minutes of delay & new follow quota = " . $ig_profile->follow_quota;
                                        }

                                        $new_follow_log = new InstagramProfileFollowLog;
                                        $new_follow_log->insta_username = $insta_username;
                                        $new_follow_log->follower_username = $user_to_follow->username;
                                        $new_follow_log->follower_id = $user_to_follow->pk;
                                        $new_follow_log->log = serialize($follow_resp);
                                        $new_follow_log->follow_success = 1;
                                        if ($new_follow_log->save()) {
                                            echo "[$insta_username] added new follow log.";
                                        }

                                        echo "[$insta_username] follow cycle: " . $follow_cycle . "\n";

                                        $followed_logs = InstagramProfileFollowLog::where('insta_username', $insta_username)
                                                ->where('follow', 1)
                                                ->where('unfollowed', 0)
                                                ->get();

                                        $followed_count = count($followed_logs);
                                        echo "[$insta_username] number of follows: " . $followed_count . "\n";

                                        if ($followed_count >= $follow_cycle) {
                                            $ig_profile->unfollow = 1;
                                            $ig_profile->save();
                                        }

                                        $followed = 1;
                                        echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                        break;
                                    } else {
                                        continue;
                                    }
                                } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                    echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";

                                    if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                                        $ig_profile->feedback_required = 1;
                                        $ig_profile->save();
                                        $followed = 1;
                                        break;
                                    } else if (stripos(trim($request_ex->getMessage()), "Feedback") !== false) {
                                        $ig_profile->feedback_required = 1;
                                        $ig_profile->auto_follow_ban = 1;
                                        $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(6)->toDateTimeString();
                                        $ig_profile->save();
                                        $followed = 1;
                                        break;
                                    }

                                    if (stripos(trim($request_ex->getMessage()), "Throttled by Instagram because of too many API requests.") !== false) {
                                        $ig_profile->feedback_required = 1;
                                        $ig_profile->save();
                                        $followed = 1;
                                        break;
                                    }

                                    if (stripos(trim($request_ex->getMessage()), "Sorry, you're following the max limit of accounts. You'll need to unfollow some accounts to start following more.") !== false) {
                                        $followed = 1;
                                        break;
                                    }
                                    continue;
                                }
                            }
                        }
                    }

                    if ($throttle_count == $throttle_limit) {
                        break;
                    }

                    if ($followed == 1) {
                        break;
                    }
                }
            } catch (Exception $ex) {
                echo "[" . $insta_username . "] hashtag-error: " . $ex->getMessage() . "\n";
            }
        } else if ($use_hashtags == 0 && count($target_usernames) > 0) {

            $throttle_count = 0;

            try {
                foreach ($target_usernames as $target_username) {

                    echo "[" . $insta_username . "] using target username: " . $target_username->target_username . "\n";

                    $username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
                    $user_follower_response = $instagram->people->getFollowers($username_id);
                    #$user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId(trim($target_username->target_username)));
                    $users_to_follow = $user_follower_response->users;

                    foreach ($users_to_follow as $user_to_follow) {
                        if ($user_to_follow->is_private) {
                            echo "[" . $insta_username . "] [$user_to_follow->username] is private.\n";
                            continue;
                        } else if ($user_to_follow->has_anonymous_profile_picture) {
                            echo "[" . $insta_username . "] [$user_to_follow->username] has no profile pic.\n";
                            continue;
                        } else {
                            if (InstagramProfileFollowLog::where('insta_username', $insta_username)->where('follower_id', $user_to_follow->pk)->count() > 0) {

                                //user exists aka duplicate
                                echo "[" . $insta_username . "] has followed [$user_to_follow->username] before.\n";
                                continue;
                            } else {
                                try {
                                    $throttle_count++;
                                    #$user_info = $instagram->getUserInfoById($user_to_follow->pk);
                                    $user_info = $instagram->people->getInfoById($user_to_follow->pk);
                                    $user_to_follow = $user_info->user;

                                    if ($user_to_follow->media_count == 0) {
                                        echo "[$insta_username] [$user_to_follow->username] does not meet requirement: > 0 photos \n";
                                        continue;
                                    }
                                    if ($follow_min_follower != 0 && $user_to_follow->follower_count < $follow_min_follower) {
                                        echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] < [$follow_min_follower] \n";
                                        continue;
                                    }
                                    if ($follow_max_follower != 0 && $user_to_follow->follower_count > $follow_max_follower) {
                                        echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] > [$follow_max_follower] \n";
                                        continue;
                                    }

                                    $follow_resp = $instagram->people->follow($user_to_follow->pk);

                                    if ($follow_resp->friendship_status->following == true) {

                                        $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
                                        $ig_profile->follow_quota = $ig_profile->follow_quota - 1;

                                        if ($ig_profile->save()) {
                                            echo "[$insta_username] TARGET USERNAME added $delay minutes of delay & new follow quota = " . $ig_profile->follow_quota;
                                        }

                                        $new_follow_log = new InstagramProfileFollowLog;
                                        $new_follow_log->insta_username = $insta_username;
                                        $new_follow_log->follower_username = $user_to_follow->username;
                                        $new_follow_log->follower_id = $user_to_follow->pk;
                                        $new_follow_log->log = serialize($follow_resp);
                                        $new_follow_log->follow_success = 1;
                                        if ($new_follow_log->save()) {
                                            echo "[$insta_username] added new follow log.";
                                        }

                                        echo "[$insta_username] follow cycle: " . $follow_cycle . "\n";

                                        $followed_logs = InstagramProfileFollowLog::where('insta_username', $insta_username)
                                                ->where('follow', 1)
                                                ->where('unfollowed', 0)
                                                ->get();

                                        $followed_count = count($followed_logs);
                                        echo "[$insta_username] number of follows: " . $followed_count . "\n";

                                        if ($followed_count >= $follow_cycle) {
                                            $ig_profile->unfollow = 1;
                                            $ig_profile->save();
                                        }

                                        $followed = 1;
                                        echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                        break;
                                    } else {
                                        if ($follow_resp->friendship_status->is_private) {
                                            continue;
                                        } else if ($follow_resp->friendship_status->following == false) {
                                            $ig_profile->next_follow_time = \Carbon\Carbon::now()->addSeconds(180)->toDateTimeString();
                                            $ig_profile->follow_quota = $ig_profile->follow_quota + 1;
                                            $ig_profile->save();
                                        }
                                        continue;
                                    }
                                } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                    echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";

                                    if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                                        $ig_profile->feedback_required = 1;
                                        $ig_profile->save();
                                        $followed = 1;
                                        break;
                                    } else if (stripos(trim($request_ex->getMessage()), "Feedback") !== false) {
                                        $ig_profile->feedback_required = 1;
                                        $ig_profile->auto_follow_ban = 1;
                                        $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(6)->toDateTimeString();
                                        $ig_profile->save();
                                        $followed = 1;
                                        break;
                                    }

                                    if (stripos(trim($request_ex->getMessage()), "Throttled by Instagram because of too many API requests.") !== false) {
                                        $ig_profile->feedback_required = 1;
                                        $ig_profile->save();
                                        $followed = 1;
                                        exit();
                                    }
                                    continue;
                                } catch (Exception $ex) {
                                    echo "[" . $insta_username . "] username-error: " . $ex->getMessage() . "\n";
                                    continue;
                                }
                            }
                        }
                    }

                    if ($throttle_count == $throttle_limit) {
                        break;
                    }

                    if ($followed == 1) {
                        break;
                    }
                }
            } catch (Exception $ex) {
                echo "[" . $insta_username . "] username-error: " . $ex->getMessage() . "\n";
            }
        } else if ($use_hashtags == 0 && count($target_usernames) == 0 && count($target_hashtags) == 0) {
            $throttle_count = 0;
            try {
                if ($this->niche == 0) {
                    exit();
                } else {
                    $target_usernames = Niche::find($this->niche)->targetUsernames();
                    if (count($target_usernames) > 0) {
                        foreach ($target_usernames as $target_username) {
                            echo "[" . $insta_username . "] using target username: " . $target_username->target_username . "\n";
                            $user_follower_response = NULL;

                            try {
                                $username_id = $instagram->people->getUserIdForName(trim($target_username->target_username));
                                $user_follower_response = $instagram->people->getFollowers($username_id);
                                #$user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId(trim($target_username->target_username)));
                            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                                $target_username->delete();
                                continue;
                            }

                            $users_to_follow = $user_follower_response->users;

                            foreach ($users_to_follow as $user_to_follow) {
                                if ($user_to_follow->is_private) {
                                    echo "[" . $insta_username . "] [$user_to_follow->username] is private.\n";
                                    continue;
                                } else if ($user_to_follow->has_anonymous_profile_picture) {
                                    echo "[" . $insta_username . "] [$user_to_follow->username] has no profile pic.\n";
                                    continue;
                                } else {
                                    if (InstagramProfileFollowLog::where('insta_username', $insta_username)->where('follower_id', $user_to_follow->pk)->count() > 0) {
                                        //user exists aka duplicate
                                        echo "[" . $insta_username . "] has followed [$user_to_follow->username] before.\n";
                                        continue;
                                    } else {
                                        try {
                                            $throttle_count++;
                                            $user_info = $instagram->people->getInfoById($user_to_follow->pk);
                                            $user_to_follow = $user_info->user;

                                            if ($user_to_follow->media_count == 0) {
                                                echo "[$insta_username] [$user_to_follow->username] does not meet requirement: > 0 photos \n";
                                                continue;
                                            }
                                            if ($follow_min_follower != 0 && $user_to_follow->follower_count < $follow_min_follower) {
                                                echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] < [$follow_min_follower] \n";
                                                continue;
                                            }
                                            if ($follow_max_follower != 0 && $user_to_follow->follower_count > $follow_max_follower) {
                                                echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] > [$follow_max_follower] \n";
                                                continue;
                                            }

                                            $follow_resp = $instagram->people->follow($user_to_follow->pk);

                                            if ($follow_resp->friendship_status->following == true) {

                                                $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
                                                $ig_profile->follow_quota = $ig_profile->follow_quota - 1;
                                                if ($ig_profile->save()) {
                                                    echo "[$insta_username] NICHE added $delay minutes of delay & new follow quota = " . $ig_profile->follow_quota;
                                                }

                                                $new_follow_log = new InstagramProfileFollowLog;
                                                $new_follow_log->insta_username = $insta_username;
                                                $new_follow_log->follower_username = $user_to_follow->username;
                                                $new_follow_log->follower_id = $user_to_follow->pk;
                                                $new_follow_log->log = serialize($follow_resp);
                                                $new_follow_log->follow_success = 1;
                                                if ($new_follow_log->save()) {
                                                    echo "[$insta_username] added new follow log.";
                                                }

                                                echo "[$insta_username] follow cycle: " . $follow_cycle . "\n";

                                                $followed_logs = InstagramProfileFollowLog::where('insta_username', $insta_username)
                                                        ->where('follow', 1)
                                                        ->where('unfollowed', 0)
                                                        ->get();

                                                $followed_count = count($followed_logs);
                                                echo "[$insta_username] number of follows: " . $followed_count . "\n";

                                                if ($followed_count >= $follow_cycle) {
                                                    $ig_profile->unfollow = 1;
                                                    $ig_profile->save();
                                                }

                                                $followed = 1;
                                                echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                                break;
                                            } else {
                                                if ($follow_resp->friendship_status->is_private) {
                                                    continue;
                                                } else if ($follow_resp->friendship_status->following == false) {
                                                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addSeconds(180)->toDateTimeString();
                                                    $ig_profile->follow_quota = $ig_profile->follow_quota + 1;
                                                    $ig_profile->save();
                                                }
                                                continue;
                                            }
                                        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                            echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";

                                            if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                                                $ig_profile->feedback_required = 1;
                                                $ig_profile->save();
                                                $followed = 1;
                                                break;
                                            } else if (stripos(trim($request_ex->getMessage()), "Feedback") !== false) {
                                                $ig_profile->feedback_required = 1;
                                                $ig_profile->auto_follow_ban = 1;
                                                $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(6)->toDateTimeString();
                                                $ig_profile->save();
                                                $followed = 1;
                                                break;
                                            }

                                            if (stripos(trim($request_ex->getMessage()), "Throttled by Instagram because of too many API requests.") !== false) {
                                                $ig_profile->feedback_required = 1;
                                                $ig_profile->save();
                                                $followed = 1;
                                                exit();
                                            }

                                            continue;
                                        } catch (Exception $ex) {
                                            echo "[" . $insta_username . "] username-error: " . $ex->getMessage() . "\n";
                                            continue;
                                        }
                                    }
                                }
                            }

                            if ($throttle_count == $throttle_limit) {
                                break;
                            }

                            if ($followed == 1) {
                                break;
                            }
                        }
                    } else {
                        $throttle_count = 0;
                        $target_hashtags = Niche::find($this->niche)->targetHashtags();
                        try {
                            foreach ($target_hashtags as $target_hashtag) {

                                echo "[" . $insta_username . "] using hashtag: " . $target_hashtag->hashtag . "\n";
                                #$hashtag_feed = $instagram->getHashtagFeed(trim($target_hashtag->hashtag));
                                $hashtag_feed = $instagram->hashtag->getFeed(trim($target_hashtag->hashtag));

                                foreach ($hashtag_feed->items as $item) {

                                    $throttle_count++;

                                    if ($throttle_count == $throttle_limit) {
                                        break;
                                    }

                                    $user_to_follow = $item->user;

                                    if (InstagramProfileFollowLog::where('insta_username', $insta_username)->where('follower_id', $user_to_follow->pk)->count() > 0) {
                                        //user exists aka duplicate
                                        echo "[" . $insta_username . "] has followed [$user_to_follow->username] before.\n";
                                        continue;
                                    } else {
                                        if ($user_to_follow->is_private) {
                                            echo "[" . $insta_username . "] [$user_to_follow->username] is private.\n";
                                            continue;
                                        } else if ($user_to_follow->has_anonymous_profile_picture) {
                                            echo "[" . $insta_username . "] [$user_to_follow->username] has no profile pic.\n";
                                            continue;
                                        } else {
                                            try {
                                                #$user_info = $instagram->getUserInfoById($user_to_follow->pk);
                                                $user_info = $instagram->people->getInfoById($user_to_follow->pk);
                                                $user_to_follow = $user_info->user;

                                                if ($user_to_follow->media_count == 0) {
                                                    echo "[$insta_username] [$user_to_follow->username] does not meet requirement: > 0 photos \n";
                                                    continue;
                                                }

                                                if ($follow_min_follower != 0 && $user_to_follow->follower_count < $follow_min_follower) {
                                                    echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] < [$follow_min_follower] \n";
                                                    continue;
                                                }

                                                if ($follow_max_follower != 0 && $user_to_follow->follower_count > $follow_max_follower) {
                                                    echo "[$insta_username] [$user_to_follow->username] does not meet requirement: [" . $user_to_follow->follower_count . "] > [$follow_max_follower] \n";
                                                    continue;
                                                }

                                                #$follow_resp = $instagram->follow($user_to_follow->pk);
                                                $follow_resp = $instagram->people->follow($user_to_follow->pk);

                                                if ($follow_resp->friendship_status->following == true) {

                                                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
                                                    $ig_profile->follow_quota = $ig_profile->follow_quota - 1;

                                                    if ($ig_profile->save()) {
                                                        echo "[$insta_username] HASHTAG added $delay minutes of delay & new follow quota = " . $ig_profile->follow_quota;
                                                    }

                                                    $new_follow_log = new InstagramProfileFollowLog;
                                                    $new_follow_log->insta_username = $insta_username;
                                                    $new_follow_log->follower_username = $user_to_follow->username;
                                                    $new_follow_log->follower_id = $user_to_follow->pk;
                                                    $new_follow_log->log = serialize($follow_resp);
                                                    $new_follow_log->follow_success = 1;
                                                    if ($new_follow_log->save()) {
                                                        echo "[$insta_username] added new follow log.";
                                                    }

                                                    echo "[$insta_username] follow cycle: " . $follow_cycle . "\n";

                                                    $followed_logs = InstagramProfileFollowLog::where('insta_username', $insta_username)
                                                            ->where('follow', 1)
                                                            ->where('unfollowed', 0)
                                                            ->get();

                                                    $followed_count = count($followed_logs);
                                                    echo "[$insta_username] number of follows: " . $followed_count . "\n";

                                                    if ($followed_count >= $follow_cycle) {
                                                        $ig_profile->unfollow = 1;
                                                        $ig_profile->save();
                                                    }

                                                    $followed = 1;
                                                    echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                                    break;
                                                } else {
                                                    continue;
                                                }
                                            } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                                echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";

                                                if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                                                    $ig_profile->feedback_required = 1;
                                                    $ig_profile->save();
                                                    $followed = 1;
                                                    break;
                                                } else if (stripos(trim($request_ex->getMessage()), "Feedback") !== false) {
                                                    $ig_profile->feedback_required = 1;
                                                    $ig_profile->auto_follow_ban = 1;
                                                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(6)->toDateTimeString();
                                                    $ig_profile->save();
                                                    $followed = 1;
                                                    break;
                                                }

                                                if (stripos(trim($request_ex->getMessage()), "Throttled by Instagram because of too many API requests.") !== false) {
                                                    $ig_profile->feedback_required = 1;
                                                    $ig_profile->save();
                                                    $followed = 1;
                                                    break;
                                                }

                                                if (stripos(trim($request_ex->getMessage()), "Sorry, you're following the max limit of accounts. You'll need to unfollow some accounts to start following more.") !== false) {
                                                    $followed = 1;
                                                    break;
                                                }
                                                continue;
                                            }
                                        }
                                    }
                                }

                                if ($throttle_count == $throttle_limit) {
                                    break;
                                }

                                if ($followed == 1) {
                                    break;
                                }
                            }
                        } catch (Exception $ex) {
                            echo "[" . $insta_username . "] hashtag-error: " . $ex->getMessage() . "\n";
                        }
                    }
                    //put curly bracket here.
                }
            } catch (Exception $ex) {
                echo "[" . $insta_username . "] niche-error: " . $ex->getMessage() . "\n";
                if (stripos(trim($ex->getMessage()), "Throttled by Instagram because of too many API requests") !== false) {
                    $ig_profile->feedback_required = 1;
                    $ig_profile->save();
                    $followed = 1;
                    exit();
                }
            }
        }
    }

}
