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
You are an ATS Resume Expert.

Analyze this resume.

Give:

1. Resume Score out of 100

2. Strengths

3. Weaknesses

4. Missing Skills

5. ATS Improvements

6. Final Suggestions

Resume:

$resumeText
";

    $analysis = askGemini($prompt);
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

<pre style="white-space:pre-wrap;font-size:16px;">

<?= htmlspecialchars($analysis) ?>

</pre>

</div>

</div>

</div>

</body>

</html>