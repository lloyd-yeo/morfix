<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . 'DB.php';
$start = microtime(true);

$offset = $argv[1];
$num_result = $argv[2]; //this is the constant

$emails = array();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn_get_user = getConnection();
$stmt_get_user = $conn_get_user->prepare("SELECT u.email FROM insta_affiliate.user u "
        . "WHERE (u.user_tier > 1 OR u.trial_activation = 1) ORDER BY u.user_id ASC LIMIT ?,?;");
$stmt_get_user->bind_param("ii", $offset, $num_result);
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
    $insta_profiles = array();
    $conn_get_profiles = getConnection();
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
                AND checkpoint_required = 0 AND invalid_user = 0 AND account_disabled = 0 AND incorrect_pw = 0 AND feedback_required = 0;");
    $stmt_get_profile->bind_param("s", $email);
    $stmt_get_profile->execute();
    $stmt_get_profile->store_result();
    $stmt_get_profile->bind_result(
            $insta_username, $insta_user_id, $insta_id, $insta_pw, $niche, $next_follow_time, $niche_target_counter, $unfollow, $auto_interaction_ban, $auto_interaction_ban_time, $follow_cycle, $auto_unfollow, $auto_follow, $auto_follow_ban, $auto_follow_ban_time, $follow_unfollow_delay, $speed, $follow_min_follower, $follow_max_follower, $unfollow_unfollowed, $daily_follow_quota, $daily_unfollow_quota, $proxy);
    while ($stmt_get_profile->fetch()) {
        $insta_profiles[] = array(
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
            if (($auto_unfollow == 1 && $auto_follow == 0) || ($auto_follow == 1 && $unfollow == 1)) {

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

                $debug = true;
                $truncatedDebug = false;
                $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

                if (is_null($proxy)) {
                    continue;
                } else {
                    $instagram->setProxy($ig_profile->proxy);
                }
                try {
                    $instagram->setUser($ig_username, $ig_password);
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
                }
            }
        } catch (Exception $ex) {
            echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
        }
    }
}

$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs;
//END OF SCRIPT
?>