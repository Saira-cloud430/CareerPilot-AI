<?php
require_once "../config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h3>Account Settings</h3>

</div>

<div class="card-body">

<a href="profile.php" class="btn btn-primary mb-3 w-100">
Edit Profile
</a>

<a href="../logout.php" class="btn btn-danger w-100">
Logout
</a>

</div>

</div>

</div>

</body>
</html>