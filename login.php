<?php
session_start();
require_once "config.php";

$error = "";

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";

    $stmt = mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param($stmt,"s",$email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result)==1)
    {
        $user = mysqli_fetch_assoc($result);

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
<html>

<head>

<title>Login | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<h2 class="mb-4">Login</h2>

<?php
if($error!="")
{
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

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

<button
class="btn btn-primary w-100"
name="login">

Login

</button>

</form>

</div>

</div>

</div>

</body>

</html>