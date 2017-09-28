<?php

namespace App;

use App\InstagramProfileFollowLog;
use App\Niche;
use App\InstagramHelper;

class InteractionFollowHelper {

    public static function setSpeedDelay($speed) {
        $delay = 5;
        switch ($speed) {
            case 'Fast':
                $delay = 2;
                break;
            case 'Medium':
                $delay = 3;
                break;
            case 'Slow':
                $delay = 5;
                break;
            case 'Ultra Fast':
                $delay = 0;
                break;
            case 'weikian_':
                $delay = 0;
                break;
            default:
                $delay = 5;
                break;
        }
        $final_delay = rand($delay, $delay + 2);
        return $final_delay;
    }

    public static function setFollowMode(InstagramProfile $ig_profile) {
        //0 for follow, 1 for unfollow, 2 for forced unfollow
        $follow_mode = 0;

        //Get number of profiles that have not been unfollowed.
        $followed_count = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->where('follow', 1)
                ->where('unfollowed', 0)
                ->count();

        echo "[" . $ig_profile->insta_username . "] number of follows: " . $followed_count . "\n";

        if ($ig_profile->auto_follow === 1 && $ig_profile->auto_unfollow === 1) {
            if ($ig_profile->unfollow === 1) {
                //If profile is on unfollowing cycle:
                //If there are profile(s) to unfollow, set mode to 1.
                if ($followed_count > 0) {
                    $follow_mode = 1;
                } else {
                    //If there are no profiles to unfollow, update the user to following cycle.
                    $ig_profile->unfollow = 0;
                    $ig_profile->save();
                }
            } else if ($ig_profile->unfollow === 0) {
                //If profile is on following cycle:
                //Check if number of profiles unfollowed is more than or equal to the follow_cycle.
                $random_var = rand(5, 10);
                $follow_cycle = $ig_profile->follow_cycle + $random_var;
                if ($followed_count >= $follow_cycle) {
                    $ig_profile->unfollow = 1;
                    $ig_profile->save();
                    $follow_mode = 1;
                }
            }
        } else if ($ig_profile->auto_follow === 0 && $ig_profile->auto_unfollow === 1) {

            if ($followed_count > 0) {
                $follow_mode = 1;
            } else if ($followed_count === 0) {
                $follow_mode = 2;
            }

            $ig_profile->unfollow = 1;
            $ig_profile->save();
        }

        if ($follow_mode > 0) {
            echo "[" . $ig_profile->insta_username . "] will be unfollowing this round.\n";
        } else {
            echo "[" . $ig_profile->insta_username . "] will be following this round.\n";
        }

        return $follow_mode;
    }

    public static function unfollow($ig_profile, $instagram, $user_to_unfollow) {
        //0 for unfollow failed
        //1 for unfollow succeeded
        //2 for unfollow false-succeeded (i.e. Marked as unfollowed)
        $delay = InteractionFollowHelper::setSpeedDelay($ig_profile->speed);
        if ($ig_profile->unfollow_unfollowed == 1) { //only unfollow users that unfollowed me (i.e. followed_by = 0)
            $friendship = $instagram->people->getFriendship($user_to_unfollow->follower_id);
            if ($friendship->followed_by == true) {
                echo "[" . $ig_profile->insta_username . "] is followed by "
                . $user_to_unfollow->follower_username . "\n";
                $user_to_unfollow->unfollowed = 1;
                $user_to_unfollow->date_unfollowed = \Carbon\Carbon::now();
                if ($user_to_unfollow->save()) {
                    echo "[" . $ig_profile->insta_username . "] marked as unfollowed & updated log as NULL: [" .
                    $user_to_unfollow->log_id . "] [" . $user_to_unfollow->follower_username . "]\n\n";
                    return 2;
                }
            } else {
                return InteractionFollowHelper::unfollow_($ig_profile, $instagram, $user_to_unfollow, $delay);
            }
        } else {
            return InteractionFollowHelper::unfollow_($ig_profile, $instagram, $user_to_unfollow, $delay);
        }
    }

    private static function unfollow_($ig_profile, $instagram, $user_to_unfollow, $delay) {
        //User didn't follow back, execute unfollow
        try {
            $resp = $instagram->people->unfollow($user_to_unfollow->follower_id);
            if ($resp->friendship_status->following === false) {
                $user_to_unfollow->unfollowed = 1;
                $user_to_unfollow->date_unfollowed = \Carbon\Carbon::now();
                if ($user_to_unfollow->save()) {
                    echo "[" . $ig_profile->insta_username . "] "
                    . "marked as unfollowed & updated log: "
                    . $user_to_unfollow->log_id . " [" . $user_to_unfollow->follower_username . "\n\n";
                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addMinutes($delay)->toDateTimeString();
                    $ig_profile->unfollow_quota = $ig_profile->unfollow_quota - 1;
                    if ($ig_profile->save()) {
                        echo "[" . $ig_profile->insta_username . "] added $delay minutes of delay & new unfollow quota = " . $ig_profile->unfollow_quota;
                        return 1;
                    }
                }
            }
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $checkpoint_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $network_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $endpoint_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $incorrectpw_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $feedback_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $emptyresponse_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $acctdisabled_ex, $user_to_unfollow);
        } catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $throttled_ex, $user_to_unfollow);
        }
        return 0;
    }
    
    

    public static function unfollowUserIsEmpty($ig_profile, $instagram) {
        $insta_username = $ig_profile->insta_username;
        echo "[" . $insta_username . "] has no follows to unfollow.\n\n";

        #forced unfollow
        if ($auto_unfollow == 1 && $auto_follow == 0) {
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
    
    public static function handleUnfollowInstagramException($ex, $ig_profile, $user_to_unfollow) {
        $this->unfollow_quota = 0;
        $ig_username = $ig_profile->insta_username;
        dump($ex);
        if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== false) {
            $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] has next_follow_time shifted forward to " . \Carbon\Carbon::now()->addHours(2)->toDateTimeString() . "\n";
            exit;
        } else if ($ex instanceof \InstagramAPI\Exception\FeedbackRequiredException) {
            if ($ex->hasResponse()) {
                $feedback_required_response = $ex->getResponse();
                if (strpos($feedback_required_response->fullResponse->feedback_message, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== false) {
                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(4);
                    $ig_profile->auto_unfollow_ban = 1;
                    $ig_profile->auto_unfollow_ban_time = \Carbon\Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] was blocked & has next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(2)->toDateTimeString() . "\n";
                    exit;
                } else if (strpos($feedback_required_response->fullResponse->feedback_message, 'It looks like your profile contains a link that is not allowed') !== false) {
                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(1);
                    $ig_profile->invalid_proxy = 1;
                    $ig_profile->save();
                    echo "\n[$ig_username] has invalid proxy & next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    exit;
                } else if (strpos($feedback_required_response->fullResponse->feedback_message, 'It looks like you were misusing this feature by going too fast') !== false) {
                    $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(4);
                    $ig_profile->auto_unfollow_ban = 1;
                    $ig_profile->auto_unfollow_ban_time = \Carbon\Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] is going too fast & next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    exit;
                }
            }
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof \InstagramAPI\Exception\CheckpointRequiredException) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof \InstagramAPI\Exception\NetworkException) {
            
        } else if ($ex instanceof \InstagramAPI\Exception\EndpointException) {
            if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->error_msg = $ex->getMessage();
            } else if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->invalid_user = 1;
            } else if (stripos(trim($ex->getMessage()), "Requested resource does not exist.") !== false) {
                $user_to_unfollow->unfollowed = 1;
                $user_to_unfollow->save();
                exit();
            }
        } else if ($ex instanceof \InstagramAPI\Exception\IncorrectPasswordException) {
            $ig_profile->incorrect_pw = 1;
        } else if ($ex instanceof \InstagramAPI\Exception\AccountDisabledException) {
            $ig_profile->account_disabled = 1;
        } else if ($ex instanceof \InstagramAPI\Exception\ThrottledException) {
            $ig_profile->next_follow_time = \Carbon\Carbon::now()->addHours(2);
            $ig_profile->auto_unfollow_ban = 1;
            $ig_profile->auto_unfollow_ban_time = \Carbon\Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] got throttled & next_like_time shifted forward to " . \Carbon\Carbon::now()->addHours(1)->toDateTimeString() . "\n";
            exit;
        }

        if ($ex->hasResponse()) {
            dump($ex->getResponse());
        } else {
            echo("\nThis exception has no response.\n");
        }

        $ig_profile->save();
    }
    
    
    public static function unFollowUsers($ig_profile, $instagram, $users_to_unfollow) {
        $insta_username = $ig_profile->insta_username;
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

    public static function useHashtagsIsOne($ig_profile, $instagram, $target_hashtags) {
        $throttle_count = 0;
        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;
        $insta_username = $ig_profile->insta_username;

        try {
            foreach ($target_hashtags as $target_hashtag) {
                $instagram->login($ig_username, $ig_password);
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
                                } else if (stripos(trim($request_ex->getMessage()), "Feedback required.") !== false) {
                                    $ig_profile->feedback_required = 1;
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

    public static function useHashtagsIsZero($ig_profile, $instagram, $target_hashtags) {
        $throttle_count = 0;
        $insta_username = $ig_profile->insta_username;
        try {
            foreach ($target_usernames as $target_username) {

                if (trim($target_username->target_username) === "") {
                    continue;
                }

                echo "[" . $insta_username . "] using target username: " . $target_username->target_username . "\n";

                $username_id = InstagramHelper::getUserIdForName($instagram, $target_username);
                if ($username_id === NULL) {
                    continue;
                }

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
                                if (stripos(trim($request_ex->getMessage()), "Sorry, you're following the max limit of accounts. You'll need to unfollow some accounts to start following more.") !== false) {
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
    }

    public static function allIsZero($ig_profile, $instagram) {
        $insta_username = $ig_profile->insta_username;
        $throttle_count = 0;
        try {
            if ($niche == 0) {
                exit();
            } else {
                $target_usernames = Niche::find($niche)->targetUsernames();
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
                    $target_hashtags = Niche::find($niche)->targetHashtags();
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

    public static function handleInstagramException($ig_profile, $ex, $current_log_id) {
        $insta_username = $ig_profile->insta_username;
        if ($ex instanceof \InstagramAPI\Exception\CheckpointRequiredException) {
            echo "[" . $insta_username . "] checkpoint_ex: " . $ex->getMessage() . "\n";
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
        } else if ($ex instanceof \InstagramAPI\Exception\NetworkException) {
            echo "[" . $insta_username . "] network_ex: " . $ex->getMessage() . "\n";
        } else if ($ex instanceof \InstagramAPI\Exception\EndpointException) {
            echo "[" . $insta_username . "] endpoint_ex: " . $ex->getMessage() . "\n";

            if (stripos(trim($ex->getMessage()), "Requested resource does not exist.") !== false) {
                $unfollow_log_to_update = InstagramProfileFollowLog::find($current_log_id);
                $unfollow_log_to_update->unfollowed = 1;
                $unfollow_log_to_update->save();
                $followed = 1;
                exit();
            }
        } else if ($ex instanceof \InstagramAPI\Exception\IncorrectPasswordException) {
            echo "[" . $insta_username . "] incorrectpw_ex: " . $$ex->getMessage() . "\n";
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
        } else if ($ex instanceof \InstagramAPI\Exception\FeedbackRequiredException) {
            echo "[" . $insta_username . "] feedback_ex: " . $ex->getMessage() . "\n";
        } else if ($ex instanceof \InstagramAPI\Exception\EmptyResponseException) {
            echo "[" . $insta_username . "] emptyresponse_ex: " . $ex->getMessage() . "\n";
            if (stripos(trim($emptyresponse_ex->getMessage()), "No response from server. Either a connection or configuration error") !== false) {
                $unfollow_log_to_update = InstagramProfileFollowLog::find($current_log_id);
                $unfollow_log_to_update->unfollowed = 1;
                $unfollow_log_to_update->save();
                $followed = 1;
                exit();
            }
        } else if ($ex instanceof \InstagramAPI\Exception\ThrottledException) {
            echo "[" . $insta_username . "] throttled_ex: " . $ex->getMessage() . "\n";
        } else if ($ex instanceof \InstagramAPI\Exception\RequestException) {
            echo "[" . $insta_username . "] request_ex: " . $ex->getMessage() . "\n";
            if (stripos(trim($ex->getMessage()), "feedback_required") !== false) {
                $ig_profile->feedback_required = 1;
                $ig_profile->save();
                $followed = 1;
                exit();
            }
        }
    }

}
