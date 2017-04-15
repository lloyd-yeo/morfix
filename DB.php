<?php
$servername = "52.221.60.235";
$username = "root";
$password = "inst@ffiliates123";
$dbname = "insta_affiliate";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (function_exists('getConnection')) {
    
} else {

    function getConnection($servername, $username, $password, $dbname) {
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->query("set names utf8mb4");
        return $conn;
    }

}

$get_premium_and_free_trial_users_sql = "SELECT u.email FROM insta_affiliate.user u "
        . "WHERE (u.user_tier > 1 OR u.trial_activation = 1) AND u.email IN (SELECT email FROM user_insta_profile) ORDER BY u.user_id ASC LIMIT ?,?;";

$get_premium_users_sql = "SELECT u.email FROM insta_affiliate.user u "
        . "WHERE u.user_tier > 1 AND u.email IN (SELECT email FROM user_insta_profile) ORDER BY u.user_id ASC LIMIT ?,?;";

$get_premium_and_free_trial_users_by_email_sql = "SELECT u.email FROM insta_affiliate.user u "
        . "WHERE u.email = ?;";

$get_all_profile_sql = "SELECT DISTINCT(insta_username),
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
                WHERE email = ?;";

$get_follow_profile_sql = "SELECT DISTINCT(insta_username),
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
                AND (NOW() >= next_follow_time OR next_follow_time IS NULL)
                AND auto_follow_ban = 0
                AND (auto_follow = 1 OR auto_unfollow = 1) 
                AND checkpoint_required = 0 AND invalid_user = 0 AND account_disabled = 0 AND incorrect_pw = 0 AND feedback_required = 0;";

$get_comment_profile_sql = "SELECT DISTINCT(insta_username),
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
                AND auto_comment = 1
                AND comment_feedback_required = 0
                AND checkpoint_required = 0 AND invalid_user = 0 AND account_disabled = 0 AND incorrect_pw = 0;";


$get_follows_sql = "SELECT log_id, follower_username, follower_id FROM insta_affiliate.user_insta_profile_follow_log WHERE insta_username = ? AND unfollowed = 0 AND follow = 1 ORDER BY date_inserted ASC LIMIT 2;";