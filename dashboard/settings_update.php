<?php

require_once "../config.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$name = mysqli_real_escape_string($conn,$_POST['full_name']);

$email = mysqli_real_escape_string($conn,$_POST['email']);

$password = trim($_POST['password'] ?? '');

if($password=="")
{
    $sql = "UPDATE users
            SET full_name='$name',
                email='$email'
            WHERE id='$id'";
}
else
{
    $hash = password_hash($password,PASSWORD_DEFAULT);

    $sql = "UPDATE users
            SET full_name='$name',
                email='$email',
                password='$hash'
            WHERE id='$id'";
}

if(mysqli_query($conn,$sql))
{
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;

    header("Location: settings.php?success=1");
    exit();
}
else
{
    die(mysqli_error($conn));
}