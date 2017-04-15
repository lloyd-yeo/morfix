<?php
$servername = "52.221.60.235";
$username = "root";
$password = "inst@ffiliates123";
$dbname = "insta_affiliate";

if (function_exists('getConnection')) {
    
} else {

    function getConnection() {
        $conn_get_profiles = new mysqli($servername, $username, $password, $dbname);
        $conn_get_profiles->query("set names utf8mb4");
        return $conn_get_profiles;
    }

} 