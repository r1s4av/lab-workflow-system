<?php
session_start();
include "../db.php";
include "../header.php";

if ($_SESSION["role"] != "Doctor") {
    header("Location: ../login.php");
    exit;
}

$query = "
SELECT r.report_id, p.patient_name, t.test_name,
       st.result_value, r.generated_date, r.status
FROM report r
JOIN sample_test st ON r.sample_test_id = st.sample_test_id
JOIN sample s ON st.sample_id = s.sample_id
JOIN patient p ON s.patient_id = p.patient_id
JOIN test t ON st.test_id = t.test_id
ORDER BY r.generated_date DESC
";

$result = $conn->query($query);
?>

<h3 class="fw-bold mb-4">Medical Report Approval Panel</h3>

<div class="card p-4">

<table class="table table-hover">

<thead>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Test</th>
<th>Result</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if ($result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>

<tr>
<td><?= $row['report_id'] ?></td>
<td><?= $row['patient_name'] ?></td>
<td><?= $row['test_name'] ?></td>

<td>
<span class="badge-status badge-complete">
<?= $row['result_value'] ?>
</span>
</td>

<td><?= date("d M Y", strtotime($row['generated_date'])) ?></td>

<td>
<?php if ($row['status']=="Approved"): ?>
<span class="badge-status badge-approved">Approved</span>
<?php else: ?>
<span class="badge-status badge-pending">Pending</span>
<?php endif; ?>
</td>

<td>
<?php if ($row['status']!="Approved"): ?>
<a href="approve_report.php?id=<?= $row['report_id'] ?>" 
   class="btn btn-success btn-sm">Approve</a>
<?php endif; ?>
</td>

</tr>

<?php endwhile; ?>
<?php else: ?>

<tr>
<td colspan="7" class="text-center text-muted">No reports available</td>
</tr>

<?php endif; ?>

</tbody>
</table>

</div>

<?php include "../footer.php"; ?>