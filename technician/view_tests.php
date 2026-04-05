<?php
session_start();
include "../db.php";
include "../header.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "Technician") {
    header("Location: ../login.php");
    exit;
}

$query = "
    SELECT st.sample_test_id, p.patient_name, t.test_name,
           st.result_value, st.performed_at
    FROM sample_test st
    JOIN sample s ON st.sample_id = s.sample_id
    JOIN patient p ON s.patient_id = p.patient_id
    JOIN test t ON st.test_id = t.test_id
    ORDER BY st.performed_at DESC
";

$result = $conn->query($query);
?>

<h3 class="fw-bold mb-4">Lab Test Dashboard</h3>

<div class="card p-4 shadow-sm">
<div class="table-responsive">

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        ✅ Test updated & report generated successfully!
    </div>
<?php endif; ?>

<table class="table align-middle">

<thead>
<tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Test</th>
    <th>Status</th>
    <th>Performed</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
<td><?= $row['sample_test_id'] ?></td>

<td><?= $row['patient_name'] ?></td>

<td><?= $row['test_name'] ?></td>

<td>
<?php if ($row['result_value']): ?>
    <span class="badge bg-success">Completed</span>
<?php else: ?>
    <span class="badge bg-warning text-dark">Pending</span>
<?php endif; ?>
</td>

<td>
<?= $row['performed_at'] ? date("d M Y", strtotime($row['performed_at'])) : "-" ?>
</td>

<td>
<a href="/lab-workflow/technician/update_test.php?id=<?= $row['sample_test_id'] ?>" 
   class="btn btn-sm btn-primary">
   Update
</a>
</td>

</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>
</div>

<?php include "../footer.php"; ?>