<?php
session_start();
include "../db.php";
include "../header.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "Receptionist") {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["patient_name"];
    $dob = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $contact = $_POST["contact_number"];
    $email = $_POST["email"];
    $address = $_POST["address"];

    $stmt = $conn->prepare("
        INSERT INTO patient 
        (patient_name, date_of_birth, gender, contact_number, email, address, registration_date) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("ssssss", $name, $dob, $gender, $contact, $email, $address);

    if ($stmt->execute()) {
        header("Location: add_patient.php?success=1");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<h3 class="fw-bold mb-3">Register Patient</h3>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">✅ Patient registered successfully!</div>
<?php endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card p-4">

<form method="POST" id="patientForm">

<div class="row g-4">

<div class="col-md-6">
<label>Full Name</label>
<input type="text" name="patient_name" class="form-control" required>
</div>

<div class="col-md-3">
<label>DOB</label>
<input type="date" name="date_of_birth" class="form-control" required>
</div>

<div class="col-md-3">
<label>Gender</label>
<select name="gender" class="form-control" required>
<option value="">Select</option>
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>
</div>

<div class="col-md-6">
<label>Contact</label>
<input type="text" name="contact_number" class="form-control" required>
</div>

<div class="col-md-6">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="col-12">
<label>Address</label>
<textarea name="address" class="form-control"></textarea>
</div>

</div>

<div class="mt-4 d-flex justify-content-between">
<button id="patientBtn" class="btn btn-primary px-4">Save</button>
<a href="../dashboard.php" class="btn btn-light">Cancel</a>
</div>

</form>
</div>

<script>
handleFormSubmit("patientForm", "patientBtn");
</script>

<?php include "../footer.php"; ?>