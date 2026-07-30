<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$reference = mysqli_real_escape_string(
    $conn,
    $_POST['reference']
);

mysqli_query(

    $conn,

    "UPDATE payments

     SET transaction_reference='$reference'

     WHERE user_id='$user_id'

     AND payment_status='pending'

     ORDER BY id DESC

     LIMIT 1"

);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Reference Submitted</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

}

h1 {

    font-weight: 800;

    color: #1F2937;

}

p {

    color: #64748B;

    font-size: 17px;

    line-height: 1.7;

}

.btn-theme {

    background: linear-gradient(135deg,#2563EB,#7C3AED);

    border: none;

    color: white;

    padding: 13px 30px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    display: inline-block;

    margin-top: 20px;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}
</style>
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card p-5 shadow text-center">

<h2 class="text-success">

Payment Request Submitted

</h2>

<p class="mt-3">

Your payment request has been sent to our verification team.

</p>

<p>

Your Premium subscription will be activated after payment verification.

</p>
<p>
    Payment verification usually takes a few minutes. (Demo) 
</p>

<a href="index.php" class="btn-theme">

            <i class="fa-solid fa-rocket"></i>

            Go to Dashboard

        </a>
</div>

</div>

</body>

</html>