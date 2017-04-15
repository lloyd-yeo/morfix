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
$file = NULL;
if (isset($argv[3])) {
    $file = fopen("comment-lock/cron-interaction-comment-" . $specific_email . ".txt", "w+");
} else {
    $file = fopen("comment-lock/cron-interaction-comment-" . $offset . ".txt", "w+");
}
if (flock($file, LOCK_EX | LOCK_NB)) {
    $emails = array();
    $conn_get_user = getConnection($servername, $username, $password, $dbname);
    $stmt_get_user = NULL;
    if (is_null($specific_email)) {
        echo "Retrieving users off $offset $num_result\n\n";
        $stmt_get_user = $conn_get_user->prepare($get_premium_users_sql);
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
        $stmt_get_profile = $conn_get_profiles->prepare($get_comment_profile_sql);
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
            $profile_comment = getRandomUserComment($insta_username, $servername, $username, $password, $dbname);
            if (is_null($profile_comment)) {
                echo "[" . $insta_username . "] has NULL comment.\n";
                continue;
            } else {
                echo "[" . $insta_username . "] using comment [$profile_comment].\n";
                try {
                    $newest_follow = getUserNewestFollow($insta_username, $servername, $username, $password, $dbname);
                    if (is_null($newest_follow)) {
                        echo "[" . $insta_username . "] newest follower [ " . $newest_follow["follower_username"] . "].\n";
                        continue;
                    } else {
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
                            $target_username_posts = $instagram->getUserFeed($newest_follow["follower_id"]);
                            if (count($target_username_posts->items) == 0) {
                                insertCommentLog($insta_username, $newest_follow["follower_username"], $newest_follow["follower_id"], "0", "User doesn't have any posts.");
                                continue;
                            }
                            $throttle_limit = 41;
                            $throttle_count = 0;
                            foreach ($target_username_posts->items as $item) {
                                $throttle_count++;
                                if ($throttle_count == $throttle_limit) {
                                    break;
                                }
                                $comment_response = $instagram->comment($item->pk, utf8_decode($profile_comment));
                                if ($comment_response->isOk()) {
                                    $commented = 1;
                                    insertCommentLog($insta_username, $item->user->username, $item->user->pk, $item->pk, serialize($comment_response), $servername, $username, $password, $dbname);
                                    echo "[" . $insta_username . "] commented on [" . $item->user->username . "] [" . $item->getItemUrl() . "]\n";
                                }
                                if ($commented == 1) {
                                    break;
                                }
                            }
                        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                            echo "[" . $insta_username . "] req-error: " . $request_ex->getMessage() . "\n";
                            if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                                updateUserFeedbackRequired($insta_username, $servername, $username, $password, $dbname);
                                $followed = 1;
                                break;
                            }
                            
                        }
                    }
                } catch (Exception $ex) {
                    echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
                }
            }
        }
    }
    $time_elapsed_secs = microtime(true) - $start;
    echo "\n" . $time_elapsed_secs;
} else {
    echo "Unable to obtain lock.";
    die();
}
flock($file, LOCK_UN);
fclose($file);

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

function getRandomUserComment($insta_username, $servername, $username, $password, $dbname) {
    $comment = NULL;
    $conn_get_comments = new mysqli($servername, $username, $password, $dbname);
    $conn_get_comments->query("set names utf8mb4");
    $stmt_get_comments = $conn_get_comments->prepare("SELECT comment FROM user_insta_profile_comment WHERE insta_username = ? ORDER BY RAND() LIMIT 1;");
    $stmt_get_comments->bind_param("s", $insta_username);
    $stmt_get_comments->execute();
    $stmt_get_comments->store_result();
    $stmt_get_comments->bind_result($comment_);
    while ($stmt_get_comments->fetch()) {
        $comment = $comment_;
    }
    $stmt_get_comments->free_result();
    $stmt_get_comments->close();
    $conn_get_comments->close();
    return $comment;
}

function getUserNewestFollow($insta_username, $servername, $username, $password, $dbname) {
    $newest_follow = NULL;
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("set names utf8mb4");
    $stmt_get_newest_follower = $conn->prepare("SELECT follower_username, follower_id FROM insta_affiliate.user_insta_profile_follow_log 
    WHERE follow = 1 AND unfollowed = 0 AND insta_username = ? 
    AND follower_username NOT IN (SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = ?)
    ORDER BY date_inserted DESC LIMIT 1;");
    $stmt_get_newest_follower->bind_param("ss", $insta_username, $insta_username);
    $stmt_get_newest_follower->execute();
    $stmt_get_newest_follower->store_result();
    $stmt_get_newest_follower->bind_result($follower_un, $follower_id);
    while ($stmt_get_newest_follower->fetch()) {
        $newest_follow = array(
            "follower_username" => $follower_un,
            "follower_id" => $follower_id
        );
    }
    $stmt_get_newest_follower->free_result();
    $stmt_get_newest_follower->close();
    $conn->close();
    return $newest_follow;
}

function updateUserFeedbackRequired($insta_username, $servername, $username, $password, $dbname) {
    $conn_f = new mysqli($servername, $username, $password, $dbname);
    $conn_f->query("set names utf8mb4");
    $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET auto_comment_ban = 1, auto_comment_ban_time = NOW() + INTERVAL 6 HOUR, comment_feedback_required = 1 WHERE insta_username = ?;");
    $stmt_update_user_profile->bind_param("s", $insta_username);
    $stmt_update_user_profile->execute();
    $stmt_update_user_profile->close();
    $conn_f->close();
}

function insertCommentLog($insta_username, $item_username, $item_userid, $item_id, $resp, $servername, $username, $password, $dbname) {
    $conn_insert_comment_log = new mysqli($servername, $username, $password, $dbname);
    $conn_insert_comment_log->query("set names utf8mb4");
    $stmt_insert_comment_log = $conn_insert_comment_log->prepare("INSERT INTO insta_affiliate.user_insta_profile_comment_log (insta_username, target_username, target_insta_id, target_media, log, date_commented) VALUES (?,?,?,?,?,NOW());");
    $stmt_insert_comment_log->bind_param("sssss", $insta_username, $item_username, $item_userid, $item_id, $resp);
    $stmt_insert_comment_log->execute();
    $conn_insert_comment_log->close();
}
