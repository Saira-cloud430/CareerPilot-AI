<?php

require_once "config.php";

$message="";

if(!isset($_GET['token']))
{
    die("Invalid reset link.");
}

$token=$_GET['token'];

$query=mysqli_prepare($conn,
"SELECT * FROM password_resets
WHERE token=? AND expires_at>NOW()");

mysqli_stmt_bind_param($query,"s",$token);

mysqli_stmt_execute($query);

$result=mysqli_stmt_get_result($query);

if(mysqli_num_rows($result)==0)
{
    die("Reset link is invalid or expired.");
}

$row=mysqli_fetch_assoc($result);

if(isset($_POST['reset']))
{

$password=$_POST['password'];

$confirm=$_POST['confirm_password'];

if($password!=$confirm)
{

$message="Passwords do not match.";

}
else
{

$hashed=password_hash($password,PASSWORD_DEFAULT);

$update=mysqli_prepare($conn,
"UPDATE users SET password=? WHERE email=?");

mysqli_stmt_bind_param($update,"ss",$hashed,$row['email']);

mysqli_stmt_execute($update);

mysqli_query($conn,
"DELETE FROM password_resets WHERE email='".$row['email']."'");

header("Location: login.php?reset=success");

exit();

}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Reset Password | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/auth.css">

</head>

<body class="auth-body">

<div class="auth-card">

<div class="logo-box">

<i class="fa-solid fa-shield-halved"></i>

</div>

<h1>Create New Password</h1>

<p class="subtitle">

Choose a strong password to keep your account secure.

</p>

<?php

if($message!="")
{
echo "<div class='alert alert-danger'>$message</div>";
}

?>

<form method="POST">

<div class="mb-3">

<label>New Password</label>

<div class="input-group">

<span class="input-group-text">

<i class="fa-solid fa-lock"></i>

</span>

<input
id="password"
type="password"
name="password"
class="form-control"
required>

<button
type="button"
class="btn btn-light"
onclick="togglePassword('password',this)">

<i class="fa-solid fa-eye"></i>

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
required>

<button
type="button"
class="btn btn-light"
onclick="togglePassword('confirm',this)">

<i class="fa-solid fa-eye"></i>

</button>

</div>

<small id="match"></small>

</div>

<button
class="btn auth-btn w-100"
name="reset">

Reset Password

</button>

</form>

<div class="text-center mt-4">

<a href="login.php">

← Back to Login

</a>

</div>

</div>

<script>

function togglePassword(id,btn){

let input=document.getElementById(id);

let icon=btn.querySelector("i");

if(input.type==="password"){

input.type="text";

icon.className="fa-solid fa-eye-slash";

}

else{

input.type="password";

icon.className="fa-solid fa-eye";

}

}

const pass=document.getElementById("password");

const confirmPass=document.getElementById("confirm");

const strength=document.getElementById("strength");

const match=document.getElementById("match");

pass.addEventListener("keyup",()=>{

let value=pass.value;

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

confirmPass.addEventListener("keyup",()=>{

if(confirmPass.value===""){

match.innerHTML="";

return;

}

if(confirmPass.value===pass.value){

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

</html>