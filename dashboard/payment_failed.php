<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$payment_id = intval($_GET['id'] ?? 0);

mysqli_query(

    $conn,

    "UPDATE payments

     SET payment_status='failed'

     WHERE id='$payment_id'

     AND user_id='$user_id'

     AND payment_status='pending'"

);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Payment Failed</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container text-center mt-5">

<div class="card shadow p-5">

<h1 class="text-danger">

Payment Failed

</h1>

<p class="mt-3">

Your payment was not completed.

</p>

<a
href="payment.php"
class="btn btn-primary">

Try Again

</a>

</div>

</div>

</body>

</html>