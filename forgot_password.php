<?php
require_once "config.php";

$message = "";

if(isset($_POST['reset']))
{
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check)>0)
    {
        $message = "Password reset feature will be available in the Premium version.";
    }
    else
    {
        $message = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Forgot Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-body">

<h3 class="mb-4 text-center">

Forgot Password

</h3>

<?php

if($message!="")
{
    echo "<div class='alert alert-info'>$message</div>";
}

?>

<form method="POST">

<div class="mb-3">

<label>Email Address</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<button
class="btn btn-primary w-100"
name="reset">

Continue

</button>

</form>

<br>

<a href="login.php">

← Back to Login

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>