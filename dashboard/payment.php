<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = mysqli_query(
    $conn,
    "SELECT full_name, email
     FROM users
     WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($user_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Premium Payment | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

body {

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

}

.payment-container {

    max-width: 900px;

    margin: 60px auto;

}

.payment-card {

    background: white;

    border-radius: 25px;

    padding: 40px;

    box-shadow: 0 20px 50px rgba(0,0,0,.08);

}

.payment-header {

    background: linear-gradient(135deg,#2563EB,#7C3AED);

    color: white;

    padding: 30px;

    border-radius: 20px;

    margin-bottom: 30px;

}

.price {

    font-size: 45px;

    font-weight: 800;

}

.form-control {

    padding: 13px;

    border-radius: 10px;

}

.pay-btn {

    width: 100%;

    padding: 15px;

    border: none;

    border-radius: 30px;

    background: linear-gradient(135deg,#2563EB,#7C3AED);

    color: white;

    font-size: 17px;

    font-weight: 700;

}

.pay-btn:hover {

    transform: translateY(-2px);

    color: white;

}

</style>

</head>

<body>

<div class="payment-container">

<div class="payment-card">

<div class="payment-header">

<h2>

<i class="fa-solid fa-crown"></i>

CareerPilot AI Premium

</h2>

<p>

Unlock unlimited AI-powered career tools.

</p>

<div class="price">

$5 <small>/ month</small>

</div>

</div>

<div class="row">

<div class="col-md-6">

<h4>Premium Benefits</h4>

<ul class="list-group mt-3">

<li class="list-group-item">

<i class="fa-solid fa-check text-success"></i>

30 AI Career Chat Messages Per Day

</li>

<li class="list-group-item">

<i class="fa-solid fa-check text-success"></i>

10 Resume Analyses Per Month

</li>

<li class="list-group-item">

<i class="fa-solid fa-check text-success"></i>

30 Interview Sessions Per Month

</li>

<li class="list-group-item">

<i class="fa-solid fa-check text-success"></i>

Personalized Career Roadmaps

</li>

</ul>

</div>

<div class="col-md-6">

<h4>Payment Details</h4>

<form action="payment_process.php" method="POST">

    <label class="form-label fw-semibold">
        Payment Method
    </label>

    <select name="payment_method" class="form-select payment-select" required>

        <option value="">
            Select Payment Method
        </option>

        <option value="raast">Bank Transfer / Raast (Pakistan)</option>


<option value="stripe">International Card (Coming Soon)</option>

    </select>

    <button type="submit" class="btn btn-theme mt-4">

        <i class="fa-solid fa-lock"></i>

        Continue to Secure Payment

    </button>

</form>

</div>

</div>

<div class="text-center mt-4">

<a href="subscription.php" class="btn btn-outline-secondary mt-4">
    <i class="fa-solid fa-arrow-left"></i>
    Back
</a>

</div>

</div>

</div>

</body>

</html>