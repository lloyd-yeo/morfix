<?php

namespace App;

use Carbon\Carbon;
use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\CheckpointRequiredException;
use InstagramAPI\Exception\EmptyResponseException;
use InstagramAPI\Exception\EndpointException;
use InstagramAPI\Exception\FeedbackRequiredException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Exception\ThrottledException;
use InstagramAPI\Instagram;
use InstagramAPI\Response\FriendshipResponse;
use InstagramAPI\Response\Model\User as InstagramUser;

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

        echo "[" . $ig_profile->insta_username . "] [" . $ig_profile->follow_cycle . "] number of follows: " . $followed_count . "\n";

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
                $follow_cycle = $ig_profile->follow_cycle;
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

    public static function isProfileValidForFollow(Instagram $instagram, InstagramProfile $ig_profile, InstagramUser $user_to_follow) {
        //Check by default that user is valid to even retrieve extra info.
        if ($user_to_follow->getIsPrivate()) {
            echo "[" . $ig_profile->insta_username . "] [" . $user_to_follow->getUsername() . "] is private.\n";
            return false;
        } else if ($user_to_follow->getHasAnonymousProfilePicture()) {
            echo "[" . $ig_profile->insta_username . "] [" . $user_to_follow->getUsername() . "] has no profile pic.\n";
            return false;
        } else if (InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                        ->where('follower_id', $user_to_follow->getPk())->count() > 0) {
            //user exists aka duplicate
            echo "[" . $ig_profile->insta_username . "] has followed [" . $user_to_follow->getUsername() . "] before.\n";
            return false;
        }
        //Get extra info to make sure it fits user's criteria
//        try {
            $user_info = $instagram->people->getInfoById($user_to_follow->getPk());
            $user_to_follow = $user_info->getUser();
            if ($user_to_follow->getMediaCount() == 0) {
                echo "[" . $ig_profile->insta_username . "] [" . $user_to_follow->getUsername() . "] does not meet requirement: > 0 photos \n";
                return false;
            }
            if ($ig_profile->follow_min_followers != 0 && $user_to_follow->getFollowerCount() < $ig_profile->follow_min_followers) {
                echo "[" . $ig_profile->insta_username . "] [" . $user_to_follow->getUsername() . "] does not meet requirement: [" . $user_to_follow->getFollowerCount() . "] < [" . $ig_profile->follow_min_followers . "] \n";
                return false;
            }
            if ($ig_profile->follow_max_followers != 0 && $user_to_follow->getFollowerCount() > $ig_profile->follow_max_followers) {
                echo "[" . $ig_profile->insta_username . "] [" . $user_to_follow->getUsername() . "] does not meet requirement: [" . $user_to_follow->getFollowerCount() . "] > [" . $ig_profile->follow_max_followers . "] \n";
                return false;
            }
//        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $checkpoint_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $network_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $endpoint_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $incorrectpw_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $feedback_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $emptyresponse_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $acctdisabled_ex);
//            return 2;
//        } catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
//            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $throttled_ex);
//            return 2;
//        }
        return true;
    }

    public static function unfollow(InstagramProfile $ig_profile, Instagram $instagram, InstagramProfileFollowLog $user_to_unfollow) {
        //0 for unfollow failed
        //1 for unfollow succeeded
        //2 for unfollow false-succeeded (i.e. Marked as unfollowed)
        try {
            $delay = InteractionFollowHelper::setSpeedDelay($ig_profile->speed);
            if ($ig_profile->unfollow_unfollowed == 1) { //only unfollow users that unfollowed me (i.e. followed_by = 0)
                $friendship = $instagram->people->getFriendship($user_to_unfollow->follower_id);
                if ($friendship->getFollowedBy() == true) {
                    echo "[" . $ig_profile->insta_username . "] is followed by "
                    . $user_to_unfollow->follower_username . "\n";
                    $user_to_unfollow->unfollowed = 1;
                    $user_to_unfollow->date_unfollowed = Carbon::now();
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
        } catch (\InstagramAPI\Exception\NotFoundException $notfound_ex) {
            $user_to_unfollow->unfollowed = 1;
            $user_to_unfollow->date_unfollowed = Carbon::now();
            if ($user_to_unfollow->save()) {
                echo "[" . $ig_profile->insta_username . "] marked as unfollowed & updated log as NULL: [" .
                $user_to_unfollow->log_id . "] [" . $user_to_unfollow->follower_username . "]\n\n";
                return 2;
            }
        }
    }

	/**
	 *
	 * This function is a wrapper for the original Follow method from the Instagram API to handle exceptions.
	 * returns 0 when the following has failed. (break)
	 * returns 1 when the $user_to_follow is successfully followed (break)
	 * returns 2 when the $user_to_follow is private (continue)
	 *
	 * @param Instagram $instagram The Instagram API instance with the user logged in.
	 * @param \App\InstagramProfile $ig_profile The Instagram Profile of the user.
	 * @param InstagramUser $user_to_follow The Instagram API User Model to follow.
	 * @return int
	 */
	public static function follow(Instagram $instagram, InstagramProfile $ig_profile, InstagramUser $user_to_follow) {
        $delay = InteractionFollowHelper::setSpeedDelay($ig_profile->speed);
        try {
            $follow_resp = $instagram->people->follow($user_to_follow->getPk());
            dump($follow_resp);
            return InteractionFollowHelper::follow_($follow_resp, $ig_profile, $user_to_follow, $delay);
        } catch (CheckpointRequiredException $checkpoint_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $checkpoint_ex);
        } catch (NetworkException $network_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $network_ex);
        } catch (EndpointException $endpoint_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $endpoint_ex);
        } catch (IncorrectPasswordException $incorrectpw_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $incorrectpw_ex);
        } catch (FeedbackRequiredException $feedback_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $feedback_ex);
        } catch (EmptyResponseException $emptyresponse_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $emptyresponse_ex);
        } catch (AccountDisabledException $acctdisabled_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $acctdisabled_ex);
        } catch (ThrottledException $throttled_ex) {
            InteractionFollowHelper::handleFollowInstagramException($ig_profile, $throttled_ex);
        }
        return 0;
    }

    private static function follow_(FriendshipResponse $follow_resp, InstagramProfile $ig_profile,
                                    InstagramUser $user_to_follow, $delay) {
        if ($follow_resp->getFriendshipStatus()->getFollowing() == true) {
            $ig_profile->next_follow_time = Carbon::now()->addMinutes($delay);
            $ig_profile->follow_quota = $ig_profile->follow_quota - 1;

            if ($ig_profile->save()) {
                echo "[" . $ig_profile->insta_username . "] TARGET USERNAME added $delay minutes of delay & new follow quota = " . $ig_profile->follow_quota . "\n";
            }

            $new_follow_log = new InstagramProfileFollowLog;
            $new_follow_log->insta_username = $ig_profile->insta_username;
            $new_follow_log->follower_username = $user_to_follow->getUsername();
            $new_follow_log->follower_id = $user_to_follow->getPk();
            $new_follow_log->log = serialize($follow_resp);
            $new_follow_log->follow_success = 1;
            if ($new_follow_log->save()) {
                echo "[" . $ig_profile->insta_username . "] added new follow log.\n";
            }
            echo "[" . $ig_profile->insta_username . "] followed [" . $user_to_follow->getUsername() . "].\n";
            return 1;
        } else {
            if ($follow_resp->getFriendshipStatus()->getIsPrivate()) {
                return 2;
            } else if ($follow_resp->getFriendshipStatus()->getFollowing() == false) {
                $ig_profile->next_follow_time = Carbon::now()->addSeconds(180)->toDateTimeString();
                $ig_profile->follow_quota = $ig_profile->follow_quota + 1;
                $ig_profile->save();
                return 0;
            }
        }
    }

    private static function unfollow_($ig_profile, $instagram, $user_to_unfollow, $delay) {
        //User didn't follow back, execute unfollow
        try {
            $resp = $instagram->people->unfollow($user_to_unfollow->follower_id);
            if ($resp->getFriendshipStatus()->getFollowing() === false) {
                $user_to_unfollow->unfollowed = 1;
                $user_to_unfollow->date_unfollowed = Carbon::now();
                if ($user_to_unfollow->save()) {
                    echo "[" . $ig_profile->insta_username . "] "
                    . "marked as unfollowed & updated log: "
                    . $user_to_unfollow->log_id . " [" . $user_to_unfollow->follower_username . "]\n";
                    $ig_profile->next_follow_time = Carbon::now()->addMinutes($delay)->toDateTimeString();
                    $ig_profile->unfollow_quota = $ig_profile->unfollow_quota - 1;
                    if ($ig_profile->save()) {
                        echo "[" . $ig_profile->insta_username . "] added $delay minutes of delay & new unfollow quota = " . $ig_profile->unfollow_quota . "\n\n";
                        return 1;
                    }
                }
            }
        } catch (CheckpointRequiredException $checkpoint_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $checkpoint_ex, $user_to_unfollow);
        } catch (NetworkException $network_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $network_ex, $user_to_unfollow);
        } catch (EndpointException $endpoint_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $endpoint_ex, $user_to_unfollow);
        } catch (IncorrectPasswordException $incorrectpw_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $incorrectpw_ex, $user_to_unfollow);
        } catch (FeedbackRequiredException $feedback_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $feedback_ex, $user_to_unfollow);
        } catch (EmptyResponseException $emptyresponse_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $emptyresponse_ex, $user_to_unfollow);
        } catch (AccountDisabledException $acctdisabled_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $acctdisabled_ex, $user_to_unfollow);
        } catch (ThrottledException $throttled_ex) {
            InteractionFollowHelper::handleUnfollowInstagramException($ig_profile, $throttled_ex, $user_to_unfollow);
        }
        return 0;
    }

    public static function handleUnfollowInstagramException($ig_profile, $ex, $user_to_unfollow) {
        $ig_username = $ig_profile->insta_username;
        dump($ex);
        if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== false) {
            $ig_profile->next_follow_time = Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] has next_follow_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "\n";
            exit;
        } else if ($ex instanceof FeedbackRequiredException) {
            if ($ex->hasResponse()) {
	            $feedback_response = $ex->getResponse()->asArray();
	            $feedback_msg = $feedback_response['feedback_message'];
                if (strpos($feedback_msg, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== false) {
                    $ig_profile->next_follow_time = Carbon::now()->addHours(4);
                    $ig_profile->auto_unfollow_ban = 1;
                    $ig_profile->auto_unfollow_ban_time = Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] was blocked & has next_like_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "\n";
                    exit;
                } else if (strpos($feedback_msg, 'It looks like your profile contains a link that is not allowed') !== false) {
                    $ig_profile->next_follow_time = Carbon::now()->addHours(1);
                    $ig_profile->invalid_proxy = 1;
                    $ig_profile->save();
                    echo "\n[$ig_username] has invalid proxy & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    exit;
                } else if (strpos($feedback_msg, 'It looks like you were misusing this feature by going too fast') !== false) {
                    $ig_profile->next_follow_time = Carbon::now()->addHours(4);
                    $ig_profile->auto_unfollow_ban = 1;
                    $ig_profile->auto_unfollow_ban_time = Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] is going too fast & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    exit;
                }
            }
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof CheckpointRequiredException) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof NetworkException) {
            $ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
        } else if ($ex instanceof EndpointException) {
            if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->error_msg = $ex->getMessage();
            } else if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->invalid_user = 1;
            } else if (stripos(trim($ex->getMessage()), "Requested resource does not exist.") !== false) {
                $user_to_unfollow->unfollowed = 1;
                $user_to_unfollow->save();
                exit();
            }
        } else if ($ex instanceof IncorrectPasswordException) {
            $ig_profile->incorrect_pw = 1;
        } else if ($ex instanceof AccountDisabledException) {
            $ig_profile->account_disabled = 1;
        } else if ($ex instanceof ThrottledException) {
            $ig_profile->next_follow_time = Carbon::now()->addHours(2);
            $ig_profile->auto_unfollow_ban = 1;
            $ig_profile->auto_unfollow_ban_time = Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] got throttled & next_like_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";
            exit;
        }

        if ($ex->hasResponse()) {
            dump($ex->getResponse());
        } else {
            echo("\nThis exception has no response.\n");
        }

        $ig_profile->save();
    }

    public static function handleFollowInstagramException($ig_profile, $ex) {
        echo "[" . $ig_profile->insta_username . "] handling exception...\n";
        $ig_username = $ig_profile->insta_username;
        if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== false) {
            $ig_profile->next_follow_time = Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] has next_follow_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "\n";
            return;
        } else if ($ex instanceof FeedbackRequiredException) {
            if ($ex->hasResponse()) {
	            $feedback_response = $ex->getResponse()->asArray();
	            $feedback_msg = $feedback_response['feedback_message'];
                if (strpos($feedback_msg, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== false) {
                    $ig_profile->next_follow_time = Carbon::now()->addHours(4);
                    $ig_profile->auto_follow_ban = 1;
                    $ig_profile->auto_follow_ban_time = Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] was blocked & has next_follow_time shifted forward to " . Carbon::now()->addHours(2)->toDateTimeString() . "\n";
                    return;
                } else if (strpos($feedback_msg, 'It looks like your profile contains a link that is not allowed') !== false) {
                    $ig_profile->next_follow_time = Carbon::now()->addHours(1);
                    $ig_profile->invalid_proxy = 1;
                    $ig_profile->save();
                    echo "\n[$ig_username] has invalid proxy & next_follow_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    return;
                } else if (strpos($feedback_msg, 'It looks like you were misusing this feature by going too fast') !== false) {
                    $ig_profile->next_follow_time = Carbon::now()->addHours(4);
                    $ig_profile->auto_follow_ban = 1;
                    $ig_profile->auto_follow_ban_time = Carbon::now()->addHours(4);
                    $ig_profile->save();
                    echo "\n[$ig_username] is going too fast & next_follow_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    return;
                }
            }
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof CheckpointRequiredException) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof NetworkException) {
            $ig_profile->invalid_proxy = $ig_profile->invalid_proxy + 1;
        } else if ($ex instanceof EndpointException) {
            if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->error_msg = $ex->getMessage();
            } else if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->invalid_user = 1;
            }
        } else if ($ex instanceof IncorrectPasswordException) {
            $ig_profile->incorrect_pw = 1;
        } else if ($ex instanceof AccountDisabledException) {
            $ig_profile->account_disabled = 1;
        } else if ($ex instanceof ThrottledException) {
            $ig_profile->next_follow_time = Carbon::now()->addHours(2);
            $ig_profile->auto_follow_ban = 1;
            $ig_profile->auto_follow_ban_time = Carbon::now()->addHours(2);
            $ig_profile->save();
            echo "\n[$ig_username] got throttled & next_follow_time shifted forward to " . Carbon::now()->addHours(1)->toDateTimeString() . "\n";
            return;
        }

        if ($ex->hasResponse()) {
            dump($ex->getResponse());
        } else {
            echo("\nThis exception has no response.\n");
        }

        $ig_profile->save();
    }

}
