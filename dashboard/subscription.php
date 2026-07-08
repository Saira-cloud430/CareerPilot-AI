<?php
require_once "../config.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}
?>

<h2>Coming Soon</h2>