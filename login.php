<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($password === $user["password"]) {
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid Password";
        }
    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Lab Workflow</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: radial-gradient(circle at top right, #1e3a8a, #0ea5e9);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(15px);
    padding:35px;
    border-radius:20px;
    width:350px;
    color:white;
    box-shadow:0 20px 50px rgba(0,0,0,0.3);
}

input{
    border-radius:10px !important;
}
</style>

</head>
<body>

<div class="login-box">

<h3 class="text-center mb-4">Login</h3>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= $error ?>
    </div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<button type="submit" class="btn btn-primary w-100">
    Login
</button>

</form>

</div>

</body>
</html>