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

<title>Payment Successful | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

body {

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

}

.success-container {

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;

}

.success-card {

    background: white;

    max-width: 650px;

    width: 100%;

    padding: 55px 40px;

    border-radius: 30px;

    text-align: center;

    box-shadow: 0 20px 50px rgba(0,0,0,.08);

}

.success-icon {

    width: 100px;

    height: 100px;

    border-radius: 50%;

    background: #DCFCE7;

    color: #16A34A;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 50px;

    margin: 0 auto 25px;

}

.success-card h1 {

    font-weight: 800;

    color: #1F2937;

}

.success-card p {

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

<body>

<div class="success-container">

    <div class="success-card">

        <div class="success-icon">

            <i class="fa-solid fa-check"></i>

        </div>

        <h1>Payment Successful!</h1>
        <hr>

<h5>Subscription Details</h5>

<p>

<b>Plan:</b> Premium

</p>

<p>

<b>Duration:</b>

30 Days

</p>

<p>

<b>Status:</b>

Active

</p>
        <p>

            Congratulations! Your CareerPilot AI Premium subscription

            has been activated successfully.

        </p>

        <p>

            You now have access to unlimited AI-powered career tools.

        </p>

        <a href="index.php" class="btn-theme">

            <i class="fa-solid fa-rocket"></i>

            Go to Dashboard

        </a>

    </div>

</div>

</body>

</html>