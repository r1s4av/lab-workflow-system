<?php
session_start();
include "db.php";
include "header.php";

if (!isset($_SESSION["role"])) {
    header("Location: login.php");
    exit;
}

$patients = $conn->query("SELECT COUNT(*) as total FROM patient")->fetch_assoc()['total'];
$samples = $conn->query("SELECT COUNT(*) as total FROM sample")->fetch_assoc()['total'];
$reports = $conn->query("SELECT COUNT(*) as total FROM report")->fetch_assoc()['total'];

$pending_tests = $conn->query("SELECT COUNT(*) as total FROM sample_test WHERE result_value IS NULL")->fetch_assoc()['total'];
$completed_tests = $conn->query("SELECT COUNT(*) as total FROM sample_test WHERE result_value IS NOT NULL")->fetch_assoc()['total'];

$role = $_SESSION["role"];
?>

<h2 class="fw-bold">Dashboard</h2>
<p class="text-muted mb-4">System Overview & Insights</p>

<!-- STATS -->
<div class="row g-4">

<div class="col-md-3">
<div class="dashboard-card card-blue">
<p>Total Patients</p>
<h2><?= $patients ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-card card-orange">
<p>Samples</p>
<h2><?= $samples ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-card card-green">
<p>Reports</p>
<h2><?= $reports ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="dashboard-card card-purple">
<p>Pending Tests</p>
<h2><?= $pending_tests ?></h2>
</div>
</div>

</div>

<!-- INSIGHT PANEL -->
<div class="row mt-4 g-4">

<div class="col-md-4">
<div class="card p-4">
<h6 class="text-muted">Completion Rate</h6>
<h3 class="fw-bold">
<?= $completed_tests ?> / <?= ($completed_tests + $pending_tests) ?>
</h3>
<div class="progress mt-2">
<div class="progress-bar bg-success" style="width: <?= ($completed_tests*100)/max(1,($completed_tests+$pending_tests)) ?>%"></div>
</div>
</div>
</div>

<div class="col-md-8">
<div class="card p-4">
<h5 class="fw-bold mb-3">Recent Activity</h5>

<?php
$recent = $conn->query("
SELECT p.patient_name, s.sample_id, st.performed_at
FROM sample_test st
JOIN sample s ON st.sample_id = s.sample_id
JOIN patient p ON s.patient_id = p.patient_id
ORDER BY st.performed_at DESC LIMIT 5
");
?>

<?php if($recent->num_rows > 0): ?>
<ul class="list-group list-group-flush">

<?php while($row = $recent->fetch_assoc()): ?>

<li class="list-group-item d-flex justify-content-between align-items-center">
<div>
<strong><?= $row['patient_name'] ?></strong>
<br>
<small class="text-muted">Sample #<?= $row['sample_id'] ?></small>
</div>

<span class="badge bg-light text-dark">
<?= $row['performed_at'] ?? 'Pending' ?>
</span>

</li>

<?php endwhile; ?>

</ul>

<?php else: ?>
<div class="empty-state">No recent activity</div>
<?php endif; ?>

</div>
</div>

</div>

<!-- QUICK ACTIONS -->
<div class="mt-5">
<h4 class="fw-bold mb-3">Quick Actions</h4>

<div class="row g-3">

<?php if ($role == "Receptionist"): ?>

<div class="col-md-4">
<a href="receptionist/add_patient.php" class="btn btn-primary w-100 p-3">
➕ Register Patient
</a>
</div>

<div class="col-md-4">
<a href="receptionist/add_sample.php" class="btn btn-warning w-100 p-3">
🧪 Create Sample
</a>
</div>

<?php elseif ($role == "Technician"): ?>

<div class="col-md-4">
<a href="technician/view_tests.php" class="btn btn-success w-100 p-3">
🔬 Perform Tests
</a>
</div>

<?php elseif ($role == "Doctor"): ?>

<div class="col-md-4">
<a href="doctor/view_reports.php" class="btn btn-dark w-100 p-3">
📄 Approve Reports
</a>
</div>

<?php endif; ?>

</div>
</div>

<?php include "footer.php"; ?>