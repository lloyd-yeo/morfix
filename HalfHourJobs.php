<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DB.php';
$start = microtime(true);

$conn = getConnection($servername, $username, $password, $dbname);
$conn->query("UPDATE user SET trial_activation = 2 WHERE user_tier > 1;");
$conn->query("UPDATE user_insta_profile SET proxy = NULL, invalid_proxy = 0 WHERE invalid_proxy = 1;");

$ig_profiles = array();
$get_profile_stmt = $conn->prepare("SELECT id FROM user_insta_profile WHERE proxy IS NULL;");
$get_profile_stmt->execute();
$get_profile_stmt->store_result();
$get_profile_stmt->bind_result($profile_id);
while ($get_profile_stmt->fetch()) {
    $ig_profiles[] = $profile_id;
}
$get_profile_stmt->free_result();
$get_profile_stmt->close();
$conn->close();

$proxies = array();
$conn = getConnection($servername, $username, $password, $dbname);
$ig_profiles_sz = count($ig_profiles);
$get_proxies_stmt = $conn->prepare("SELECT proxy, assigned FROM insta_affiliate.proxy ORDER BY RAND() LIMIT ?;");
$get_proxies_stmt->bind_param("i", $ig_profiles_sz);
$get_proxies_stmt->execute();
$get_proxies_stmt->bind_result($proxy, $assigned);
while ($get_proxies_stmt->fetch()) {
    $proxies[] = $proxy;
}
$get_proxies_stmt->close();
$conn->close();

for ($i = 0; $i < count($ig_profiles); $i++) {
    $conn = getConnection($servername, $username, $password, $dbname);
    $update_profile_proxy = $conn->prepare("UPDATE user_insta_profile set proxy = ? where id = ?;");
    $update_profile_proxy->bind_param("si", $ig_profiles[$i], $proxies[$i]);
    $update_profile_proxy->execute();
    
    $update_proxy = $conn->prepare("UPDATE proxy set assigned = assigned + 1 where proxy = ?;");
    $update_proxy->bind_param("s", $proxies[$i]);
    $update_proxy->execute();
    $conn->close();
}


$time_elapsed_secs = microtime(true) - $start;
echo "\n" . $time_elapsed_secs;
//END OF SCRIPT