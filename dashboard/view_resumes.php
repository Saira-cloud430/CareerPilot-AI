<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: my_resumes.php");

    exit();

}

$resume_id = (int) $_GET['id'];


/* ==============================
   FETCH USER'S OWN REPORT
============================== */

$stmt = mysqli_prepare(

    $conn,

    "SELECT id, resume_file, ai_feedback, uploaded_at
     FROM resumes
     WHERE id = ?
     AND user_id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $resume_id,

    $user_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {

    header("Location: my_resumes.php");

    exit();

}

$resume = mysqli_fetch_assoc($result);

$analysis = $resume['ai_feedback'];

$score = "N/A";

if (preg_match('/ATS SCORE:\s*(\d+)/i', $analysis, $matches)) {

    $score = $matches[1];

}


/* ==============================
   SECTION DEFINITIONS
============================== */

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

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View Resume Analysis | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

* {

    box-sizing: border-box;

}

body {

    margin: 0;

    padding: 40px 20px;

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    color: #1F2937;

}

.report-container {

    max-width: 1100px;

    margin: auto;

}

.report-header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 40px;

    border-radius: 25px 25px 0 0;

}

.report-header h1 {

    margin: 0;

    font-size: 32px;

    font-weight: 700;

}

.report-header p {

    margin-top: 10px;

    opacity: .9;

}

.report-body {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

.report-meta {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-bottom: 30px;

}

.meta-box {

    flex: 1;

    min-width: 240px;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-radius: 14px;

    padding: 18px;

}

.meta-box strong {

    display: block;

    color: #374151;

    margin-bottom: 6px;

}

.meta-box i {

    color: #2563EB;

    margin-right: 8px;

}

.meta-box span {

    color: #64748B;

}

.score-box {

    background: linear-gradient(135deg, #EFF6FF, #F5F3FF);

    border: 1px solid #DBEAFE;

    border-left: 5px solid #2563EB;

    padding: 25px;

    border-radius: 16px;

    text-align: center;

    margin-bottom: 30px;

}

.score-number {

    font-size: 52px;

    font-weight: 800;

    color: #2563EB;

}

.score-label {

    display: block;

    color: #64748B;

    font-weight: 700;

    letter-spacing: .5px;

}

.section-card {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    border-radius: 16px;

    padding: 25px;

    margin-bottom: 22px;

}

.section-title {

    font-size: 19px;

    font-weight: 700;

    color: #172554;

    margin-bottom: 15px;

}

.section-title i {

    color: #2563EB;

    margin-right: 8px;

}

.section-content {

    color: #475569;

    line-height: 1.8;

    white-space: pre-wrap;

}

.action-buttons {

    display: flex;

    gap: 14px;

    flex-wrap: wrap;

    margin-top: 35px;

    padding-top: 25px;

    border-top: 1px solid #E5E7EB;

}

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 12px 24px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

.btn-secondary-custom {

    background: #64748B;

    color: white;

    padding: 12px 24px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

}

.btn-secondary-custom:hover {

    background: #475569;

    color: white;

}

.btn-danger-custom {

    background: #FEE2E2;

    color: #B91C1C;

    padding: 12px 24px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

}

.btn-danger-custom:hover {

    background: #FECACA;

    color: #991B1B;

}

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .report-header,

    .report-body {

        padding: 25px;

    }

    .report-header h1 {

        font-size: 26px;

    }

}

</style>

</head>

<body>

<div class="report-container">

    <div class="report-header">

        <h1>

            <i class="fa-solid fa-file-circle-check"></i>

            Resume Analysis Report

        </h1>

        <p>

            Review your personalized AI-powered resume feedback.

        </p>

    </div>

    <div class="report-body">

        <div class="report-meta">

            <div class="meta-box">

                <strong>

                    <i class="fa-solid fa-file-pdf"></i>

                    Resume File

                </strong>

                <span>

                    <?= htmlspecialchars($resume['resume_file']); ?>

                </span>

            </div>

            <div class="meta-box">

                <strong>

                    <i class="fa-solid fa-calendar-days"></i>

                    Analyzed On

                </strong>

                <span>

                    <?= date(

                        "F d, Y - h:i A",

                        strtotime($resume['uploaded_at'])

                    ); ?>

                </span>

            </div>

        </div>

        <div class="score-box">

            <div class="score-number">

                <?= htmlspecialchars($score); ?>

            </div>

            <span class="score-label">

                ATS SCORE

            </span>

        </div>

        <?php foreach ($sections as $heading => $section): ?>

            <?php

            $pattern = '/' .

                preg_quote($heading, '/') .

                ':\s*(.*?)(?=\n[A-Z][A-Z ]+:\s*|\z)/is';

            ?>

            <?php if (preg_match($pattern, $cleanAnalysis, $matches)): ?>

                <?php $content = trim($matches[1]); ?>

                <?php if ($content !== ""): ?>

                    <div class="section-card">

                        <div class="section-title">

                            <i class="fa-solid <?= $section['icon']; ?>"></i>

                            <?= $section['title']; ?>

                        </div>

                        <div class="section-content">

                            <?= nl2br(htmlspecialchars($content)); ?>

                        </div>

                    </div>

                <?php endif; ?>

            <?php endif; ?>

        <?php endforeach; ?>

        <div class="action-buttons">

            <a

                href="download_report.php?id=<?= $resume_id; ?>"

                class="btn-theme"

            >

                <i class="fa-solid fa-file-pdf"></i>

                Download PDF Report

            </a>

            <a

                href="resume.php"

                class="btn-theme"

            >

                <i class="fa-solid fa-wand-magic-sparkles"></i>

                Analyze New Resume

            </a>

            <a

                href="my_resumes.php"

                class="btn-secondary-custom"

            >

                <i class="fa-solid fa-clock-rotate-left"></i>

                My Resume Analyses

            </a>

            <a

                href="delete_resume.php?id=<?= $resume_id; ?>"

                class="btn-danger-custom"

                onclick="return confirm('Are you sure you want to delete this resume analysis?');"

            >

                <i class="fa-solid fa-trash"></i>

                Delete Analysis

            </a>

        </div>

    </div>

</div>

</body>

</html>