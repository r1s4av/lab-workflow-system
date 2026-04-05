<?php
session_start();
include "../db.php";

if ($_SESSION["role"] != "Doctor") {
    header("Location: ../login.php");
    exit;
}

$id = $_GET["id"];

$conn->query("UPDATE report SET status='Approved' WHERE report_id=$id");

header("Location: view_reports.php");