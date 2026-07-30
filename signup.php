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

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Sign Up | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/auth.css">

</head>

<body>

<body class="auth-body">

<div class="auth-card">

<div class="logo-box">

<i class="fa-solid fa-user-plus"></i>

</div>

<h1>Create Account</h1>

<p class="subtitle">

Start your AI career journey today.

</p>

<?php
if(isset($error))
{
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<div class="mb-3">

<label>Full Name</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-user"></i>
</span>

<input
type="text"
name="full_name"
class="form-control"
placeholder="Enter your full name"
required>

</div>

</div>

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

<div class="mb-3">

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
placeholder="Create password"
required>

<button
type="button"
class="btn btn-light"
onclick="togglePassword('password','eye1')">

<i id="eye1" class="fa-solid fa-eye"></i>

</button>

</div>

<small id="strength" class="text-muted"></small>

</div>

<div class="mb-4">

<label>Confirm Password</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-lock"></i>
</span>

<input
id="confirm"
type="password"
name="confirm_password"
class="form-control"
placeholder="Confirm password"
required>

<button
type="button"
class="btn btn-light"
onclick="togglePassword('confirm','eye2')">

<i id="eye2" class="fa-solid fa-eye"></i>

</button>

</div>

<small id="match"></small>

</div>

<button
class="btn auth-btn w-100"
name="signup">

Create Account

</button>

<div class="text-center mt-4">

Already have an account?

<a href="login.php">

Login

</a>

</div>

</form>

</div>

<script>

function togglePassword(id,iconId){

let input=document.getElementById(id);

let icon=document.getElementById(iconId);

if(input.type==="password"){

input.type="text";

icon.className="fa-solid fa-eye-slash";

}
else{

input.type="password";

icon.className="fa-solid fa-eye";

}

}

const password=document.getElementById("password");

const confirm=document.getElementById("confirm");

const strength=document.getElementById("strength");

const match=document.getElementById("match");

password.addEventListener("keyup",()=>{

let value=password.value;

if(value.length<8){

strength.innerHTML="Weak Password";

strength.style.color="red";

}
else if(value.length<12){

strength.innerHTML="Medium Password";

strength.style.color="orange";

}
else{

strength.innerHTML="Strong Password";

strength.style.color="green";

}

});

confirm.addEventListener("keyup",()=>{

if(confirm.value===""){

match.innerHTML="";
return;

}

if(confirm.value===password.value){

match.innerHTML="Passwords Match ✓";

match.style.color="green";

}
else{

match.innerHTML="Passwords Do Not Match";

match.style.color="red";

}

});

</script>

</body>

</body>

</html>