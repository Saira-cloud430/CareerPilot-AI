<?php
session_start();
require_once "config.php";

$error = "";
$success = "";

if(isset($_GET['reset']))
{
    $success = "Password changed successfully. Please login with your new password.";
}

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn,"SELECT * FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result)==1)
    {
        $user=mysqli_fetch_assoc($result);

        if(password_verify($password,$user['password']))
        {
            $_SESSION['user_id']=$user['id'];
            $_SESSION['user_name']=$user['full_name'];
            $_SESSION['user_email']=$user['email'];

            header("Location: dashboard/index.php");
            exit();
        }
        else
        {
            $error="Invalid Password!";
        }
    }
    else
    {
        $error="Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/auth.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

</head>

<body class="auth-body">

<div class="auth-card">

<div class="logo-box">
<i class="fa-solid fa-rocket"></i>
</div>

<h1>CareerPilot AI</h1>

<p class="subtitle">
Your AI Career Assistant
</p>

<?php
if($success!="")
{
echo "<div class='alert alert-success'>$success</div>";
}

if($error!="")
{
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<div class="mb-3">

<label>Email Address</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-envelope"></i>
</span>

<input
type="email"
name="email"
class="form-control"
placeholder="Enter your email"
required>

</div>

</div>

<div class="mb-4">

<label>Password</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-lock"></i>
</span>

<input
id="password"
type="password"
name="password"
class="form-control"
placeholder="Enter your password"
required>

<button
type="button"
class="btn btn-light"
onclick="togglePassword()">

<i id="eye" class="fa-solid fa-eye"></i>

</button>

</div>

</div>

<button
type="submit"
name="login"
class="btn auth-btn w-100">

Login

</button>

<div class="text-center mt-4">

<a href="forgot_password.php">

Forgot Password?

</a>

</div>

<div class="text-center mt-3">

Don't have an account?

<a href="signup.php">

Create One

</a>

</div>

</form>

</div>

<script>

function togglePassword(){

let pass=document.getElementById("password");

let eye=document.getElementById("eye");

if(pass.type==="password")
{
pass.type="text";
eye.className="fa-solid fa-eye-slash";
}
else
{
pass.type="password";
eye.className="fa-solid fa-eye";
}

}

</script>

</body>

</html>