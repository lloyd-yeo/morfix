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
    $file = fopen("/home/ubuntu/dm-send-lock/cron-send-" . $specific_email . ".txt", "w+");
} else {
    $file = fopen("/home/ubuntu/dm-send-lock/cron-send-" . $offset . ".txt", "w+");
}
if (flock($file, LOCK_EX | LOCK_NB)) {
    $emails = array();
    $conn_get_user = getConnection($servername, $username, $password, $dbname);
    $conn_get_user->query($clear_empty_jobs_sql);
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
        $stmt_get_user = $conn_get_user->prepare($get_all_users_by_email_sql);
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
        echo "[$email] getting profiles...\n";
        $insta_profiles = array();
        $conn_get_profiles = getConnection($servername, $username, $password, $dbname);
        $stmt_get_profile = $conn_get_profiles->prepare($get_all_dm_profile_sql);
        $stmt_get_profile->bind_param("s", $email);
        $stmt_get_profile->execute();
        $stmt_get_profile->store_result();
        $stmt_get_profile->bind_result($insta_username, $insta_user_id, $insta_id, $insta_pw, $auto_dm_delay, $temporary_ban, $insta_new_follower_template, $follow_up_message, $proxy);
        while ($stmt_get_profile->fetch()) {
            $insta_profiles[] = array(
                "insta_username" => $insta_username,
                "insta_user_id" => $insta_user_id,
                "insta_id" => $insta_id,
                "insta_pw" => $insta_pw,
                "auto_dm_delay" => $auto_dm_delay,
                "temporary_ban" => $temporary_ban,
                "insta_new_follower_template" => $insta_new_follower_template,
                "follow_up_message" => $follow_up_message,
                "proxy" => $proxy
            );
        }
        $stmt_get_profile->free_result();
        $stmt_get_profile->close();
        $conn_get_profiles->close();

        foreach ($insta_profiles as $insta_profile) {
            $insta_username = $insta_profile['insta_username'];
            $insta_user_id = $insta_profile['insta_user_id'];
            $insta_id = $insta_profile['insta_id'];
            $insta_pw = $insta_profile['insta_pw'];
            $auto_dm_delay = $insta_profile['auto_dm_delay'];
            $temporary_ban = $insta_profile['temporary_ban'];
            $insta_new_follower_template = $insta_profile['insta_new_follower_template'];
            $follow_up_message = $insta_profile['follow_up_message'];
            $proxy = $insta_profile['proxy'];
            echo "[$insta_username] retrieved...\n";
            
            $dm_job = getDmJobsByIgUsername($insta_username, $servername, $username, $password, $dbname);
            
            if (is_null($dm_job)) {
                continue;
            }
            
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
                    
                    try {
                        $delay = rand(25,40);
                        $direct_msg_resp = $instagram->directMessage($dm_job["recipient_insta_id"], $dm_job["message"]);
                        var_dump($direct_msg_resp);
                        updateDmJobFulfilled($dm_job["job_id"], $servername, $username, $password, $dbname);
                        if (!is_null($temporary_ban)) {
                            updateUserNextSendTime($insta_username, $delay, "banned", $servername, $username, $password, $dbname);
                        } else {
                            updateUserNextSendTime($insta_username, $delay, "normal", $servername, $username, $password, $dbname);
                        }
                        
                        if ($auto_dm_delay == 1) {
                            
                        }
                    } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                        echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
                        if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                            updateUserFeedbackRequired($insta_username, $dm_job["job_id"], $request_ex->getMessage(), $servername, $username, $password, $dbname);
                            continue;
                        }
                        if (stripos(trim($request_ex->getMessage()), "checkpoint_required") !== false) {
                            updateUserCheckpointRequired($insta_username, $servername, $username, $password, $dbname);
                            continue;
                        }
                    }
                }
            } catch (Exception $ex) {
                echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
                echo "[" . $insta_username . "] " .$ex->getTraceAsString() . "\n";
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

function updateUserFeedbackRequired($insta_username, $job_id, $error_msg, $servername, $username, $password, $dbname) {
    
    $conn = getConnection($servername, $username, $password, $dbname);
    $stmt_update_user_profile = $conn->prepare("UPDATE user_insta_profile SET last_sent_dm = NOW() + INTERVAL 6 HOUR, temporary_ban = NOW() + INTERVAL 6 HOUR WHERE insta_username = ?;");
    $stmt_update_user_profile->bind_param("s", $insta_username);
    $stmt_update_user_profile->execute();
    $stmt_update_user_profile->close();
    
    $stmt_update_dm_job = $conn->prepare("UPDATE dm_job SET error_msg = ? WHERE job_id = ?;");
    $stmt_update_dm_job->bind_param("si", $error_msg, $job_id);
    $stmt_update_dm_job->execute();
    $stmt_update_dm_job->close();
    
    $conn->close();
}

function updateUserCheckpointRequired($insta_username, $servername, $username, $password, $dbname) {
    
    $conn = getConnection($servername, $username, $password, $dbname);
    $stmt_update_user_profile = $conn->prepare("UPDATE user_insta_profile SET checkpoint_required = 1 WHERE insta_username = ?;");
    $stmt_update_user_profile->bind_param("s", $insta_username);
    $stmt_update_user_profile->execute();
    $stmt_update_user_profile->close();
    $conn->close();
}

function updateDmJobFulfilled($job_id, $servername, $username, $password, $dbname) {
    $conn = getConnection($servername, $username, $password, $dbname);
    $stmt_update_user_profile = $conn->prepare("UPDATE dm_job SET fulfilled = 1, updated_at = NOW() WHERE job_id = ?;");
    $stmt_update_user_profile->bind_param("i", $job_id);
    $stmt_update_user_profile->execute();
    $stmt_update_user_profile->close();
    $conn->close();
}

function getDmJobsByIgUsername($insta_username, $servername, $username, $password, $dbname) {
    $dm_job = NULL;
    $conn = getConnection($servername, $username, $password, $dbname);
    $stmt_get_dm_job = $conn->prepare("SELECT job_id, recipient_username, recipient_insta_id, recipient_fullname, message FROM insta_affiliate.dm_job WHERE insta_username = ? AND fulfilled = 0 ORDER BY job_id ASC LIMIT 1;");
    $stmt_get_dm_job->bind_param("s", $insta_username);
    $stmt_get_dm_job->execute();
    $stmt_get_dm_job->store_result();
    $stmt_get_dm_job->bind_result($job_id, $recipient_username, $recipient_insta_id, $recipient_fullname, $message);
    while ($stmt_get_dm_job->fetch()) {
        $dm_job = array(
            "job_id" => $job_id,
            "recipient_username" => $recipient_username, 
            "recipient_insta_id" => $recipient_insta_id, 
            "recipient_fullname" => $recipient_fullname, 
            "message" => $message
        );
    }
    $stmt_get_dm_job->free_result();
    $conn->close();
    return $dm_job;
}

function updateUserNextSendTime($insta_username, $delay, $mode, $servername, $username, $password, $dbname) {
    if ($mode == "banned") {
        $conn_f = getConnection($servername, $username, $password, $dbname);
        $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET last_sent_dm = NOW() + INTERVAL 1 DAY, temporary_ban = NULL WHERE insta_username = ?;");
        $stmt_update_user_profile->bind_param("s", $insta_username);
        $stmt_update_user_profile->execute();
        $stmt_update_user_profile->close();
        $conn_f->close();
    } else if ($mode == "normal") {
        $conn_f = getConnection($servername, $username, $password, $dbname);
        $stmt_update_user_profile = $conn_f->prepare("UPDATE user_insta_profile SET last_sent_dm = NOW() + INTERVAL $delay MINUTE WHERE insta_username = ?;");
        $stmt_update_user_profile->bind_param("s", $insta_username);
        $stmt_update_user_profile->execute();
        $stmt_update_user_profile->close();
        $conn_f->close();
    }
}