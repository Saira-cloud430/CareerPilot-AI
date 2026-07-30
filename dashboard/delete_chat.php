<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {

    header("Location: my_chats.php");

    exit();

}

$chat_id = intval($_GET['id']);

/*
    Delete only the chat that belongs
    to the currently logged-in user.
*/

$sql = "

    DELETE FROM ai_chat_history

    WHERE id='$chat_id'

    AND user_id='$user_id'

";

if (mysqli_query($conn, $sql)) {

    header("Location: my_chats.php");

    exit();

} else {

    die("Unable to delete chat.");

}

?>