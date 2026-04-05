<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "lab_workflow_db";

$conn = new mysqli("localhost", "root", "", "lab_workflow");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>