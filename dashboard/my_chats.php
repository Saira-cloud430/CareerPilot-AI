<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM ai_chat_history
     WHERE user_id='$user_id'
     ORDER BY created_at DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My AI Chats | CareerPilot AI</title>

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

/* HEADER */

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

/* CONTENT */

.chat-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

/* CHAT CARD */

.chat-item {

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-left: 5px solid #2563EB;

    border-radius: 15px;

    padding: 22px;

    margin-bottom: 18px;

    transition: .3s;

}

.chat-item:hover {

    transform: translateY(-3px);

    box-shadow: 0 10px 25px rgba(37, 99, 235, .10);

}

.question {

    font-size: 18px;

    font-weight: 700;

    color: #172554;

    margin-bottom: 10px;

}

.question i {

    color: #2563EB;

}

.chat-date {

    color: #64748B;

    font-size: 14px;

    margin-bottom: 15px;

}

.chat-preview {

    color: #475569;

    line-height: 1.6;

    margin-bottom: 18px;

}

.action-buttons {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;

}

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    border: none;

    padding: 10px 20px;

    border-radius: 25px;

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

    padding: 10px 20px;

    border-radius: 25px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-delete:hover {

    background: #DC2626;

    color: white;

}

.btn-dashboard {

    display: inline-block;

    margin-top: 25px;

    background: white;

    color: #2563EB;

    border: 1px solid #2563EB;

    padding: 12px 25px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

}

.btn-dashboard:hover {

    background: #2563EB;

    color: white;

}

.empty-state {

    text-align: center;

    padding: 60px 20px;

    color: #64748B;

}

.empty-state i {

    font-size: 60px;

    color: #2563EB;

    margin-bottom: 20px;

}

.empty-state h4 {

    color: #172554;

    font-weight: 700;

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

            <i class="fa-solid fa-clock-rotate-left"></i>

            My AI Chat History

        </h1>

        <p>

            View and manage your previous conversations with CareerPilot AI.

        </p>

    </div>

    <div class="chat-content">

        <?php if (mysqli_num_rows($query) > 0): ?>

            <?php while ($chat = mysqli_fetch_assoc($query)): ?>

                <div class="chat-item">

                    <div class="question">

                        <i class="fa-solid fa-message me-2"></i>

                        <?= htmlspecialchars($chat['user_message']) ?>

                    </div>

                    <div class="chat-date">

                        <i class="fa-regular fa-calendar me-1"></i>

                        <?= date("d M Y, h:i A", strtotime($chat['created_at'])) ?>

                    </div>

                    <div class="chat-preview">

                        <?= htmlspecialchars(mb_substr($chat['ai_response'], 0, 220)) ?>

                        <?php if (strlen($chat['ai_response']) > 220): ?>

                            ...

                        <?php endif; ?>

                    </div>

                    <div class="action-buttons">

                        <a

                            href="view_chat.php?id=<?= $chat['id'] ?>"

                            class="btn-theme"

                        >

                            <i class="fa-solid fa-eye"></i>

                            View Chat

                        </a>

                        <a

                            href="delete_chat.php?id=<?= $chat['id'] ?>"

                            class="btn-delete"

                            onclick="return confirm('Are you sure you want to delete this chat?');"

                        >

                            <i class="fa-solid fa-trash"></i>

                            Delete

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-state">

                <i class="fa-solid fa-comments"></i>

                <h4>No Chat History Yet</h4>

                <p>

                    Your AI career questions and answers will appear here.

                </p>

                <a href="ai_chat.php" class="btn-theme">

                    <i class="fa-solid fa-comments"></i>

                    Start AI Chat

                </a>

            </div>

        <?php endif; ?>

        <a href="ai_chat.php" class="btn-dashboard">

            <i class="fa-solid fa-arrow-left"></i>

            Ask New Question

        </a>

        <a href="index.php" class="btn-dashboard">

            <i class="fa-solid fa-house"></i>

            Back to Dashboard

        </a>

    </div>

</div>

</body>

</html>