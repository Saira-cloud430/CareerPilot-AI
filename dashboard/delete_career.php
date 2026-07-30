<?php

require_once "../config.php";


if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


$user_id = $_SESSION['user_id'];


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: my_career.php");

    exit();

}


$roadmap_id = (int) $_GET['id'];


$stmt = mysqli_prepare(

    $conn,

    "DELETE FROM career_roadmaps

     WHERE id = ?

     AND user_id = ?"

);


mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $roadmap_id,

    $user_id

);


mysqli_stmt_execute($stmt);


header("Location: my_career.php");

exit();

?>