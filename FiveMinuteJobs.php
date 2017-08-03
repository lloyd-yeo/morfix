<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DB.php';
$start = microtime(true);
$conn = getConnection($servername, $username, $password, $dbname);
$conn->query("UPDATE user SET trial_activation = 2 WHERE user_tier > 1;");
$conn->query("UPDATE user_insta_profile SET last_sent_dm = NOW() WHERE last_sent_dm IS NULL;");
$conn->query("UPDATE user_insta_profile SET next_follow_time = NOW() WHERE next_follow_time IS NULL;");
$conn->query("UPDATE user_insta_profile SET next_comment_time = NOW() WHERE next_comment_time IS NULL;");
$conn->query("UPDATE user_insta_target_username SET target_username = REPLACE (target_username, \"@\", \"\");");
$conn->query("UPDATE user_insta_target_username SET target_username = REPLACE (target_username, \"#\", \"\");");
$conn->query("UPDATE user_insta_target_hashtag SET hashtag = REPLACE (hashtag, \"@\", \"\");");
$conn->query("UPDATE user_insta_target_hashtag SET hashtag = REPLACE (hashtag, \"#\", \"\");");
$conn->query("UPDATE user_insta_target_username SET target_username = REPLACE(`target_username`, ' ', '');");
$conn->query("UPDATE user_insta_target_hashtag SET hashtag = REPLACE(`hashtag`, ' ', '');");
$conn->query("UPDATE user_insta_target_username SET target_username = REPLACE(`target_username`, '\n', '');");
$conn->query("UPDATE user_insta_target_hashtag SET hashtag = REPLACE(`hashtag`, '\n', '');");
$conn->query("UPDATE user_insta_target_username SET target_username = REPLACE(`target_username`, '\t', '');");
$conn->query("UPDATE user_insta_target_hashtag SET hashtag = REPLACE(`hashtag`, '\t', '');");
$conn->query("UPDATE user SET trial_activation = 1, trial_end_date = NOW() + INTERVAL 7 DAY WHERE trial_activation = 0;");
$conn->close();
$time_elapsed_secs = microtime(true) - $start;
echo "Time taken: " . $time_elapsed_secs;