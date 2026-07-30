<?php

require_once "../config.php";


/* ==============================
   AUTHENTICATION
============================== */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


$user_id = $_SESSION['user_id'];


/* ==============================
   VALIDATE INTERVIEW ID
============================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: my_interviews.php");

    exit();

}


$interview_id = (int) $_GET['id'];


/* ==============================
   DELETE ONLY USER'S OWN INTERVIEW
============================== */

$stmt = mysqli_prepare(

    $conn,

    "DELETE FROM interview_sessions
     WHERE id = ?
     AND user_id = ?"

);


mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $interview_id,

    $user_id

);


mysqli_stmt_execute($stmt);


/* ==============================
   CLOSE STATEMENT
============================== */

mysqli_stmt_close($stmt);


/* ==============================
   REDIRECT TO INTERVIEW HISTORY
============================== */

header("Location: my_interviews.php");

exit();

?>