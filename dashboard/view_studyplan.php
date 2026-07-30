<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

$plan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($plan_id <= 0) {

    header("Location: my_studyplans.php");

    exit();

}


/* =========================================
   FETCH ONLY CURRENT USER'S STUDY PLAN
========================================= */

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        id,
        technology,
        level,
        hours,
        weeks,
        study_plan,
        created_at
     FROM study_plans
     WHERE id = ?
     AND user_id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $plan_id,

    $user_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$plan = mysqli_fetch_assoc($result);

if (!$plan) {

    die("Study plan not found or you do not have permission to view it.");

}


/* =========================================
   FORMAT SAVED AI RESPONSE
========================================= */

$text = htmlspecialchars(

    $plan['study_plan'],

    ENT_QUOTES,

    'UTF-8'

);


/* Convert Markdown bold */

$text = preg_replace(

    '/\*\*(.*?)\*\*/s',

    '<strong>$1</strong>',

    $text

);


/* Remove Markdown separators */

$text = preg_replace(

    '/^\s*---+\s*$/m',

    '',

    $text

);


/* Convert ## headings */

$text = preg_replace(

    '/^##\s*(.*?)$/m',

    '<h2>$1</h2>',

    $text

);


/* Convert ### headings */

$text = preg_replace(

    '/^###\s*(.*?)$/m',

    '<h3>$1</h3>',

    $text

);


/* Convert bullet points */

$text = preg_replace(

    '/^\s*[-*•]\s+(.*?)$/m',

    '<li>$1</li>',

    $text

);


/* Wrap consecutive list items */

$text = preg_replace(

    '/((?:<li>.*?<\/li>\s*)+)/s',

    '<ul>$1</ul>',

    $text

);


/* Remove excessive empty lines */

$text = preg_replace(

    "/\n\s*\n+/",

    "\n",

    $text

);


/* Convert remaining line breaks */

$text = nl2br($text);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

<?= htmlspecialchars($plan['technology']) ?>

Study Plan | CareerPilot AI

</title>

<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"

rel="stylesheet"

>

<link

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"

rel="stylesheet"

>

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

.study-container {

    max-width: 1100px;

    margin: auto;

}


/* ============================
   HEADER
============================ */

.page-header {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    color: white;

    padding: 40px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(

        37,

        99,

        235,

        .20

    );

}

.page-header h1 {

    margin: 0;

    font-size: 32px;

    font-weight: 700;

}

.page-header p {

    margin: 12px 0 0;

    opacity: .9;

}


/* ============================
   CONTENT
============================ */

.study-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(

        0,

        0,

        0,

        .08

    );

}


/* ============================
   STUDY INFO
============================ */

.study-info {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-bottom: 30px;

}

.info-box {

    flex: 1;

    min-width: 180px;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-radius: 14px;

    padding: 18px;

}

.info-box i {

    color: #2563EB;

    margin-right: 8px;

}

.info-box strong {

    display: block;

    color: #374151;

    margin-bottom: 6px;

}

.info-box span {

    color: #6B7280;

}


/* ============================
   INTRODUCTION
============================ */

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


/* ============================
   AI RESULT
============================ */

.study-result {

    font-size: 16px;

    line-height: 1.8;

}

.study-result h2 {

    color: #2563EB;

    font-size: 24px;

    font-weight: 700;

    margin-top: 30px;

    margin-bottom: 14px;

    padding-bottom: 10px;

    border-bottom: 2px solid #E5E7EB;

}

.study-result h3 {

    color: #7C3AED;

    font-size: 20px;

    font-weight: 700;

    margin-top: 22px;

    margin-bottom: 10px;

}

.study-result ul {

    padding-left: 25px;

    margin-top: 8px;

    margin-bottom: 18px;

}

.study-result li {

    margin-bottom: 7px;

}

.study-result strong {

    color: #111827;

}


/* ============================
   ACTION BUTTONS
============================ */

.action-buttons {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 40px;

    padding-top: 25px;

    border-top: 1px solid #E5E7EB;

}

.btn-theme {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    border: none;

    color: white;

    padding: 12px 24px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    display: inline-block;

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

    font-weight: 600;

    text-decoration: none;

    display: inline-block;

}

.btn-secondary-custom:hover {

    background: #475569;

    color: white;

}


/* ============================
   RESPONSIVE
============================ */

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .study-content {

        padding: 25px;

    }

    .page-header h1 {

        font-size: 26px;

    }

}

</style>

</head>

<body>

<div class="study-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-book-open-reader"></i>

            <?= htmlspecialchars($plan['technology']) ?>

            Study Plan

        </h1>

        <p>

            Your personalized AI learning roadmap created by CareerPilot AI.

        </p>

    </div>


    <div class="study-content">

        <div class="study-info">

            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-code"></i>

                    Technology

                </strong>

                <span>

                    <?= htmlspecialchars($plan['technology']) ?>

                </span>

            </div>

            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-signal"></i>

                    Skill Level

                </strong>

                <span>

                    <?= htmlspecialchars($plan['level']) ?>

                </span>

            </div>

            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-clock"></i>

                    Study Time

                </strong>

                <span>

                    <?= (int)$plan['hours'] ?> hours/day

                </span>

            </div>

            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-calendar-days"></i>

                    Duration

                </strong>

                <span>

                    <?= (int)$plan['weeks'] ?> weeks

                </span>

            </div>

        </div>


        <div class="introduction">

            <i class="fa-solid fa-circle-info"></i>

            <strong>Your Personalized Learning Plan</strong>

            <br>

            Follow this roadmap consistently, practice what you learn, and build projects to turn your knowledge into practical skills.

        </div>


        <div class="study-result">

            <?= $text ?>

        </div>


        <div class="action-buttons">

            <a

                href="my_studyplans.php"

                class="btn-theme"

            >

                <i class="fa-solid fa-clock-rotate-left"></i>

                My Study Plans

            </a>


            <a

                href="studyplan.php"

                class="btn-theme"

            >

                <i class="fa-solid fa-plus"></i>

                Create New Plan

            </a>


            <a

                href="index.php"

                class="btn-secondary-custom"

            >

                <i class="fa-solid fa-house"></i>

                Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>