<?php

require_once "../config.php";
require_once "../api/gemini.php";
require_once "subscription_check.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$question = trim($_POST['question']);

$user_id = $_SESSION['user_id'];

/* ==============================
   AI CHAT DAILY LIMIT
============================== */

$daily_limit = $isPremium ? 30 : 5;

$limit_query = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total
     FROM ai_chat_history
     WHERE user_id='$user_id'
     AND DATE(created_at)=CURDATE()"

);

$limit_data = mysqli_fetch_assoc($limit_query);

$used_today = (int) $limit_data['total'];

if ($used_today >= $daily_limit) {

    $limit_message = $isPremium

        ? "You have used your 30 AI questions for today. Your daily limit will reset tomorrow."

        : "Free users can ask 5 AI questions per day. Upgrade to Premium to unlock 30 AI questions per day.";

    die("

<!DOCTYPE html>

<html lang='en'>

<head>

<meta charset='UTF-8'>

<meta name='viewport' content='width=device-width, initial-scale=1.0'>

<title>AI Chat Limit Reached | CareerPilot AI</title>

<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css' rel='stylesheet'>

<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>

<style>

body {

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

}

.limit-container {

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 20px;

}

.limit-card {

    max-width: 550px;

    width: 100%;

    background: white;

    border-radius: 25px;

    padding: 45px;

    text-align: center;

    box-shadow: 0 20px 50px rgba(0,0,0,.10);

}

.limit-icon {

    width: 90px;

    height: 90px;

    margin: 0 auto 25px;

    border-radius: 50%;

    background: linear-gradient(135deg,#2563EB,#7C3AED);

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 40px;

}

.limit-card h2 {

    color: #172554;

    font-weight: 700;

    margin-bottom: 15px;

}

.limit-card p {

    color: #64748B;

    font-size: 16px;

    line-height: 1.7;

}

.btn-upgrade {

    display: inline-block;

    margin-top: 20px;

    padding: 13px 28px;

    border-radius: 30px;

    background: linear-gradient(135deg,#2563EB,#7C3AED);

    color: white;

    text-decoration: none;

    font-weight: 600;

}

.btn-upgrade:hover {

    color: white;

}

.btn-back {

    display: block;

    margin-top: 15px;

    color: #64748B;

    text-decoration: none;

}

</style>

</head>

<body>

<div class='limit-container'>

<div class='limit-card'>

<div class='limit-icon'>

<i class='fa-solid fa-comments'></i>

</div>

<h2>Daily AI Chat Limit Reached</h2>

<p>

$limit_message

</p>

<a href='subscription.php' class='btn-upgrade'>

<i class='fa-solid fa-crown'></i>

Upgrade to Premium to unlock 30 AI questions per day.

</a>

<a href='ai_chat.php' class='btn-back'>

<i class='fa-solid fa-arrow-left'></i>

Back to AI Career Assistant

</a>

</div>

</div>

</body>

</html>

");

}

$prompt = "

You are CareerPilot AI, an expert career assistant.

Answer the following career-related question in a clear, beginner-friendly way.

Question:
$question

Your answer should include:

## Explanation

Explain the topic clearly.

## Step-by-Step Guidance

Give practical steps.

## Practical Tips

Give useful advice.

## Useful Resources

Mention resources if applicable.

## Motivation

Encourage the learner.

Use clear headings and bullet points.

Do not use unnecessary symbols or decorative formatting.

";

$response = askGemini($prompt);

$user_message = mysqli_real_escape_string($conn, $question);

$ai_response = mysqli_real_escape_string($conn, $response);

mysqli_query(

    $conn,

    "INSERT INTO ai_chat_history
    (user_id, user_message, ai_response)
    VALUES
    ('$user_id', '$user_message', '$ai_response')"

);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Career Assistant | CareerPilot AI</title>

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

    opacity: .9;

}

/* CONTENT */

.chat-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

/* QUESTION */

.question-box {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 30px;

}

.question-box h5 {

    color: #2563EB;

    font-weight: 700;

}

.question-box p {

    margin: 0;

    font-size: 17px;

    font-weight: 600;

}

/* AI RESPONSE */

.ai-response {

    background: #FFFFFF;

    border: 1px solid #E5E7EB;

    border-left: 6px solid #7C3AED;

    border-radius: 18px;

    padding: 30px;

    line-height: 1.7;

    box-shadow: 0 8px 25px rgba(0, 0, 0, .06);

}

/* HEADINGS */

.ai-response h2 {

    color: #2563EB;

    font-size: 24px;

    font-weight: 700;

    margin-top: 28px;

    margin-bottom: 12px;

    padding-bottom: 8px;

    border-bottom: 2px solid #E5E7EB;

}

.ai-response h3 {

    color: #7C3AED;

    font-size: 20px;

    font-weight: 700;

    margin-top: 20px;

    margin-bottom: 8px;

}

/* LISTS */

.ai-response ul {

    padding-left: 25px;

    margin-top: 5px;

}

.ai-response li {

    margin-bottom: 6px;

}

/* BUTTONS */

.action-buttons {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 30px;

}

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    border: none;

    color: white;

    padding: 12px 25px;

    border-radius: 30px;

    font-weight: 600;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

</style>

</head>

<body>

<div class="chat-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-robot"></i>

            CareerPilot AI Assistant

        </h1>

        <p>

            Personalized guidance to help you grow your career and technical skills.

        </p>

    </div>

    <div class="chat-content">

        <div class="question-box">

            <h5>

                <i class="fa-solid fa-user"></i>

                Your Question

            </h5>

            <p>

                <?= htmlspecialchars($question) ?>

            </p>

        </div>

        <div class="ai-response">

            <?php

            $text = htmlspecialchars($response);

            /* Convert bold Markdown */

            $text = preg_replace(

                '/\*\*(.*?)\*\*/s',

                '<strong>$1</strong>',

                $text

            );

            /* Remove Markdown separators */

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

            /* Remove unnecessary blank lines */

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

            <a href="ai_chat.php" class="btn btn-theme">

                <i class="fa-solid fa-comments"></i>

                Ask Another Question

            </a>

            <a href="index.php" class="btn btn-secondary">

                <i class="fa-solid fa-house"></i>

                Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>