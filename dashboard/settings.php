<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$id = $_SESSION['user_id'];

$query = mysqli_query(

    $conn,

    "SELECT full_name, email
     FROM users
     WHERE id='$id'"

);

$user = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Settings | CareerPilot AI</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
rel="stylesheet">

<style>

* {

    box-sizing: border-box;

}

body {

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    padding: 40px 20px;

    color: #1F2937;

}

.settings-container {

    max-width: 900px;

    margin: auto;

}

.page-header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 35px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37, 99, 235, .20);

}

.page-header h1 {

    margin: 0;

    font-weight: 700;

}

.page-header p {

    margin-top: 10px;

    margin-bottom: 0;

    opacity: .9;

}

.settings-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

.settings-section {

    border: 1px solid #E5E7EB;

    border-radius: 18px;

    padding: 25px;

    margin-bottom: 25px;

}

.settings-section h4 {

    color: #2563EB;

    font-weight: 700;

    margin-bottom: 20px;

}

.form-label {

    font-weight: 600;

    color: #374151;

}

.form-control {

    padding: 12px 15px;

    border-radius: 10px;

    border: 1px solid #D1D5DB;

}

.form-control:focus {

    border-color: #2563EB;

    box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);

}

.password-note {

    font-size: 13px;

    color: #6B7280;

    margin-top: 6px;

}

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    border: none;

    color: white;

    padding: 12px 28px;

    border-radius: 30px;

    font-weight: 600;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

.account-card {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    border-radius: 12px;

    padding: 18px;

    margin-bottom: 25px;

}

.action-buttons {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 25px;

}

</style>

</head>

<body>

<div class="settings-container">

<div class="page-header">

<h1>

<i class="fa-solid fa-gear"></i>

Account Settings

</h1>

<p>

Manage your account information and security preferences.

</p>

</div>

<div class="settings-content">

<?php

if (isset($_GET['success'])) {

?>

<div class="alert alert-success">

<i class="fa-solid fa-circle-check"></i>

Settings updated successfully.

</div>

<?php

}

?>

<div class="account-card">

<i class="fa-solid fa-shield-halved text-primary"></i>

Your account information is secure and can be updated anytime.

</div>

<form action="settings_update.php" method="POST">

<div class="settings-section">

<h4>

<i class="fa-solid fa-user"></i>

Personal Account Information

</h4>

<div class="mb-3">

<label class="form-label">

Full Name

</label>

<input

type="text"

name="full_name"

class="form-control"

value="<?= htmlspecialchars($user['full_name']) ?>"

required>

</div>

<div class="mb-3">

<label class="form-label">

Email Address

</label>

<input

type="email"

name="email"

class="form-control"

value="<?= htmlspecialchars($user['email']) ?>"

required>

</div>

</div>

<div class="settings-section">

<h4>

<i class="fa-solid fa-lock"></i>

Change Password

</h4>

<div class="mb-3">

<label class="form-label">

New Password

</label>

<input

type="password"

name="password"

class="form-control"

placeholder="Leave blank to keep your current password">

<div class="password-note">

Leave this field empty if you do not want to change your password.

</div>

</div>

</div>

<button

type="submit"

class="btn btn-theme">

<i class="fa-solid fa-floppy-disk"></i>

Save Changes

</button>

</form>

<div class="action-buttons">

<a

href="profile.php"

class="btn btn-outline-primary">

<i class="fa-solid fa-user"></i>

Edit Career Profile

</a>

<a

href="../logout.php"

class="btn btn-danger">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</div>

</body>

</html>