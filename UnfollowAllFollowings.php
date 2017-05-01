<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DB.php';
$start = microtime(true);
$specific_email = $argv[1];
$path = "/home/ubuntu/unfollow-lock";
$file = NULL;
$file = fopen($path . "/cron-interaction-unfollow-user-" . $specific_email . ".txt", "w+");

if (flock($file, LOCK_EX | LOCK_NB)) {
    $emails = array();
    $conn_get_user = getConnection($servername, $username, $password, $dbname);
    $stmt_get_user = NULL;
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
    $conn_get_user->close();

    foreach ($emails as $email) {

        $insta_profiles = getFollowProfiles($email, $servername, $username, $password, $dbname);

        foreach ($insta_profiles as $insta_profile) {

            $insta_username = $insta_profile['insta_username'];
            $insta_user_id = $insta_profile['insta_user_id'];
            $insta_id = $insta_profile['insta_id'];
            $insta_pw = $insta_profile['insta_pw'];
            $niche = $insta_profile['niche'];
            $next_follow_time = $insta_profile['next_follow_time'];
            $unfollow = $insta_profile['unfollow'];
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
            $follow_quota = $insta_profile['follow_quota'];
            $unfollow_quota = $insta_profile['unfollow_quota'];
            $proxy = $insta_profile['proxy'];


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
            $delay = rand($follow_unfollow_delay, $follow_unfollow_delay + 2);
            echo "[" . $insta_username . "] beginning unfollowing sequence.\n";

            if ($unfollow_quota < 1) {
                echo "[" . $insta_username . "] has reached quota for unfollowing today.\n";
                continue;
            }

            $config = array();
            $config["storage"] = "mysql";
            $config["dbusername"] = "root";
            $config["dbpassword"] = "inst@ffiliates123";
            $config["dbhost"] = "52.221.60.235:3306";
            $config["dbname"] = "morfix";
            $config["dbtablename"] = "instagram_sessions";

            $debug = true;
            $truncatedDebug = false;
            $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

            if (is_null($proxy)) {
                continue;
            } else {
                $instagram->setProxy($proxy);
            }
            $current_log_id = "";

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
                    echo "[" . $insta_username . "] logging in..\n";
                    $followings = $instagram->getSelfUsersFollowing();
                    foreach ($followings->users as $user) {
                        insertNewFollowLogEntry($insta_username, $user->username, $user->pk, NULL, $servername, $username, $password, $dbname);
                    }
                } else {
                    foreach ($users_to_unfollow as $user_to_unfollow) {
                        echo "[" . $insta_username . "] retrieved: " . $user_to_unfollow["follower_username"] . "\n";
                        $current_log_id = $user_to_unfollow["log_id"];
                        if ($unfollow_unfollowed == 1) {
                            $friendship = $instagram->getUserFriendship($user_to_unfollow["follower_id"]);
                            if ($friendship->followed_by == true) {
                                echo "[" . $insta_username . "] is followed by " . $user_to_unfollow["follower_username"] . "\n";
                                updateUnfollowLog($user_to_unfollow["log_id"], $insta_username, $servername, $username, $password, $dbname);
                                continue;
                            }
                        }

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
                echo "[" . $insta_username . "] checkpoint_ex: " . $checkpoint_ex->getMessage() . "\n";
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                echo "[" . $insta_username . "] network_ex: " . $network_ex->getMessage() . "\n";
            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                echo "[" . $insta_username . "] endpoint_ex: " . $endpoint_ex->getMessage() . "\n";
            } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                echo "[" . $insta_username . "] incorrectpw_ex: " . $incorrectpw_ex->getMessage() . "\n";
            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                echo "[" . $insta_username . "] feedback_ex: " . $feedback_ex->getMessage() . "\n";
            } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                echo "[" . $insta_username . "] emptyresponse_ex: " . $emptyresponse_ex->getMessage() . "\n";
                if (stripos(trim($emptyresponse_ex->getMessage()), "No response from server. Either a connection or configuration error") !== false) {
                    updateUnfollowLogWithNull($current_log_id, $servername, $username, $password, $dbname);
                    $followed = 1;
                    break;
                }
            } catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
                echo "[" . $insta_username . "] throttled_ex: " . $throttled_ex->getMessage() . "\n";
            } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                echo "[" . $insta_username . "] request_ex: " . $request_ex->getMessage() . "\n";
                if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                    updateUserFeedbackRequired($insta_username, $servername, $username, $password, $dbname);
                    $followed = 1;
                    break;
                }
            }
        }
    }
}

function insertNewFollowLogEntry($insta_username, $follower_username, $follower_id, $resp, $servername, $username, $password, $dbname) {
    $conn_insert_follow = getConnection($servername, $username, $password, $dbname);

    $stmt_update_follow_log = $conn_insert_follow->prepare("INSERT INTO `insta_affiliate`.`user_insta_profile_follow_log`
                            (`insta_username`, `follower_username`, `follower_id`, `log`, `follow_success`) 
                            VALUES (?,?,?,?,?);");
    $follow_success = 1;
    $stmt_update_follow_log->bind_param("ssssi", $insta_username, $follower_username, $follower_id, $resp, $follow_success);
    if ($stmt_update_follow_log->execute()) {
        echo "[$insta_username] inserted as follow entry: [$follower_username]\n";
    }
    $stmt_update_follow_log->close();
    $conn_insert_follow->close();
}


function updateUserNextFollowTime($insta_username, $delay, $mode, $servername, $username, $password, $dbname) {
    if ($mode == "unfollow") {
        $conn_f = new mysqli($servername, $username, $password, $dbname);
        $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET next_follow_time = NOW() + INTERVAL $delay MINUTE, unfollow_quota = unfollow_quota - 1 WHERE insta_username = ?;");
        $stmt_update_user_profile->bind_param("s", $insta_username);
        $stmt_update_user_profile->execute();
        $stmt_update_user_profile->close();
        $conn_f->close();
    } else if ($mode == "follow") {
        $conn_f = new mysqli($servername, $username, $password, $dbname);
        $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET next_follow_time = NOW() + INTERVAL $delay MINUTE, follow_quota = follow_quota - 1 WHERE insta_username = ?;");
        $stmt_update_user_profile->bind_param("s", $insta_username);
        $stmt_update_user_profile->execute();
        $stmt_update_user_profile->close();
        $conn_f->close();
    }
}