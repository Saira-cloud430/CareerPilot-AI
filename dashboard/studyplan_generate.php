<?php

require_once "../config.php";
require_once "../api/gemini.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$technology = mysqli_real_escape_string($conn, $_POST['technology']);
$level = mysqli_real_escape_string($conn, $_POST['level']);
$hours = (int)$_POST['hours'];
$weeks = (int)$_POST['weeks'];

$prompt = "

Create a personalized study plan.

Technology: $technology

Current Level: $level

Hours Available Per Day: $hours

Duration: $weeks weeks

Generate:

1. Weekly roadmap

2. Daily learning schedule

3. Best free learning resources

4. Practice exercises

5. Mini projects

6. Final portfolio project

7. Interview preparation tips

8. Productivity tips

Format the answer using headings and bullet points.

";

$response = askGemini($prompt);

$user_id = $_SESSION['user_id'];

$study_plan = mysqli_real_escape_string($conn, $response);

mysqli_query($conn,

"INSERT INTO study_plans
(user_id, technology, level, hours, weeks, study_plan)

VALUES
('$user_id','$technology','$level','$hours','$weeks','$study_plan')");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Your AI Study Plan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>Your AI Study Plan</h3>

</div>

<div class="card-body">

<?= nl2br(htmlspecialchars($response)); ?>

<hr>

<a href="studyplan.php" class="btn btn-success">

Generate Another Plan

</a>

</div>

</div>

</div>

</body>

</html>