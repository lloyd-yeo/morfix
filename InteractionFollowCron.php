<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DB.php';
$start = microtime(true);
$specific_email = NULL;
$offset = $argv[1];
$num_result = $argv[2]; //this is the constant
if (isset($argv[3])) {
    $specific_email = $argv[3];
}
$emails = array();
$conn_get_user = getConnection($servername, $username, $password, $dbname);
$stmt_get_user = NULL;
if (is_null($specific_email)) {
    echo "Retrieving users off $offset $num_result\n\n";
    $stmt_get_user = $conn_get_user->prepare($get_premium_and_free_trial_users_sql);
    $stmt_get_user->bind_param("ii", $offset, $num_result);
    $stmt_get_user->execute();
    $stmt_get_user->store_result();
    $stmt_get_user->bind_result($email_);
    while ($stmt_get_user->fetch()) {
        $emails[$email_] = $email_;
    }
    $stmt_get_user->free_result();
    $stmt_get_user->close();
} else {
    echo "Retrieving users off $specific_email\n\n";
    $stmt_get_user = $conn_get_user->prepare($get_premium_and_free_trial_users_by_email_sql);
    $stmt_get_user->bind_param("s", $specific_email);
    $stmt_get_user->execute();
    $stmt_get_user->store_result();
    $stmt_get_user->bind_result($email_);
    while ($stmt_get_user->fetch()) {
        $emails[$email_] = $email_;
    }
    $stmt_get_user->free_result();
    $stmt_get_user->close();
}
$conn_get_user->close();

foreach ($emails as $email) {
    $insta_profiles = array();
    $conn_get_profiles = getConnection($servername, $username, $password, $dbname);
    $stmt_get_profile = $conn_get_profiles->prepare($get_follow_profile_sql);
    $stmt_get_profile->bind_param("s", $email);
    $stmt_get_profile->execute();
    $stmt_get_profile->store_result();
    $stmt_get_profile->bind_result($insta_username, $insta_user_id, $insta_id, $insta_pw, $niche, $next_follow_time, $niche_target_counter, $unfollow, $auto_interaction_ban, $auto_interaction_ban_time, $follow_cycle, $auto_unfollow, $auto_follow, $auto_follow_ban, $auto_follow_ban_time, $follow_unfollow_delay, $speed, $follow_min_follower, $follow_max_follower, $unfollow_unfollowed, $daily_follow_quota, $daily_unfollow_quota, $proxy);
    while ($stmt_get_profile->fetch()) {
        $insta_profiles[] = generateProfileArray($insta_username, $insta_user_id, $insta_id, $insta_pw, $niche, $next_follow_time, $niche_target_counter, $unfollow, $auto_interaction_ban, $auto_interaction_ban_time, $follow_cycle, $auto_unfollow, $auto_follow, $auto_follow_ban, $auto_follow_ban_time, $follow_unfollow_delay, $speed, $follow_min_follower, $follow_max_follower, $unfollow_unfollowed, $daily_follow_quota, $daily_unfollow_quota, $proxy);
    }
    $stmt_get_profile->free_result();
    $stmt_get_profile->close();
    $conn_get_profiles->close();

    foreach ($insta_profiles as $insta_profile) {

        $insta_username = $insta_profile['insta_username'];
        $insta_user_id = $insta_profile['insta_user_id'];
        $insta_id = $insta_profile['insta_id'];
        $insta_pw = $insta_profile['insta_pw'];
        $niche = $insta_profile['niche'];
        $next_follow_time = $insta_profile['next_follow_time'];
        $niche_target_counter = $insta_profile['niche_target_counter'];
        $unfollow = $insta_profile['unfollow'];
        $auto_interaction_ban = $insta_profile['auto_interaction_ban'];
        $auto_interaction_ban_time = $insta_profile['auto_interaction_ban_time'];
        $follow_cycle = $insta_profile['follow_cycle'];
        $auto_unfollow = $insta_profile['auto_unfollow'];
        $auto_follow = $insta_profile['auto_follow'];
        $auto_follow_ban = $insta_profile['auto_follow_ban'];
        $auto_follow_ban_time = $insta_profile['auto_follow_ban_time'];
        $follow_unfollow_delay = $insta_profile['follow_unfollow_delay'];
        $speed = $insta_profile['speed'];
        $follow_min_follower = $insta_profile['follow_min_follower'];
        $follow_max_follower = $insta_profile['follow_max_follower'];
        $unfollow_unfollowed = $insta_profile['unfollow_unfollowed'];
        $daily_follow_quota = $insta_profile['daily_follow_quota'];
        $daily_unfollow_quota = $insta_profile['daily_unfollow_quota'];
        $proxy = $insta_profile['proxy'];

        try {
            $target_username = "";
            $target_follow_username = "";
            $target_follow_id = "";
            $custom_target_defined = false;

            echo "[" . $insta_username . "] Niche: " . $niche . " Auto_Follow: " . $auto_follow . " Auto_Unfollow: " . $auto_unfollow . "\n";

            if ($unfollow == 1) {
                echo "[" . $insta_username . "] will be unfollowing this round.\n";
            } else {
                echo "[" . $insta_username . "] will be following this round.\n";
            }

            $follow_unfollow_delay = 5;
            if ($speed == "Fast") {
                $follow_unfollow_delay = 2;
            }
            if ($speed == "Medium") {
                $follow_unfollow_delay = 3;
            }
            if ($speed == "Slow") {
                $follow_unfollow_delay = 5;
            }
            if ($speed == "Ultra Fast") {
                $follow_unfollow_delay = 0;
            }

            $delay = rand($follow_unfollow_delay, $follow_unfollow_delay + 2); //randomize the delay to escape detection from IG.
            //go into unfollowing mode if user is entirely on unfollow OR on the unfollowing cycle.
            if (($auto_unfollow == 1 && $auto_follow == 0) || ($auto_follow == 1 && $auto_unfollow == 1 && $unfollow == 1)) {

                if ($daily_unfollow_quota < 1) {
                    echo "[" . $insta_username . "] has reached quota for unfollowing today.\n";
                    continue;
                }

                echo "[" . $insta_username . "] beginning unfollowing sequence.\n";

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

                if (is_null($proxy)) {
                    continue;
                } else {
                    $instagram->setProxy($proxy);
                }

                try {
                    $ig_username = $insta_username;
                    $ig_password = $insta_pw;
                    $instagram->setUser($ig_username, $ig_password);
                    $instagram->login();

                    $users_to_unfollow = array();

                    $conn_get_follows = getConnection($servername, $username, $password, $dbname);
                    $stmt_get_follows = $conn_get_follows->prepare($get_follows_sql);
                    $stmt_get_follows->bind_param("s", $ig_username);
                    $stmt_get_follows->execute();
                    $stmt_get_follows->store_result();
                    $stmt_get_follows->bind_result($log_id, $follower_username, $follower_id);
                    while ($stmt_get_follows->fetch()) {
                        $users_to_unfollow[] = array(
                            "log_id" => $log_id,
                            "follower_username" => $follower_username,
                            "follower_id" => $follower_id
                        );
                    }
                    $stmt_get_follows->free_result();
                    $stmt_get_follows->close();
                    $conn_get_follows->close();

                    if (count($users_to_unfollow) == 0) {
                        echo "[" . $insta_username . "] has no follows to unfollow.\n\n";
                        switchFollowCycle($insta_id, $insta_username, $servername, $username, $password, $dbname);
                    } else {
                        foreach ($users_to_unfollow as $user_to_unfollow) {
                            echo "[" . $insta_username . "] retrieved: " . $user_to_unfollow["follower_username"] . "\n";
                            $resp = $instagram->unfollow($user_to_unfollow["follower_id"]);
                            echo "[" . $insta_username . "] ";
                            var_dump($resp);
                            if ($resp->friendship_status->following === false) {
                                updateUnfollowLog($user_to_unfollow["log_id"], $insta_username, $servername, $username, $password, $dbname);
                                updateUserNextFollowTime($insta_username, $follow_unfollow_delay, "unfollow", $servername, $username, $password, $dbname);
                                break;
                            }
                        }
                    }
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                    echo "[" . $insta_username . "] " . $checkpoint_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                    echo "[" . $insta_username . "] " . $network_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    echo "[" . $insta_username . "] " . $endpoint_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    echo "[" . $insta_username . "] " . $incorrectpw_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                    echo "[" . $insta_username . "] " . $feedback_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                    echo "[" . $insta_username . "] " . $emptyresponse_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
                    echo "[" . $insta_username . "] " . $throttled_ex->getMessage() . "\n";
                } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                    echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
                }
            } else if ($unfollow == 0 && $auto_follow == 1) { //follow sequence
                if ($daily_follow_quota < 1) {
                    echo "[" . $insta_username . "] has reached quota for following today.\n";
                    continue;
                }

                echo "[" . $insta_username . "] beginning following sequence.\n";

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

                if (is_null($proxy)) {
                    continue;
                } else {
                    $instagram->setProxy($proxy);
                }

                $ig_username = $insta_username;
                $ig_password = $insta_pw;
                $instagram->setUser($ig_username, $ig_password);
                $instagram->login();

                //start with targeted usernames/hashtags
                $use_hashtags = rand(0, 1);
                $target_hashtags = NULL;
                $target_usernames = NULL;
                if ($use_hashtags == 1) {
                    $target_hashtags = getTargetedHashtagsByIgUsername($insta_username, $servername, $username, $password, $dbname);
                    if (count($target_hashtags) == 0) {
                        $target_usernames = getTargetedUsernamesByIgUsername($insta_username, $servername, $username, $password, $dbname);
                        $use_hashtags = 0;
                    }
                } else if ($use_hashtags == 0) {
                    $target_usernames = getTargetedUsernamesByIgUsername($insta_username, $servername, $username, $password, $dbname);
                    if (count($target_usernames) == 0) {
                        $target_hashtags = getTargetedHashtagsByIgUsername($insta_username, $servername, $username, $password, $dbname);
                        $use_hashtags = 1;
                    }
                }
                $followed = 0;
                $throttle_limit = 41;

                if ($use_hashtags == 1 && count($target_hashtags) > 0) {
                    $throttle_count = 0;
                    try {
                        foreach ($target_hashtags as $target_hashtag) {
                            echo "[" . $insta_username . "] using hashtag: " . $target_hashtag . "\n";
                            $hashtag_feed = $instagram->getHashtagFeed($target_hashtag);
                            foreach ($hashtag_feed->items as $item) {
                                $throttle_count++;
                                if ($throttle_count == $throttle_limit) {
                                    break;
                                }
                                $user_to_follow = $item->user;
                                if (checkUserFollowLogs($insta_username, $user_to_follow->pk, $servername, $username, $password, $dbname)) {
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
                                            $follow_resp = $instagram->follow($user_to_follow->pk);
                                            if ($follow_resp->friendship_status->following == true) {
                                                updateUserNextFollowTime($insta_username, $follow_unfollow_delay, "follow", $servername, $username, $password, $dbname);
                                                insertNewFollowLogEntry($insta_username, $user_to_follow->username, $user_to_follow->pk, serialize($follow_resp), $servername, $username, $password, $dbname);
                                                $followed = 1;
                                                echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                                break;
                                            } else {
                                                continue;
                                            }
                                        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                            echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
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
                            echo "[" . $insta_username . "] using target username: " . $target_username . "\n";

                            $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId($target_username));
                            $users_to_follow = $user_follower_response->users;
                            foreach ($users_to_follow as $user_to_follow) {
                                if ($user_to_follow->is_private) {
                                    echo "[" . $insta_username . "] [$user_to_follow->username] is private.\n";
                                    continue;
                                } else if ($user_to_follow->has_anonymous_profile_picture) {
                                    echo "[" . $insta_username . "] [$user_to_follow->username] has no profile pic.\n";
                                    continue;
                                } else {
                                    if (checkUserFollowLogs($insta_username, $user_to_follow->pk, $servername, $username, $password, $dbname)) {
                                        //user exists aka duplicate
                                        echo "[" . $insta_username . "] has followed [$user_to_follow->username] before.\n";
                                        continue;
                                    } else {
                                        try {
                                            $follow_resp = $instagram->follow($user_to_follow->pk);
                                            if ($follow_resp->friendship_status->following == true) {
                                                updateUserNextFollowTime($insta_username, $follow_unfollow_delay, "follow", $servername, $username, $password, $dbname);
                                                insertNewFollowLogEntry($insta_username, $user_to_follow->username, $user_to_follow->pk, serialize($follow_resp), $servername, $username, $password, $dbname);
                                                $followed = 1;
                                                echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                                break;
                                            } else {
                                                continue;
                                            }
                                        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                            echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
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
                    try {
                        if ($niche == 0) {
                            continue;
                        } else {
                            foreach (getNicheTargets($niche, $servername, $username, $password, $dbname) as $target_username) {

                                echo "[" . $insta_username . "] using NICHE target username: " . $target_username . "\n";

                                $user_follower_response = $instagram->getUserFollowers($instagram->getUsernameId($target_username));
                                $users_to_follow = $user_follower_response->users;
                                foreach ($users_to_follow as $user_to_follow) {
                                    if ($user_to_follow->is_private) {
                                        echo "[" . $insta_username . "] [$user_to_follow->username] is private.\n";
                                        continue;
                                    } else if ($user_to_follow->has_anonymous_profile_picture) {
                                        echo "[" . $insta_username . "] [$user_to_follow->username] has no profile pic.\n";
                                        continue;
                                    } else {
                                        if (checkUserFollowLogs($insta_username, $user_to_follow->pk, $servername, $username, $password, $dbname)) {
                                            //user exists aka duplicate
                                            echo "[" . $insta_username . "] has followed [$user_to_follow->username] before.\n";
                                            continue;
                                        } else {
                                            try {
                                                $follow_resp = $instagram->follow($user_to_follow->pk);
                                                if ($follow_resp->friendship_status->following == true) {
                                                    updateUserNextFollowTime($insta_username, $follow_unfollow_delay, "follow", $servername, $username, $password, $dbname);
                                                    insertNewFollowLogEntry($insta_username, $user_to_follow->username, $user_to_follow->pk, serialize($follow_resp), $servername, $username, $password, $dbname);
                                                    $followed = 1;
                                                    echo "[" . $insta_username . "] followed [$user_to_follow->username].\n";
                                                    break;
                                                } else {
                                                    continue;
                                                }
                                            } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                                                echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
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
                        }
                    } catch (Exception $ex) {
                        echo "[" . $insta_username . "] niche-error: " . $ex->getMessage() . "\n";
                    }
                }
            }
        } catch (Exception $ex) {
            echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
        }
    }
}

$time_elapsed_secs = microtime(true) - $start;
echo "\n" . $time_elapsed_secs;

//END OF SCRIPT

function generateProfileArray($insta_username, $insta_user_id, $insta_id, $insta_pw, $niche, $next_follow_time, $niche_target_counter, $unfollow, $auto_interaction_ban, $auto_interaction_ban_time, $follow_cycle, $auto_unfollow, $auto_follow, $auto_follow_ban, $auto_follow_ban_time, $follow_unfollow_delay, $speed, $follow_min_follower, $follow_max_follower, $unfollow_unfollowed, $daily_follow_quota, $daily_unfollow_quota, $proxy) {
    return array(
        "insta_username" => $insta_username,
        "insta_user_id" => $insta_user_id,
        "insta_id" => $insta_id,
        "insta_pw" => $insta_pw,
        "niche" => $niche,
        "next_follow_time" => $next_follow_time,
        "niche_target_counter" => $niche_target_counter,
        "unfollow" => $unfollow,
        "auto_interaction_ban" => $auto_interaction_ban,
        "auto_interaction_ban_time" => $auto_interaction_ban_time,
        "follow_cycle" => $follow_cycle,
        "auto_unfollow" => $auto_unfollow,
        "auto_follow" => $auto_follow,
        "auto_follow_ban" => $auto_follow_ban,
        "auto_follow_ban_time" => $auto_follow_ban_time,
        "follow_unfollow_delay" => $follow_unfollow_delay,
        "speed" => $speed,
        "follow_min_follower" => $follow_min_follower,
        "follow_max_follower" => $follow_max_follower,
        "unfollow_unfollowed" => $unfollow_unfollowed,
        "daily_follow_quota" => $daily_follow_quota,
        "daily_unfollow_quota" => $daily_unfollow_quota,
        "proxy" => $proxy
    );
}

function switchFollowCycle($insta_id, $insta_username, $servername, $username, $password, $dbname) {
    $conn_switch_follow_status = new mysqli($servername, $username, $password, $dbname);
    $conn_switch_follow_status->query("set names utf8mb4");
    $stmt_switch_follow = $conn_switch_follow_status->prepare("UPDATE user_insta_profile SET unfollow = 0 WHERE id = ?;");
    $stmt_switch_follow->bind_param("i", $insta_id);
    if ($stmt_switch_follow->execute()) {
        echo "[" . $insta_username . "] is following next round.\n\n";
    }
    $stmt_switch_follow->close();
    $conn_switch_follow_status->close();
}

function updateUnfollowLog($logid, $insta_username, $servername, $username, $password, $dbname) {
    $conn_ = new mysqli($servername, $username, $password, $dbname);
    $conn_->query("set names utf8mb4");
    $stmt_update_follow_log = $conn_->prepare("UPDATE user_insta_profile_follow_log SET unfollowed = 1, date_unfollowed = NOW() "
            . "WHERE log_id = ?;");
    $stmt_update_follow_log->bind_param("i", $logid);
    if ($stmt_update_follow_log->execute()) {
        echo "[" . $insta_username . "] marked as unfollowed & updated log: " . $logid . "\n\n";
    }
    $stmt_update_follow_log->close();
    $conn_->close();
}

function updateUserNextFollowTime($insta_username, $delay, $mode, $servername, $username, $password, $dbname) {
    if ($mode == "unfollow") {
        $conn_f = new mysqli($servername, $username, $password, $dbname);
        $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET next_follow_time = NOW() + INTERVAL $delay MINUTE, daily_unfollow_quota = daily_unfollow_quota - 1 WHERE insta_username = ?;");
        $stmt_update_user_profile->bind_param("s", $insta_username);
        $stmt_update_user_profile->execute();
        $stmt_update_user_profile->close();
        $conn_f->close();
    } else if ($mode == "follow") {
        $conn_f = new mysqli($servername, $username, $password, $dbname);
        $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET next_follow_time = NOW() + INTERVAL $delay MINUTE, daily_follow_quota = daily_follow_quota - 1 WHERE insta_username = ?;");
        $stmt_update_user_profile->bind_param("s", $insta_username);
        $stmt_update_user_profile->execute();
        $stmt_update_user_profile->close();
        $conn_f->close();
    }
}

function getTargetedUsernamesByIgUsername($insta_username, $servername, $username, $password, $dbname) {
    $target_usernames = array();
    $conn_get_target_usernames = getConnection($servername, $username, $password, $dbname);
    $stmt_get_target_usernames = $conn_get_target_usernames->prepare("SELECT target_username FROM insta_affiliate.user_insta_target_username WHERE insta_username = ? ORDER BY RAND();");
    $stmt_get_target_usernames->bind_param("s", $insta_username);
    $stmt_get_target_usernames->execute();
    $stmt_get_target_usernames->store_result();
    $stmt_get_target_usernames->bind_result($target_username);
    while ($stmt_get_target_usernames->fetch()) {
        $target_usernames[] = $target_username;
    }
    $stmt_get_target_usernames->free_result();
    $stmt_get_target_usernames->close();
    $conn_get_target_usernames->close();
    return $target_usernames;
}

function getTargetedHashtagsByIgUsername($insta_username, $servername, $username, $password, $dbname) {
    $target_hashtags = array();
    $conn_get_target_hashtags = getConnection($servername, $username, $password, $dbname);
    $stmt_get_target_hashtags = $conn_get_target_hashtags->prepare("SELECT hashtag FROM insta_affiliate.user_insta_target_hashtag WHERE insta_username = ? ORDER BY RAND();");
    $stmt_get_target_hashtags->bind_param("s", $insta_username);
    $stmt_get_target_hashtags->execute();
    $stmt_get_target_hashtags->store_result();
    $stmt_get_target_hashtags->bind_result($hashtag);
    while ($stmt_get_target_hashtags->fetch()) {
        $target_hashtags[] = $hashtag;
    }
    $stmt_get_target_hashtags->free_result();
    $stmt_get_target_hashtags->close();
    $conn_get_target_hashtags->close();
    return $target_hashtags;
}

function checkUserFollowLogs($insta_username, $follower_id, $servername, $username, $password, $dbname) {
    $exists = false;
    $conn_get_followed_username = getConnection($servername, $username, $password, $dbname);
    $stmt_get_followed_username = $conn_get_followed_username->prepare("SELECT log_id FROM user_insta_profile_follow_log WHERE insta_username = ? AND follower_id = ?;");
    $stmt_get_followed_username->bind_param("ss", $insta_username, $follower_id);
    $stmt_get_followed_username->execute();
    while ($stmt_get_followed_username->fetch()) {
        $exists = true;
    }
    $stmt_get_followed_username->close();
    $conn_get_followed_username->close();
    return $exists;
}

function insertNewFollowLogEntry($insta_username, $follower_username, $follower_id, $resp, $servername, $username, $password, $dbname) {
    $conn_insert_follow = getConnection($servername, $username, $password, $dbname);

    $stmt_update_follow_log = $conn_insert_follow->prepare("INSERT INTO `insta_affiliate`.`user_insta_profile_follow_log`
                            (`insta_username`, `follower_username`, `follower_id`, `log`, `follow_success`) 
                            VALUES (?,?,?,?,?);");
    $follow_success = 1;
    $stmt_update_follow_log->bind_param("ssssi", $insta_username, $follower_username, $follower_id, $resp, $follow_success);
    $stmt_update_follow_log->execute();
    $stmt_update_follow_log->close();
    $conn_insert_follow->close();
}

function getNicheTargets($niche, $servername, $username, $password, $dbname) {
    $niche_targets = array();
    $conn_get_niche_targets = getConnection($servername, $username, $password, $dbname);
    $stmt_get_niche_targets = $conn_get_niche_targets->prepare("SELECT target_username FROM insta_affiliate.niche_targets WHERE niche_id = ? ORDER BY RAND();");
    $stmt_get_niche_targets->bind_param("i", $niche);
    $stmt_get_niche_targets->execute();
    $stmt_get_niche_targets->store_result();
    $stmt_get_niche_targets->bind_result($niche_target);
    while ($stmt_get_niche_targets->fetch()) {
        $niche_targets[] = $niche_target;
    }
    $stmt_get_niche_targets->free_result();
    $stmt_get_niche_targets->close();
    $conn_get_niche_targets->close();
    return $niche_targets;
}
