<?php

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$isPremium = false;

$subscription_query = mysqli_query(

    $conn,

    "SELECT plan, status, end_date
     FROM subscriptions
     WHERE user_id='$user_id'
     AND plan='premium'
     AND status='active'
     AND end_date >= CURDATE()
     ORDER BY id DESC
     LIMIT 1"

);

$subscription = mysqli_fetch_assoc($subscription_query);

if($subscription)
{
    $isPremium = true;
}

?>