<?php
session_start();
include "../db.php";
include "../header.php";

/* DEBUG */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* AUTH */
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "Technician") {
    header("Location: ../login.php");
    exit;
}

/* VALIDATE ID */
if (!isset($_GET["id"])) {
    header("Location: view_tests.php");
    exit;
}

$id = intval($_GET["id"]);

/* FETCH DATA */
$stmt = $conn->prepare("
    SELECT st.sample_test_id, p.patient_name, t.test_name, st.result_value
    FROM sample_test st
    JOIN sample s ON st.sample_id = s.sample_id
    JOIN patient p ON s.patient_id = p.patient_id
    JOIN test t ON st.test_id = t.test_id
    WHERE st.sample_test_id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Invalid Test ID</div>";
    include "../footer.php";
    exit;
}

$data = $result->fetch_assoc();

/* UPDATE LOGIC */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $result_value = trim($_POST["result_value"]);

    if (!empty($result_value)) {

        /* ✅ STEP 1: UPDATE TEST RESULT */
        $stmt2 = $conn->prepare("
            UPDATE sample_test 
            SET result_value = ?, performed_at = NOW()
            WHERE sample_test_id = ?
        ");

        $stmt2->bind_param("si", $result_value, $id);

        if (!$stmt2->execute()) {
            die("Update Error: " . $conn->error);
        }

        /* ✅ STEP 2: CHECK IF REPORT EXISTS */
        $check = $conn->prepare("
            SELECT report_id FROM report WHERE sample_test_id = ?
        ");
        $check->bind_param("i", $id);
        $check->execute();
        $res = $check->get_result();

        /* ✅ STEP 3: INSERT REPORT ONLY IF NOT EXISTS */
        if ($res->num_rows == 0) {

            $stmt3 = $conn->prepare("
                INSERT INTO report 
                (sample_test_id, generated_date, status, remarks, approved_by)
                VALUES (?, NOW(), 'Pending', '', NULL)
            ");

            $stmt3->bind_param("i", $id);

            if (!$stmt3->execute()) {
                die("Report Insert Error: " . $conn->error);
            }
        }

        /* SUCCESS */
        header("Location: view_tests.php?success=1");
        exit;

    } else {
        $error = "Result cannot be empty!";
    }
}
?>

<h3 class="fw-bold mb-4">Update Test Result</h3>

<div class="card p-4">

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<label>Patient</label>
<input type="text" class="form-control"
       value="<?= htmlspecialchars($data['patient_name']) ?>" disabled>
</div>

<div class="mb-3">
<label>Test</label>
<input type="text" class="form-control"
       value="<?= htmlspecialchars($data['test_name']) ?>" disabled>
</div>

<div class="mb-3">
<label>Result</label>
<input type="text" name="result_value" class="form-control"
       value="<?= htmlspecialchars($data['result_value']) ?>" required>
</div>

<button class="btn btn-success">Save Result</button>

<a href="view_tests.php" class="btn btn-secondary">Cancel</a>

</form>
</div>

<?php include "../footer.php"; ?>