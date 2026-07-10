<?php
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>My Profile</h3>

</div>

<div class="card-body">

<?php if(isset($_GET['success'])){ ?>

<div class="alert alert-success">

Profile Updated Successfully.

</div>

<?php } ?>

<form action="profile_update.php" method="POST">

<div class="mb-3">

<label>Name</label>

<input
type="text"
name="full_name"
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

<button class="btn btn-primary">

Update Profile

</button>

</form>

</div>

</div>

</div>

</body>

</html>