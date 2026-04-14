<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lab Workflow System</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<link href="/lab-workflow/assets/css/style.css" rel="stylesheet">

</head>

<body>

<?php if (isset($_SESSION["role"])): ?>

<!-- ======================
     SIDEBAR
====================== -->
<div class="sidebar">

    <!-- LOGO (FINAL CLEAN) -->
    <div class="logo">
        <img src="/lab-workflow/assets/images/logo.jpg" alt="Logo">
        <div>
            <div class="logo-title">LabFlow</div>
            <small class="logo-sub">Lab System</small>
        </div>
    </div>

    <!-- NAVIGATION -->
    <nav>

        <a href="/lab-workflow/dashboard.php"
           class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <?php if ($_SESSION["role"] == "Receptionist"): ?>

            <a href="/lab-workflow/receptionist/add_patient.php"
               class="<?= basename($_SERVER['PHP_SELF']) == 'add_patient.php' ? 'active' : '' ?>">
                <i class="bi bi-person-plus"></i>
                <span>Add Patient</span>
            </a>

            <a href="/lab-workflow/receptionist/add_sample.php"
               class="<?= basename($_SERVER['PHP_SELF']) == 'add_sample.php' ? 'active' : '' ?>">
                <i class="bi bi-droplet-half"></i>
                <span>Add Sample</span>
            </a>

        <?php endif; ?>

        <?php if ($_SESSION["role"] == "Technician"): ?>

            <a href="/lab-workflow/technician/view_tests.php"
               class="<?= basename($_SERVER['PHP_SELF']) == 'view_tests.php' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-data"></i>
                <span>View Tests</span>
            </a>

        <?php endif; ?>

        <?php if ($_SESSION["role"] == "Doctor"): ?>

            <a href="/lab-workflow/doctor/view_reports.php"
               class="<?= basename($_SERVER['PHP_SELF']) == 'view_reports.php' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-medical"></i>
                <span>View Reports</span>
            </a>

        <?php endif; ?>

    </nav>

    <!-- FOOTER -->
    <div class="sidebar-footer">
        <a href="/lab-workflow/logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>

</div>

<div class="user-info d-flex align-items-center gap-2">

    <div class="avatar">
        <?= strtoupper($_SESSION["role"][0]) ?>
    </div>

    <span class="role-badge">
        <?= $_SESSION["role"] ?>
    </span>

</div>

<div id="loader">
    <div class="spinner"></div>
</div>

<!-- ======================
     TOPBAR
====================== -->
<div class="topbar">

    <div class="page-title fw-semibold">
        Laboratory Workflow System
    </div>

    <div class="user-info">
        <span class="role">
            <?= htmlspecialchars($_SESSION["role"]) ?>
        </span>
    </div>

</div>

<!-- ======================
     MAIN CONTENT START
====================== -->
<div class="main-content">

<?php else: ?>

<!-- PUBLIC (LOGIN / INDEX) -->
<div class="container mt-5">

<?php endif; ?>