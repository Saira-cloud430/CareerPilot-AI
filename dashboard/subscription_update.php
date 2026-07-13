<?php

require_once "../config.php";

if(!isset($_SESSION['user_id']))
{
header("Location: ../login.php");
exit();
}

$id=$_SESSION['user_id'];

$plan=$_GET['plan'];

mysqli_query($conn,

"UPDATE users
SET plan='$plan'
WHERE id='$id'");

header("Location: subscription.php");