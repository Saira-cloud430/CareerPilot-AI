<?php

session_start();

require_once "../config.php";
require_once "../api/gemini.php";
require_once "subscription_check.php";

use Smalot\PdfParser\Parser;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {

    header("Location: resume.php");

    exit();
}


/* ==============================
   RESUME ANALYSIS LIMIT
============================== */

if ($isPremium) {

    /* ==============================
   RESUME ANALYSIS MONTHLY LIMIT
============================== */

// Premium users: 10 resume analyses per month
// Free users: 1 resume analysis per month

$monthly_limit = $isPremium ? 10 : 1;

$limit_query = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total
     FROM resumes
     WHERE user_id='$user_id'
     AND MONTH(uploaded_at)=MONTH(CURDATE())
     AND YEAR(uploaded_at)=YEAR(CURDATE())"

);

$limit_data = mysqli_fetch_assoc($limit_query);

if ($limit_data['total'] >= $monthly_limit) {

    if ($isPremium) {

        die("

        <!DOCTYPE html>

        <html lang='en'>

        <head>

        <meta charset='UTF-8'>

        <meta name='viewport' content='width=device-width, initial-scale=1.0'>

        <title>Premium Limit Reached | CareerPilot AI</title>

        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css' rel='stylesheet'>

        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>

        </head>

        <body style='background:#F5F7FF;font-family:Segoe UI,sans-serif;'>

        <div style='min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;'>

        <div style='max-width:550px;width:100%;background:white;border-radius:25px;padding:45px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.10);'>

        <div style='width:90px;height:90px;margin:0 auto 25px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#7C3AED);color:white;display:flex;align-items:center;justify-content:center;font-size:40px;'>

        <i class='fa-solid fa-file-circle-check'></i>

        </div>

        <h2 style='color:#172554;font-weight:700;'>

        Monthly Resume Limit Reached

        </h2>

        <p style='color:#64748B;font-size:16px;line-height:1.7;'>

        You have used your 10 resume analyses for this month.

        </p>

        <p style='color:#64748B;font-size:16px;line-height:1.7;'>

        Your premium access will reset next month.

        </p>

        <a href='resume.php' style='display:inline-block;margin-top:20px;padding:13px 28px;border-radius:30px;background:linear-gradient(135deg,#2563EB,#7C3AED);color:white;text-decoration:none;font-weight:600;'>

        <i class='fa-solid fa-arrow-left'></i>

        Back to Resume Analysis

        </a>

        </div>

        </div>

        </body>

        </html>

        ");

    } else {

        die("

        <!DOCTYPE html>

        <html lang='en'>

        <head>

        <meta charset='UTF-8'>

        <meta name='viewport' content='width=device-width, initial-scale=1.0'>

        <title>Monthly Limit Reached | CareerPilot AI</title>

        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css' rel='stylesheet'>

        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>

        </head>

        <body style='background:#F5F7FF;font-family:Segoe UI,sans-serif;'>

        <div style='min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;'>

        <div style='max-width:550px;width:100%;background:white;border-radius:25px;padding:45px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.10);'>

        <div style='width:90px;height:90px;margin:0 auto 25px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#7C3AED);color:white;display:flex;align-items:center;justify-content:center;font-size:40px;'>

        <i class='fa-solid fa-lock'></i>

        </div>

        <h2 style='color:#172554;font-weight:700;'>

        Monthly Resume Limit Reached

        </h2>

        <p style='color:#64748B;font-size:16px;line-height:1.7;'>

        Free users can analyze only 1 resume per month.

        </p>

        <a href='subscription.php' style='display:inline-block;margin-top:20px;padding:13px 28px;border-radius:30px;background:linear-gradient(135deg,#2563EB,#7C3AED);color:white;text-decoration:none;font-weight:600;'>

        <i class='fa-solid fa-crown'></i>

        Upgrade to Premium

        </a>

        <br><br>

        <a href='resume.php' style='color:#64748B;text-decoration:none;'>

        <i class='fa-solid fa-arrow-left'></i>

        Back to Resume Analysis

        </a>

        </div>

        </div>

        </body>

        </html>

        ");

    }

}

if (!isset($_FILES['resume'])) {
    die("No file uploaded.");
}

$file = $_FILES['resume'];

if ($file['error'] != 0) {
    die("Resume upload failed.");
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($extension != "pdf") {
    die("Only PDF resumes are allowed.");
}

$uploadDir = "../uploads/resumes/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = time() . "_" . basename($file['name']);

$destination = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    die("Unable to save uploaded file.");
}

try {

    $parser = new Parser();

    $pdf = $parser->parseFile($destination);

    $resumeText = $pdf->getText();

    if (trim($resumeText) == "") {
        die("This PDF contains no readable text. Please upload a text-based resume.");
    }

} catch (Exception $e) {

    die("Unable to read PDF.");

}

$prompt = "

You are an expert ATS Resume Reviewer and Senior HR Manager.

Analyze the following resume professionally.

Return your response EXACTLY in this structure:

ATS SCORE:
90

PROFESSIONAL SUMMARY:
Write the summary here.

STRENGTHS:
- Strength 1
- Strength 2

WEAKNESSES:
- Weakness 1
- Weakness 2

MISSING TECHNICAL SKILLS:
- Skill 1
- Skill 2

MISSING SOFT SKILLS:
- Skill 1
- Skill 2

ATS KEYWORDS TO ADD:
- Keyword 1
- Keyword 2

GRAMMAR ISSUES:
- Issue 1

FORMATTING ISSUES:
- Issue 1

PROJECTS REVIEW:
Write review here.

EXPERIENCE REVIEW:
Write review here.

EDUCATION REVIEW:
Write review here.

INTERVIEW READINESS:
Write review here.

FINAL RECOMMENDATION:
Write final recommendation here.

Resume:

$resumeText

";

$analysis = askGemini($prompt);

$score = "N/A";

if (preg_match('/ATS SCORE:\s*(\d+)/i', $analysis, $matches)) {
    $score = $matches[1];
}

$user_id = $_SESSION['user_id'];

$fileName = mysqli_real_escape_string($conn, $filename);

$analysisSafe = mysqli_real_escape_string($conn, $analysis);

$sql = "

INSERT INTO resumes
(
user_id,
resume_file,
ai_feedback
)

VALUES
(
'$user_id',
'$fileName',
'$analysisSafe'
)

";

if (!mysqli_query($conn, $sql)) {
    die("Database Error: " . mysqli_error($conn));
}

$reportId = mysqli_insert_id($conn);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Resume Analysis | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

:root {

    --primary: #2563eb;
    --secondary: #7c3aed;
    --dark: #172554;
    --light-bg: #f5f7ff;

}

* {

    box-sizing: border-box;

}

body {

    margin: 0;

    background: var(--light-bg);

    font-family: "Segoe UI", sans-serif;

    color: #1e293b;

}

.page-wrapper {

    min-height: 100vh;

    padding: 45px 20px;

}

.report-container {

    max-width: 1100px;

    margin: auto;

}

.report-header {

    background: linear-gradient(135deg, #2563eb, #7c3aed);

    border-radius: 24px 24px 0 0;

    padding: 42px;

    color: white;

}

.brand {

    font-size: 14px;

    font-weight: 600;

    letter-spacing: .5px;

    opacity: .9;

}

.report-title {

    font-size: 32px;

    font-weight: 750;

    margin-top: 12px;

}

.report-subtitle {

    margin-top: 10px;

    opacity: .9;

}

.score-circle {

    width: 165px;

    height: 165px;

    background: white;

    border-radius: 50%;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    margin: auto;

    box-shadow: 0 15px 35px rgba(0,0,0,.2);

}

.score-number {

    font-size: 54px;

    font-weight: 800;

    color: var(--primary);

    line-height: 1;

}

.score-label {

    color: var(--primary);

    font-weight: 600;

    margin-top: 8px;

}

.report-body {

    background: white;

    padding: 42px;

    border-radius: 0 0 24px 24px;

}

.section-card {

    border: none;

    border-radius: 16px;

    padding: 25px;

    margin-bottom: 22px;

    background: #f8fafc;

    box-shadow: 0 5px 18px rgba(15, 23, 42, .05);

    border-left: 5px solid var(--primary);

}

.section-title {

    font-size: 19px;

    font-weight: 700;

    margin-bottom: 16px;

    color: var(--dark);

}

.section-title i {

    color: var(--primary);

    margin-right: 8px;

}

.section-content {

    white-space: pre-wrap;

    line-height: 1.8;

    color: #475569;

    font-size: 15px;

}

.action-buttons {

    display: flex;

    gap: 14px;

    flex-wrap: wrap;

    margin-top: 32px;

}

/* =========================
   ACTION BUTTONS
========================= */

.action-buttons {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 35px;

}

.btn-theme {

    border: none;

    color: white;

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    padding: 13px 25px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

    box-shadow: 0 8px 20px rgba(37, 99, 235, .25);

}

.btn-outline-theme {

    background: white;

    color: #2563EB;

    border: 1px solid #2563EB;

    padding: 13px 25px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-outline-theme:hover {

    background: #2563EB;

    color: white;

    transform: translateY(-2px);

}

.btn-dashboard {

    background: #F1F5F9;

    color: #475569;

    padding: 13px 25px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-dashboard:hover {

    background: #E2E8F0;

    color: #1E293B;

    transform: translateY(-2px);

}

@media(max-width: 768px) {

    .report-header {

        padding: 30px 22px;

        text-align: center;

    }

    .report-title {

        font-size: 25px;

    }

    .score-circle {

        margin-top: 25px;

    }

    .report-body {

        padding: 22px;

    }

}

</style>

</head>

<body>

<div class="page-wrapper">

<div class="report-container">

<div class="report-header">

<div class="row align-items-center">

<div class="col-md-8">

<div class="brand">

<i class="fa-solid fa-rocket"></i>

CAREERPILOT AI

</div>

<div class="report-title">

<i class="fa-solid fa-file-circle-check"></i>

Resume Analysis Report

</div>

<div class="report-subtitle">

AI-powered feedback to help you improve your resume and career opportunities.

</div>

</div>

<div class="col-md-4">

<div class="score-circle">

<div class="score-number">

<?= htmlspecialchars($score) ?>

</div>

<div class="score-label">

ATS SCORE

</div>

</div>

</div>

</div>

</div>

<div class="report-body">

<?php

$sections = [

    "PROFESSIONAL SUMMARY" => [
        "icon" => "fa-user-tie",
        "title" => "Professional Summary"
    ],

    "STRENGTHS" => [
        "icon" => "fa-thumbs-up",
        "title" => "Strengths"
    ],

    "WEAKNESSES" => [
        "icon" => "fa-triangle-exclamation",
        "title" => "Weaknesses"
    ],

    "MISSING TECHNICAL SKILLS" => [
        "icon" => "fa-code",
        "title" => "Missing Technical Skills"
    ],

    "MISSING SOFT SKILLS" => [
        "icon" => "fa-people-group",
        "title" => "Missing Soft Skills"
    ],

    "ATS KEYWORDS TO ADD" => [
        "icon" => "fa-key",
        "title" => "ATS Keywords to Add"
    ],

    "GRAMMAR ISSUES" => [
        "icon" => "fa-spell-check",
        "title" => "Grammar Issues"
    ],

    "FORMATTING ISSUES" => [
        "icon" => "fa-paintbrush",
        "title" => "Formatting Issues"
    ],

    "PROJECTS REVIEW" => [
        "icon" => "fa-diagram-project",
        "title" => "Projects Review"
    ],

    "EXPERIENCE REVIEW" => [
        "icon" => "fa-briefcase",
        "title" => "Experience Review"
    ],

    "EDUCATION REVIEW" => [
        "icon" => "fa-graduation-cap",
        "title" => "Education Review"
    ],

    "INTERVIEW READINESS" => [
        "icon" => "fa-comments",
        "title" => "Interview Readiness"
    ],

    "FINAL RECOMMENDATION" => [
        "icon" => "fa-lightbulb",
        "title" => "Final Recommendation"
    ]

];

$cleanAnalysis = str_replace("**", "", $analysis);

foreach ($sections as $heading => $section) {

    $pattern = '/' . preg_quote($heading, '/') . ':\s*(.*?)(?=\n[A-Z][A-Z ]+:\s*|\z)/is';

    if (preg_match($pattern, $cleanAnalysis, $matches)) {

        $content = trim($matches[1]);

        if ($content != "") {

?>

<div class="section-card">

    <div class="section-title">

        <i class="fa-solid <?= $section['icon'] ?>"></i>

        <?= $section['title'] ?>

    </div>

    <div class="section-content">

        <?= nl2br(htmlspecialchars($content)) ?>

    </div>

</div>

<?php

        }

    }

}

?>

<div class="action-buttons">

    <a

        href="download_report.php?id=<?= $reportId ?>"

        class="btn-theme"

    >

        <i class="fa-solid fa-file-pdf"></i>

        Download PDF Report

    </a>

    <a

        href="resume.php"

        class="btn-outline-theme"

    >

        <i class="fa-solid fa-rotate-right"></i>

        Analyze Another Resume

    </a>

    <a

        href="index.php"

        class="btn-dashboard"

    >

        <i class="fa-solid fa-house"></i>

        Back to Dashboard

    </a>

</div>

</div>

</div>

</div>

</body>

</html>