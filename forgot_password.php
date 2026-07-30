<?php
require_once "config.php";
require_once "config_mail.php";

$message = "";

if(isset($_POST['submit']))
{
    $email = trim($_POST['email']);

    $check = mysqli_prepare($conn,"SELECT id FROM users WHERE email=?");
    mysqli_stmt_bind_param($check,"s",$email);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if(mysqli_num_rows($result)==1)
    {
        $token = bin2hex(random_bytes(32));

        $expiry = date("Y-m-d H:i:s",strtotime("+1 hour"));

        mysqli_query($conn,"DELETE FROM password_resets WHERE email='$email'");

        $insert = mysqli_prepare($conn,
        "INSERT INTO password_resets(email,token,expires_at)
        VALUES(?,?,?)");

        mysqli_stmt_bind_param($insert,"sss",$email,$token,$expiry);

        mysqli_stmt_execute($insert);

        $link =
        "http://localhost/CareerPilot-AI/reset_password.php?token=".$token;

        try
        {
            $mail = getMailer();

            $mail->addAddress($email);

            $mail->Subject = "CareerPilot AI Password Reset";

            $mail->isHTML(true);

            $mail->Body = "
            <h2>Password Reset</h2>

            <p>Click the button below to reset your password.</p>

            <a href='$link'
            style='
            background:#2563EB;
            color:white;
            padding:12px 25px;
            text-decoration:none;
            border-radius:8px;
            display:inline-block;
            '>

            Reset Password

            </a>

            <p>This link expires in 1 hour.</p>
            ";

            $mail->send();

            $message =
            "<div class='alert alert-success'>
            Reset link has been sent to your email.
            </div>";
        }
        catch(Exception $e)
        {
            $message =
            "<div class='alert alert-danger'>
            Email could not be sent.
            </div>";
        }
    }
    else
    {
        $message =
        "<div class='alert alert-danger'>
        Email not found.
        </div>";
    }
}
?>

<!DOCTYPE html>

<html>
    <!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Forgot Password | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/auth.css">

</head>

<body class="auth-body">

<div class="auth-card">

<div class="logo-box">
<i class="fa-solid fa-key"></i>
</div>

<h1>Forgot Password?</h1>

<p class="subtitle">
Don't worry. Enter your email and we'll send you a secure password reset link.
</p>
<?php echo $message; ?>
<!-- Success/Error Alert -->

<form method="POST">

<div class="mb-4">

<label>Email Address</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-envelope"></i>
</span>

<input
type="email"
name="email"
class="form-control"
required>

</div>

</div>

<button
class="btn auth-btn w-100"
name="submit">

Send Reset Link

</button>

</form>

<div class="text-center mt-4">

<a href="login.php">

← Back to Login

</a>

</div>

</div>

</body>

</html>