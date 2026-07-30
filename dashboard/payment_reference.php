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

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card p-5 shadow text-center">

<h2 class="text-success">

Payment Submitted Successfully

</h2>

<p class="mt-3">

Your payment request has been sent to our verification team.

</p>

<p>

Your Premium subscription will be activated after payment verification.

</p>
<p>
    Estimated verification time:
    2–10 minutes (Demo) 
</p>

<a href="index.php" class="btn-theme text-decoration-none text-center d-block mt-3">
    <i class="fa-solid fa-house"></i>
    Back to Dashboard
</a>

</div>

</div>

</body>

</html>