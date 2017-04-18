<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DB.php';
$start = microtime(true);

$conn = getConnection($servername, $username, $password, $dbname);

$conn->query("UPDATE user_insta_profile SET follow_quota = 22, unfollow_quota = 22, like_quota = 30, comment_quota = 6, updated_at = NOW();");

$conn->close();

$time_elapsed_secs = microtime(true) - $start;
echo "\n" . $time_elapsed_secs;
//END OF SCRIPT