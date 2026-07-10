<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);

$email = mysqli_real_escape_string($conn, $_POST['email']);

$sql = "UPDATE users
SET
full_name='$full_name',
email='$email'
WHERE id='$id'";

mysqli_query($conn, $sql);

header("Location: profile.php?success=1");

exit();