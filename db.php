<?php
$host = "junction.proxy.rlwy.net";
$user = "root";
$pass = "YOUR_PASSWORD";
$db   = "railway";
$port = 21864;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>