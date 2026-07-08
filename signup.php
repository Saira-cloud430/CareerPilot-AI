<?php
require_once "config.php";

if (isset($_POST['signup'])) {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if(strlen($password) < 8)
{
    $error = "Password must be at least 8 characters.";
}
    $confirm_password = $_POST['confirm_password'];

    // Password Match Check
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }

    // Check Email
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $error = "Email already exists.";
    }

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert User
    $insert = mysqli_prepare(
        $conn,
        "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $insert,
        "sss",
        $full_name,
        $email,
        $hashed_password
    );

    if (mysqli_stmt_execute($insert)) {

        echo "<script>
        alert('Account Created Successfully!');
        window.location='login.php';
        </script>";

        exit();

    } else {

        $error = "Something went wrong. Please try again.";

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Signup | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<?php if(isset($error)): ?>

<div class="alert alert-danger">

    <?php echo $error; ?>

</div>

<?php endif; ?>

<h2 class="mb-4">Create Account</h2>

<form method="POST">

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="full_name"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<button
class="btn btn-primary w-100"
name="signup">

Create Account

</button>

</form>

</div>

</div>

</div>

</body>

</html>