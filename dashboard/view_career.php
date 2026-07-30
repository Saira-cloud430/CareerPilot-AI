<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];


/* ==============================
   VALIDATE ID
============================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: my_career.php");

    exit();

}

$roadmap_id = (int) $_GET['id'];


/* ==============================
   FETCH USER'S OWN ROADMAP
============================== */

$stmt = mysqli_prepare(

    $conn,

    "SELECT id, target_career, roadmap, created_at

     FROM career_roadmaps

     WHERE id = ?

     AND user_id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $roadmap_id,

    $user_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) === 0) {

    header("Location: my_career.php");

    exit();

}

$roadmap = mysqli_fetch_assoc($result);

$target_career = $roadmap['target_career'];

$created_at = $roadmap['created_at'];

$roadmap_text = $roadmap['roadmap'];


/* ==============================
   FORMAT AI RESPONSE
============================== */

$text = htmlspecialchars(

    $roadmap_text,

    ENT_QUOTES,

    'UTF-8'

);


/* Convert bold Markdown */

$text = preg_replace(

    '/\*\*(.*?)\*\*/s',

    '<strong>$1</strong>',

    $text

);


/* Convert ## headings */

$text = preg_replace(

    '/^##\s*(.*?)$/m',

    '<h2 class="section-title">$1</h2>',

    $text

);


/* Convert ### headings */

$text = preg_replace(

    '/^###\s*(.*?)$/m',

    '<h3 class="subsection-title">$1</h3>',

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


/* Remove excessive empty lines */

$text = preg_replace(

    '/\n\s*\n+/',

    "\n",

    $text

);


/* Convert line breaks */

$text = nl2br($text);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View Career Roadmap | CareerPilot AI</title>

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

.roadmap-container {

    max-width: 1100px;

    margin: auto;

}

.page-header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 40px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37,99,235,.20);

}

.brand {

    font-size: 14px;

    font-weight: 600;

    letter-spacing: .6px;

    opacity: .9;

    margin-bottom: 12px;

}

.page-header h1 {

    margin: 0;

    font-size: 32px;

    font-weight: 700;

}

.page-header p {

    margin-top: 10px;

    opacity: .9;

}

.roadmap-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0,0,0,.08);

}

.roadmap-info {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-bottom: 30px;

}

.info-box {

    flex: 1;

    min-width: 220px;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-radius: 14px;

    padding: 18px;

}

.info-box strong {

    display: block;

    color: #374151;

    margin-bottom: 6px;

}

.info-box i {

    color: #2563EB;

    margin-right: 7px;

}

.info-box span {

    color: #64748B;

}

.introduction {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 30px;

    line-height: 1.7;

}

.introduction i {

    color: #2563EB;

    margin-right: 8px;

}

.roadmap-result {

    line-height: 1.8;

    font-size: 16px;

}

.roadmap-result strong {

    color: #111827;

    font-weight: 700;

}

.section-title {

    color: #2563EB;

    font-size: 25px;

    font-weight: 700;

    margin-top: 30px;

    margin-bottom: 18px;

    padding-bottom: 10px;

    border-bottom: 2px solid #E5E7EB;

}

.subsection-title {

    color: #7C3AED;

    font-size: 20px;

    font-weight: 700;

    margin-top: 22px;

    margin-bottom: 10px;

}

.roadmap-result ul {

    padding-left: 25px;

    margin-top: 10px;

    margin-bottom: 18px;

}

.roadmap-result li {

    margin-bottom: 9px;

    line-height: 1.7;

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

}

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .roadmap-content {

        padding: 25px;

    }

    .page-header h1 {

        font-size: 26px;

    }

}

</style>

</head>

<body>

<div class="roadmap-container">

    <div class="page-header">

        <div class="brand">

            <i class="fa-solid fa-rocket"></i>

            CAREERPILOT AI

        </div>

        <h1>

            <i class="fa-solid fa-route"></i>

            Career Roadmap

        </h1>

        <p>

            Your personalized AI-generated career development roadmap.

        </p>

    </div>

    <div class="roadmap-content">

        <div class="roadmap-info">

            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-bullseye"></i>

                    Target Career

                </strong>

                <span>

                    <?= htmlspecialchars($target_career); ?>

                </span>

            </div>

            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-calendar-days"></i>

                    Created On

                </strong>

                <span>

                    <?= date(

                        "F d, Y - h:i A",

                        strtotime($created_at)

                    ); ?>

                </span>

            </div>

        </div>

        <div class="introduction">

            <i class="fa-solid fa-circle-info"></i>

            <strong>Your Personalized Career Plan</strong>

            <br>

            Follow this roadmap step by step and build your skills, projects, and experience consistently.

        </div>

        <div class="roadmap-result">

            <?= $text ?>

        </div>

        <div class="action-buttons">

            <a href="roadmap.php" class="btn-theme">

                <i class="fa-solid fa-wand-magic-sparkles"></i>

                Generate New Roadmap

            </a>

            <a href="my_career.php" class="btn-secondary-custom">

                <i class="fa-solid fa-clock-rotate-left"></i>

                My Roadmaps

            </a>

            <a

                href="delete_career.php?id=<?= $roadmap_id; ?>"

                class="btn-danger-custom"

                onclick="return confirm('Are you sure you want to delete this career roadmap?');"

            >

                <i class="fa-solid fa-trash"></i>

                Delete Roadmap

            </a>

        </div>

    </div>

</div>

</body>

</html>