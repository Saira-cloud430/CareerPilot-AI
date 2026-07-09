<?php

require_once "../config.php";
require_once "../api/gemini.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request.");
}

$job = trim($_POST['job']);
$experience = trim($_POST['experience']);
$questions = trim($_POST['questions']);

$prompt = "

You are an expert technical interviewer.

Prepare an interview for the following candidate.

Job Role:
$job

Experience Level:
$experience

Generate exactly $questions interview questions.

For each question include:

1. The Interview Question

2. Why the interviewer asks it

3. Tips for answering

4. A sample strong answer

After all questions, give:

• Common interview mistakes
• Final interview preparation tips

Use clear headings and bullet points.

";

$interview = askGemini($prompt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>AI Interview Preparation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2 class="mb-4">

AI Interview Preparation

</h2>

<div class="card shadow">

<div class="card-body">

<pre style="white-space:pre-wrap;font-size:16px;">

<?= htmlspecialchars($interview) ?>

</pre>

</div>

</div>

<br>

<a href="interview.php" class="btn btn-success">

Generate Another Interview

</a>

</div>

</body>

</html>