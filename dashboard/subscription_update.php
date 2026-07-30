<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['plan'])) {

    header("Location: subscription.php");

    exit();

}

$plan = strtolower(trim($_GET['plan']));

if ($plan !== "free") {

    header("Location: payment.php");

    exit();

}


/* ==============================
   SWITCH PREMIUM USER TO FREE
============================== */

mysqli_query(

    $conn,

    "UPDATE subscriptions

     SET status='expired'

     WHERE user_id='$user_id'

     AND status='active'

     AND plan='premium'"

);

header("Location: subscription.php?success=free");

exit();

?>