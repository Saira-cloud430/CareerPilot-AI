<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Payment Pending | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

body{
    background:#F5F7FF;
    font-family:'Segoe UI',sans-serif;
}

.card-box{

    max-width:700px;
    margin:60px auto;
    background:white;
    border-radius:20px;
    padding:40px;
    box-shadow:0 15px 35px rgba(0,0,0,.08);

}
.btn-theme{
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#2563EB,#7C3AED);
    color:white;
    font-weight:600;
    transition:.3s;
}

.btn-theme:hover{
    color:white;
    transform:translateY(-2px);
}
</style>

</head>

<body>

<div class="card-box">

<h2 class="mb-4">

<i class="fa-solid fa-clock text-warning"></i>

Payment Pending

</h2>

<p>

Your Premium subscription request has been created successfully.

</p>

<p>

Please transfer the subscription amount using your preferred
Bank Transfer / Raast method.

</p>

<p>

After making the payment, enter your Transaction Reference below.

</p>

<form action="payment_reference.php" method="POST">

<div class="mb-3">

<label class="form-label">

Transaction Reference

</label>

<input
type="text"
name="reference"
class="form-control"
placeholder="Enter transaction reference"
required>

</div>

<button class="btn-theme">
    <i class="fa-solid fa-paper-plane"></i>
    Submit Reference
</button>
</form>

</div>

</body>

</html>