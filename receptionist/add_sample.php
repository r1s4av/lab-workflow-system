<?php
session_start();
include "../db.php";
include "../header.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "Receptionist") {
    header("Location: ../login.php");
    exit;
}

$patients = $conn->query("SELECT patient_id, patient_name FROM patient ORDER BY patient_name ASC");
$tests = $conn->query("SELECT test_id, test_name FROM test ORDER BY test_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = $_POST["patient_id"];
    $sample_type = $_POST["sample_type"];
    $test_ids = $_POST["test_ids"];

    $stmt = $conn->prepare("
        INSERT INTO sample (sample_type, collection_date, sample_status, patient_id)  
        VALUES (?, NOW(), 'Pending', ?)
    ");

    $stmt->bind_param("si", $sample_type, $patient_id);

    if ($stmt->execute()) {

        $sample_id = $stmt->insert_id;

        foreach ($test_ids as $test_id) {
            $stmt2 = $conn->prepare("
                INSERT INTO sample_test (sample_id, test_id) VALUES (?, ?)
            ");
            $stmt2->bind_param("ii", $sample_id, $test_id);
            $stmt2->execute();
        }

        header("Location: add_sample.php?success=1");
        exit;

    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<h3 class="fw-bold mb-3">Create Sample</h3>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">✅ Sample created successfully!</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card p-4">

<form method="POST" id="sampleForm">

<div class="row g-4">

<div class="col-md-6">
<label>Patient</label>
<select name="patient_id" class="form-control" required>
<option value="">Select Patient</option>
<?php while($p = $patients->fetch_assoc()): ?>
<option value="<?= $p['patient_id'] ?>">
<?= htmlspecialchars($p['patient_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-6">
<label>Sample Type</label>
<input type="text" name="sample_type" class="form-control" required>
</div>

<div class="col-12">
<label>Select Tests</label>
<select name="test_ids[]" class="form-select" multiple required style="height:150px;">
<?php while($t = $tests->fetch_assoc()): ?>
<option value="<?= $t['test_id'] ?>">
<?= htmlspecialchars($t['test_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

</div>

<div class="mt-4 d-flex justify-content-between">
<button id="sampleBtn" class="btn btn-success px-4">Create</button>
<a href="../dashboard.php" class="btn btn-light">Cancel</a>
</div>

</form>
</div>

<script>
handleFormSubmit("sampleForm", "sampleBtn");
</script>

<?php include "../footer.php"; ?>