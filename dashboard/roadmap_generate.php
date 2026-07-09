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

$career = trim($_POST['career']);
$education = trim($_POST['education']);
$level = trim($_POST['level']);
$duration = trim($_POST['duration']);

$prompt = "

You are an experienced AI Career Mentor.

Create a detailed career roadmap.

Career Goal:
$career

Current Education:
$education

Current Skill Level:
$level

Target Duration:
$duration

Generate:

1. Learning Phases

2. Skills to Learn

3. Recommended Projects

4. Certifications

5. Portfolio Tips

6. Interview Preparation

7. Final Career Advice

Make the roadmap easy to read using headings and bullet points.

";

$roadmap = askGemini($prompt);

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Career Roadmap</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2 class="mb-4">

Your AI Career Roadmap

</h2>

<div class="card shadow">

<div class="card-body">

<pre style="white-space:pre-wrap;font-size:16px;">

<?= htmlspecialchars($roadmap) ?>

</pre>

</div>

</div>

<br>

<a href="roadmap.php" class="btn btn-primary">

Generate Another Roadmap

</a>

</div>

</body>

</html>