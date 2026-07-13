<?php

require_once "../config.php";
require_once "../api/gemini.php";

use Smalot\PdfParser\Parser;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (!isset($_FILES['resume'])) {
        die("No file uploaded.");
    }

    $file = $_FILES['resume'];

    if ($file['error'] != 0) {
        die("Upload failed.");
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($extension != "pdf") {
        die("Only PDF files are allowed.");
    }

    $uploadDir = "../uploads/resumes/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = time() . "_" . basename($file['name']);

    $destination = $uploadDir . $filename;

    move_uploaded_file($file['tmp_name'], $destination);

    $parser = new Parser();

    $pdf = $parser->parseFile($destination);

    $resumeText = $pdf->getText();
   
    $prompt = "

You are an experienced ATS Resume Reviewer and Senior HR Recruiter.

Analyze the following resume carefully.

Return your response in this format:

# ATS Resume Score
(Give a score out of 100)

# Summary

# Strengths

# Weaknesses

# Missing Technical Skills

# Missing Soft Skills

# Experience Review

# Education Review

# Projects Review

# Resume Formatting Issues

# ATS Keyword Suggestions

# Interview Readiness

# Final Recommendations

Resume:

$resumeText

";

    $analysis = askGemini($prompt);
$user_id = $_SESSION['user_id'];

$fileName = mysqli_real_escape_string($conn, $filename);

$analysisSafe = mysqli_real_escape_string($conn, $analysis);

$user_id = $_SESSION['user_id'];

$fileName = mysqli_real_escape_string($conn, $filename);

$analysisSafe = mysqli_real_escape_string($conn, $analysis);

$sql = "
INSERT INTO resumes
(user_id, resume_file, ai_feedback)
VALUES
('$user_id','$fileName','$analysisSafe')
";

if (!mysqli_query($conn, $sql)) {
    die("Database Error: " . mysqli_error($conn));
}
}
?>
<!DOCTYPE html>
<html>

<head>

<title>Resume Analysis</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2 class="mb-4">

AI Resume Analysis

</h2>

<div class="card">

<div class="card-body">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>AI Resume Analysis</h3>

</div>

<div class="card-body">

<?= nl2br(htmlspecialchars($analysis)) ?>

</div>

</div>

</div>

</div>

</div>

</body>

</html>