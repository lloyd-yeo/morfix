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
    $file = fopen("/home/ubuntu/profile-lock/cron-refresh-" . $specific_email . ".txt", "w+");
} else {
    $file = fopen("/home/ubuntu/profile-lock/cron-refresh-" . $offset . ".txt", "w+");
}
if (flock($file, LOCK_EX | LOCK_NB)) {
    $emails = array();
    $conn_get_user = getConnection($servername, $username, $password, $dbname);
    $stmt_get_user = NULL;
    if (is_null($specific_email)) {
        echo "Retrieving users off $offset $num_result\n\n";
        $stmt_get_user = $conn_get_user->prepare($get_all_users_sql);
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
        $stmt_get_profile = $conn_get_profiles->prepare($get_all_profile_sql);
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
                $ig_username = $insta_username;
                $ig_password = $insta_pw;
                
                if (is_null($proxy)) {
                    continue;
                } else {
                    $instagram->setProxy($proxy);
                    $instagram->setUser($ig_username, $ig_password);
                    $instagram->login();
                    $user_response = $instagram->getUserInfoByName($ig_username);
                    $instagram_user = $user_response->user;
                    $conn = getConnection($servername, $username, $password, $dbname);
                    $update_stmt = $conn->prepare("UPDATE user_insta_profile SET updated_at = NOW(), follower_count = ?, num_posts = ?, insta_user_id = ? WHERE insta_username = ?;");
                    $update_stmt->bind_param("iiss", $instagram_user->follower_count, $instagram_user->media_count, $instagram_user->pk, $ig_username);
                    $update_stmt->execute();
                    $update_stmt->close();
                    $conn->close();
                    
                    $items = $instagram->getSelfUserFeed()->items;
                    foreach ($items as $item) {
                        try {
                            $conn = getConnection($servername, $username, $password, $dbname);
                            $insert_stmt = $conn->prepare("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) VALUES (?,?,?);");
                            $insert_stmt->bind_param("sss", $ig_username, $item->id, $item->image_versions2->candidates[0]->url);
                            $insert_stmt->execute();
                            $insert_stmt->close();
                            $conn->close();
                        } catch (Exception $e) {
                            break;
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

function updateUserFeedbackRequired($insta_username, $servername, $username, $password, $dbname) {
    $conn_f = new mysqli($servername, $username, $password, $dbname);
    $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET feedback_required = 1 WHERE insta_username = ?;");
    $stmt_update_user_profile->bind_param("s", $insta_username);
    $stmt_update_user_profile->execute();
    $stmt_update_user_profile->close();
    $conn_f->close();
}
