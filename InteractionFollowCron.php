<?php

require __DIR__ . '/vendor/autoload.php';
$start = microtime(true);

$offset = $argv[1];
$num_result = $argv[2]; //this is the constant

$servername = "52.221.60.235";
$username = "root";
$password = "inst@ffiliates123";
$dbname = "insta_affiliate";

$emails = array();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn_get_user = new mysqli($servername, $username, $password, $dbname);
$stmt_get_user = $conn_get_user->prepare("SELECT u.email FROM insta_affiliate.user u "
        . "WHERE (u.user_tier > 1 OR u.trial_activation = 1) ORDER BY u.user_id ASC LIMIT ?,?;");
$stmt_get_user->bind_param("ii", $offset, $num_result);
$stmt_get_user->execute();
$stmt_get_user->store_result();
$stmt_get_user->bind_result($email_);
while ($stmt_get_user->fetch()) {
    $emails[$email_] = array($email_);
}
$stmt_get_user->close();
$conn_get_user->close();

foreach ($emails as $email) {
    $conn_get_profiles = new mysqli($servername, $username, $password, $dbname);
    $conn_get_profiles->query("set names utf8mb4");
    $stmt_get_profile = $conn_get_profiles->prepare("SELECT DISTINCT(insta_username),
                insta_user_id, 
                id, 
                insta_pw,
                niche, 
                next_follow_time, 
                niche_target_counter, 
                unfollow, 
                auto_interaction_ban, 
                auto_interaction_ban_time,
                follow_cycle,
                auto_unfollow,
                auto_follow,
                auto_follow_ban,
                auto_follow_ban_time,
                follow_unfollow_delay,
                speed,
                follow_min_followers,
                follow_max_followers,
                unfollow_unfollowed,
                daily_follow_quota,
                daily_unfollow_quota,
                proxy
                FROM insta_affiliate.user_insta_profile 
                WHERE auto_interaction = 1
                AND email = ?
                AND NOW() >= next_follow_time
                AND auto_follow_ban = 0
                AND (auto_follow = 1 OR auto_unfollow = 1) 
                AND checkpoint_required = 0 AND invalid_user = 0;");
    $stmt_get_profile->bind_param("s", $email);
    $stmt_get_profile->execute();
    $stmt_get_profile->store_result();
    $stmt_get_profile->bind_result(
            $insta_username, $insta_user_id, $insta_id, $insta_pw, $niche, $next_follow_time, $niche_target_counter, $unfollow, $auto_interaction_ban, $auto_interaction_ban_time, $follow_cycle, $auto_unfollow, $auto_follow, $auto_follow_ban, $auto_follow_ban_time, $follow_unfollow_delay, $speed, $follow_min_follower, $follow_max_follower, $unfollow_unfollowed, $daily_follow_quota, $daily_unfollow_quota, $proxy);
    while ($stmt_get_profile->fetch()) {

        $target_username = "";
        $target_follow_username = "";
        $target_follow_id = "";
        $custom_target_defined = false;
        
        
    }
    $stmt_get_profile->close();
    $conn_get_profiles->close();
}

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs;
?>