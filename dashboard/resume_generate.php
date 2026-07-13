<?php

require_once "../config.php";
require_once "../api/gemini.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_FILES['resume'])) {
    die("No file uploaded.");
}

$user_id = $_SESSION['user_id'];

$file = $_FILES['resume'];

$allowed = ['pdf', 'doc', 'docx'];

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowed)) {
    die("Only PDF, DOC and DOCX files are allowed.");
}

$newName = time() . "_" . basename($file['name']);

$destination = "../uploads/" . $newName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    die("Failed to upload file.");
}

$prompt = "

You are an expert ATS Resume Reviewer.

The user has uploaded a resume.

Since the resume text is not extracted yet, provide professional resume improvement guidance.

Include:

1. ATS Score (estimate)
2. Strengths
3. Weaknesses
4. Missing Sections
5. Skills to Add
6. Formatting Suggestions
7. Final Verdict

Keep the response professional and easy to understand.

";

$response = askGemini($prompt);

$fileName = mysqli_real_escape_string($conn, $newName);
$feedback = mysqli_real_escape_string($conn, $response);

mysqli_query(
    $conn,
    "INSERT INTO resumes(user_id,resume_file,ai_feedback)
    VALUES('$user_id','$fileName','$feedback')"
);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Resume Analysis</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>AI Resume Analysis</h3>

</div>

<div class="card-body">

<?= nl2br(htmlspecialchars($response)) ?>

<hr>

<a href="resume.php" class="btn btn-primary">

Analyze Another Resume

</a>

</div>

</div>

</div>

</body>

</html>