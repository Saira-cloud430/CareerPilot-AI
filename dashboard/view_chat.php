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

$query = mysqli_query(

    $conn,

    "SELECT *

     FROM ai_chat_history

     WHERE id='$chat_id'

     AND user_id='$user_id'"

);

if (mysqli_num_rows($query) == 0) {

    die("Chat not found.");

}

$chat = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View AI Chat | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

* {

    box-sizing: border-box;

}

body {

    margin: 0;

    padding: 40px 20px;

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    color: #1F2937;

}

.chat-container {

    max-width: 1100px;

    margin: auto;

}

.page-header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 40px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37, 99, 235, .20);

}

.page-header h1 {

    margin: 0;

    font-weight: 700;

}

.page-header p {

    margin-top: 10px;

    margin-bottom: 0;

    opacity: .9;

}

.chat-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

.question-box {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 25px;

    border-radius: 15px;

    margin-bottom: 25px;

}

.question-box h5 {

    color: #2563EB;

    font-weight: 700;

    margin-bottom: 12px;

}

.question-box p {

    margin: 0;

    font-size: 18px;

    font-weight: 600;

    line-height: 1.6;

}

.date-box {

    color: #64748B;

    font-size: 14px;

    margin-bottom: 25px;

}

.response-box {

    background: #FFFFFF;

    border: 1px solid #E5E7EB;

    border-left: 6px solid #7C3AED;

    border-radius: 18px;

    padding: 30px;

    line-height: 1.8;

    box-shadow: 0 8px 25px rgba(0, 0, 0, .06);

}

.response-box h2 {

    color: #2563EB;

    font-size: 24px;

    font-weight: 700;

    margin-top: 28px;

    margin-bottom: 12px;

    padding-bottom: 8px;

    border-bottom: 2px solid #E5E7EB;

}

.response-box h3 {

    color: #7C3AED;

    font-size: 20px;

    font-weight: 700;

    margin-top: 20px;

    margin-bottom: 8px;

}

.response-box ul {

    padding-left: 25px;

}

.response-box li {

    margin-bottom: 8px;

}

.action-buttons {

    display: flex;

    gap: 12px;

    flex-wrap: wrap;

    margin-top: 30px;

}

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    border: none;

    padding: 12px 24px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

.btn-delete {

    background: #FEF2F2;

    color: #DC2626;

    border: 1px solid #FECACA;

    padding: 12px 24px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-delete:hover {

    background: #DC2626;

    color: white;

}

.btn-dashboard {

    background: #F1F5F9;

    color: #475569;

    padding: 12px 24px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-dashboard:hover {

    background: #E2E8F0;

    color: #1E293B;

}

@media(max-width:768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .chat-content {

        padding: 25px 20px;

    }

}

</style>

</head>

<body>

<div class="chat-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-comments"></i>

            AI Career Conversation

        </h1>

        <p>

            Review your personalized conversation with CareerPilot AI.

        </p>

    </div>

    <div class="chat-content">

        <div class="question-box">

            <h5>

                <i class="fa-solid fa-user"></i>

                Your Question

            </h5>

            <p>

                <?= htmlspecialchars($chat['user_message']) ?>

            </p>

        </div>

        <div class="date-box">

            <i class="fa-regular fa-calendar"></i>

            <?= date("d M Y, h:i A", strtotime($chat['created_at'])) ?>

        </div>

        <div class="response-box">

            <?php

            $text = htmlspecialchars($chat['ai_response']);

            /* Convert bold Markdown */

            $text = preg_replace(

                '/\*\*(.*?)\*\*/s',

                '<strong>$1</strong>',

                $text

            );

            /* Remove markdown separators */

            $text = preg_replace(

                '/^\s*---+\s*$/m',

                '',

                $text

            );

            /* Convert headings */

            $text = preg_replace(

                '/^##\s*(.*?)$/m',

                '<h2>$1</h2>',

                $text

            );

            $text = preg_replace(

                '/^###\s*(.*?)$/m',

                '<h3>$1</h3>',

                $text

            );

            /* Convert bullet points */

            $text = preg_replace(

                '/^\s*[-*•]\s+(.*?)$/m',

                '<li>$1</li>',

                $text

            );

            /* Wrap list items */

            $text = preg_replace(

                '/((?:<li>.*?<\/li>\s*)+)/s',

                '<ul>$1</ul>',

                $text

            );

            /* Remove excessive blank lines */

            $text = preg_replace(

                "/\n\s*\n+/",

                "\n",

                $text

            );

            /* Convert line breaks */

            $text = nl2br($text);

            echo $text;

            ?>

        </div>

        <div class="action-buttons">

            <a

                href="my_chats.php"

                class="btn-theme"

            >

                <i class="fa-solid fa-clock-rotate-left"></i>

                Back to Chat History

            </a>

            <a

                href="ai_chat.php"

                class="btn-theme"

            >

                <i class="fa-solid fa-comments"></i>

                Ask New Question

            </a>

            <a

                href="delete_chat.php?id=<?= $chat['id'] ?>"

                class="btn-delete"

                onclick="return confirm('Are you sure you want to delete this chat?');"

            >

                <i class="fa-solid fa-trash"></i>

                Delete Chat

            </a>

            <a

                href="index.php"

                class="btn-dashboard"

            >

                <i class="fa-solid fa-house"></i>

                Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>