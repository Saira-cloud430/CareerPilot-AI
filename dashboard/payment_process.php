<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== "POST") {

    header("Location: payment.php");

    exit();

}

$payment_method = $_POST['payment_method'];

$amount = 5.00;

$transaction_id = "TXN_" . strtoupper(uniqid());

$payment_method_safe = mysqli_real_escape_string(
    $conn,
    $payment_method
);

$transaction_id_safe = mysqli_real_escape_string(
    $conn,
    $transaction_id
);


/*
|--------------------------------------------------------------------------
| DEMO PAYMENT
|--------------------------------------------------------------------------
| Real payment gateway ke baghair hum payment ko completed maan rahe hain.
*/

$payment_query = mysqli_query(

    $conn,

    "INSERT INTO payments
    (
        user_id,
        amount,
        payment_method,
        transaction_id,
        payment_status
    )

    VALUES

    (
        '$user_id',
        '$amount',
        '$payment_method_safe',
        '$transaction_id_safe',
        'pending'
    )"

);


if (!$payment_query) {

    die("Payment Error: " . mysqli_error($conn));

}

header("Location: payment_pending.php");

exit();