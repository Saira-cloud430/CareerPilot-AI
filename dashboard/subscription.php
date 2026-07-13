<?php
require_once "../config.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

$id=$_SESSION['user_id'];

$user=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT full_name,email FROM users WHERE id='$id'"));
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#eef3fb;
}

.card{
border:none;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
}

</style>

</head>

<body>

<div class="container mt-5">

<div class="card p-4">

<h2 class="mb-4">

Account Settings

</h2>

<?php
if(isset($_GET['success']))
{
echo "<div class='alert alert-success'>Settings Updated Successfully.</div>";
}
?>

<form action="settings_update.php" method="POST">

<div class="mb-3">

<label>Full Name</label>

<input
type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($user['full_name']) ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user['email']) ?>"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input
type="password"
name="password"
class="form-control"
placeholder="Leave blank to keep current password">

</div>

<button class="btn btn-primary">

Save Changes

</button>

</form>

</div>

</div>

</body>

</html>