<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DB.php';
$start = microtime(true);
$conn = getConnection($servername, $username, $password, $dbname);
$conn->query("UPDATE user SET trial_activation = 2 WHERE user_tier > 1;");
$conn->close();
$time_elapsed_secs = microtime(true) - $start;
echo "Time taken: " . $time_elapsed_secs;