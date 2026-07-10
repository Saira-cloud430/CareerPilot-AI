<?php

require_once "../config.php";
require_once "../api/gemini.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$question = trim($_POST['question']);

$prompt = "

You are CareerPilot AI, an expert career assistant.

Answer the following career-related question in a clear, beginner-friendly way.

Question:
$question

Your answer should include:

1. Explanation
2. Step-by-step guidance
3. Practical tips
4. Useful resources (if applicable)
5. Motivation for the learner

Use headings and bullet points.

";

$response = askGemini($prompt);

$user_id = $_SESSION['user_id'];

$user_message = mysqli_real_escape_string($conn, $question);

$ai_response = mysqli_real_escape_string($conn, $response);

mysqli_query(
    $conn,
    "INSERT INTO ai_chat_history(user_id, user_message, ai_response)
     VALUES('$user_id','$user_message','$ai_response')"
);

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>CareerPilot AI Chat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h3>CareerPilot AI Response</h3>

</div>

<div class="card-body">

<h5>Your Question</h5>

<p><?= htmlspecialchars($question) ?></p>

<hr>

<?= nl2br(htmlspecialchars($response)) ?>

<hr>

<a href="ai_chat.php" class="btn btn-dark">

Ask Another Question

</a>

</div>

</div>

</div>

</body>

</html>