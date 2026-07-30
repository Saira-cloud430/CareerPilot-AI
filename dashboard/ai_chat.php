<?php

require_once "../config.php";
require_once "subscription_check.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Career Chat | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

* {
    box-sizing: border-box;
}

body {

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    color: #1F2937;

    padding: 40px 20px;

}

.chat-container {

    max-width: 1000px;

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

    font-weight: 700;

    margin: 0;

}

.page-header p {

    margin-top: 10px;

    opacity: .9;

}

/* CHAT CARD */

.chat-card {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

/* INTRODUCTION */

.chat-introduction {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 30px;

    line-height: 1.7;

}

/* LABEL */

.form-label {

    font-weight: 700;

    color: #374151;

}

/* TEXTAREA */

textarea {

    resize: vertical;

    min-height: 160px;

    border: 2px solid #E5E7EB !important;

    border-radius: 14px !important;

    padding: 16px !important;

    font-size: 16px !important;

}

textarea:focus {

    border-color: #2563EB !important;

    box-shadow: 0 0 0 3px rgba(37, 99, 235, .12) !important;

}

/* BUTTON */

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    border: none;

    color: white;

    padding: 13px 28px;

    border-radius: 30px;

    font-weight: 600;

    transition: .3s;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

    box-shadow: 0 8px 20px rgba(37, 99, 235, .25);

}

/* TIP BOX */

.chat-tip {

    margin-top: 25px;

    padding: 18px;

    background: #EFF6FF;

    border-radius: 12px;

    border-left: 4px solid #2563EB;

    color: #374151;
}
.chat-limit-info {

    margin-top: 18px;

    padding: 14px 18px;

    background: #F8FAFC;

    border-left: 4px solid #7C3AED;

    border-radius: 10px;

    color: #475569;

    font-size: 14px;

}

.chat-limit-info i {

    color: #2563EB;

    margin-right: 6px;

}

.chat-limit-info a {

    color: #2563EB;

    font-weight: 600;

    text-decoration: none;

}

</style>

</head>

<body>

<div class="chat-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-comments"></i>

            AI Career Assistant

        </h1>

        <p>

            Ask CareerPilot AI for guidance on careers, skills, learning, projects, interviews, and more.

        </p>

    </div>

    <div class="chat-card">

        <div class="chat-introduction">

            <i class="fa-solid fa-circle-info text-primary"></i>

            <strong>How can I help you?</strong>

            <br>

            Ask any career-related question and receive personalized guidance from CareerPilot AI.

        </div>

        <form action="ai_chat_generate.php" method="POST">

            <div class="mb-4">

                <label class="form-label">

                    <i class="fa-solid fa-message text-primary"></i>

                    Your Question

                </label>

                <textarea

                    name="question"

                    class="form-control"

                    placeholder="Example: How can I become a Full Stack Developer?"

                    required></textarea>

            </div>

            <button type="submit" class="btn btn-theme">

                <i class="fa-solid fa-paper-plane"></i>

                Ask CareerPilot AI

            </button>

        </form>
        <div class="chat-limit-info">

    <i class="fa-solid fa-circle-info"></i>

    <?php if ($isPremium): ?>

        Premium users can ask up to <strong>30 AI questions per day.</strong>

    <?php else: ?>

        Free users can ask up to <strong>5 AI questions per day.</strong>

        <a href="subscription.php">Upgrade to Premium</a>

    <?php endif; ?>

</div>

<div class="text-center mt-4">

    <a href="my_chats.php" class="btn btn-outline-primary">

        <i class="fa-solid fa-clock-rotate-left"></i>

        View My Chat History

    </a>

</div>
        <div class="chat-tip">

            <i class="fa-solid fa-lightbulb text-primary"></i>

            <strong>Tip:</strong>

            You can ask about career paths, programming languages, projects, interviews, skills, certifications, or learning strategies.

        </div>

    </div>

</div>

</body>

</html>