<?php

session_start();

require_once "../config.php";
require_once "../api/gemini.php";
require_once "subscription_check.php";


/* ============================================================
   AUTHENTICATION
============================================================ */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];


/* ============================================================
   REQUEST VALIDATION
============================================================ */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: careerroadmap.php");

    exit();

}


/* ============================================================
   CAREER ROADMAP LIMIT
============================================================ */

/*
   FREE USER:
   1 roadmap per day

   PREMIUM USER:
   10 roadmaps per day
*/

$daily_limit = $isPremium ? 10 : 1;


$limit_query = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total
     FROM career_roadmaps
     WHERE user_id='$user_id'
     AND DATE(created_at)=CURDATE()"

);


if (!$limit_query) {

    die("Database Error: " . mysqli_error($conn));

}


$limit_data = mysqli_fetch_assoc($limit_query);


if ($limit_data['total'] >= $daily_limit) {

    ?>

    <!DOCTYPE html>

    <html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Daily Limit Reached | CareerPilot AI</title>

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

                background: #F5F7FF;

                font-family: 'Segoe UI', sans-serif;

            }

            .limit-container {

                min-height: 100vh;

                display: flex;

                align-items: center;

                justify-content: center;

                padding: 20px;

            }

            .limit-card {

                max-width: 560px;

                width: 100%;

                background: white;

                border-radius: 25px;

                padding: 45px;

                text-align: center;

                box-shadow: 0 20px 50px rgba(0,0,0,.10);

            }

            .limit-icon {

                width: 90px;

                height: 90px;

                margin: 0 auto 25px;

                border-radius: 50%;

                background: linear-gradient(135deg,#2563EB,#7C3AED);

                color: white;

                display: flex;

                align-items: center;

                justify-content: center;

                font-size: 40px;

            }

            .limit-card h2 {

                color: #172554;

                font-weight: 700;

                margin-bottom: 15px;

            }

            .limit-card p {

                color: #64748B;

                font-size: 16px;

                line-height: 1.7;

            }

            .btn-upgrade {

                display: inline-block;

                margin-top: 20px;

                padding: 13px 28px;

                border-radius: 30px;

                background: linear-gradient(135deg,#2563EB,#7C3AED);

                color: white;

                text-decoration: none;

                font-weight: 600;

            }

            .btn-upgrade:hover {

                color: white;

                transform: translateY(-2px);

            }

            .btn-back {

                display: block;

                margin-top: 18px;

                color: #64748B;

                text-decoration: none;

            }

            .btn-back:hover {

                color: #2563EB;

            }

        </style>

    </head>

    <body>

        <div class="limit-container">

            <div class="limit-card">

                <div class="limit-icon">

                    <i class="fa-solid fa-route"></i>

                </div>

                <?php if ($isPremium): ?>

                    <h2>Daily Premium Limit Reached</h2>

                    <p>

                        You have used your

                        <strong>10 AI career roadmaps</strong>

                        for today.

                    </p>

                    <p>

                        Your daily limit will reset tomorrow.

                    </p>

                <?php else: ?>

                    <h2>Daily Free Limit Reached</h2>

                    <p>

                        You have used your

                        <strong>1 free career roadmap</strong>

                        for today.

                    </p>

                    <p>

                        Upgrade to Premium to generate up to

                        <strong>10 career roadmaps every day</strong>.

                    </p>

                    <a href="subscription.php" class="btn-upgrade">

                        <i class="fa-solid fa-crown"></i>

                        Upgrade to Premium

                    </a>

                <?php endif; ?>

                <a href="careerroadmap.php" class="btn-back">

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Career Roadmap

                </a>

                <a href="index.php" class="btn-back">

                    <i class="fa-solid fa-house"></i>

                    Back to Dashboard

                </a>

            </div>

        </div>

    </body>

    </html>

    <?php

    exit();

}


/* ============================================================
   FORM DATA
============================================================ */

$career = trim($_POST['career'] ?? '');

$education = trim($_POST['education'] ?? '');

$level = trim($_POST['skill_level'] ?? 'Beginner');

$duration = trim($_POST['duration'] ?? '12 Months');


if ($career === '') {

    die("Please enter your career goal.");

}


/* ============================================================
   AI PROMPT
============================================================ */

$prompt = "

You are an experienced AI Career Mentor.

Create a detailed, practical, and personalized career roadmap.

Career Goal:
$career

Current Education:
$education

Current Skill Level:
$level

Target Duration:
$duration

Create the roadmap using this exact structure:

## Career Roadmap Overview

Write a short personalized introduction explaining the candidate's current position and career direction.

## Phase 1: Foundation

Explain what the candidate should learn first.

Include:

- Skills to learn
- Important concepts
- Recommended learning resources
- Practical exercises

## Phase 2: Skill Development

Explain the next important technical and professional skills.

Include:

- Technical skills
- Tools and technologies
- Practical exercises

## Phase 3: Projects

Recommend practical projects.

For every project include:

- Project name
- What to build
- Skills practiced

## Phase 4: Portfolio Development

Explain what the candidate should add to their portfolio.

## Phase 5: Certifications

Recommend relevant certifications only when they are genuinely useful.

## Phase 6: Interview Preparation

Explain what the candidate should prepare for interviews.

## Final Career Advice

Give practical and encouraging advice.

Use clear professional language.

Use headings and bullet points.

Do not use unnecessary symbols.

";


/* ============================================================
   GENERATE ROADMAP
============================================================ */

$roadmap = askGemini($prompt);


if (is_array($roadmap) && isset($roadmap['error'])) {

    die("

        <div style='
            max-width:700px;
            margin:80px auto;
            padding:30px;
            font-family:Arial;
            background:#fff3cd;
            border:1px solid #ffeeba;
            border-radius:15px;
            color:#856404;
            text-align:center;
        '>

            <h2>AI Service Temporarily Unavailable</h2>

            <p>

                The AI service is currently unavailable.

                Please try again later.

            </p>

            <a href='careerroadmap.php'>

                Back to Career Roadmap

            </a>

        </div>

    ");

}


if (empty(trim($roadmap))) {

    die("Unable to generate career roadmap. Please try again.");

}


/* ============================================================
   SAVE ROADMAP
============================================================ */

$careerSafe = mysqli_real_escape_string($conn, $career);

$roadmapSafe = mysqli_real_escape_string($conn, $roadmap);


$sql = "

INSERT INTO career_roadmaps

(

    user_id,

    target_career,

    roadmap

)

VALUES

(

    '$user_id',

    '$careerSafe',

    '$roadmapSafe'

)

";


if (!mysqli_query($conn, $sql)) {

    die("Database Error: " . mysqli_error($conn));

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Career Roadmap | CareerPilot AI</title>

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

    color: #1F2937;

    font-family: 'Segoe UI', sans-serif;

}

.roadmap-container {

    max-width: 1100px;

    margin: auto;

}

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

    font-weight: 700;

    font-size: 32px;

}

.page-header p {

    margin-top: 12px;

    margin-bottom: 0;

    opacity: .9;

    font-size: 16px;

}

.roadmap-content {

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

.introduction {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 35px;

    line-height: 1.7;

}

.introduction i {

    color: #2563EB;

    margin-right: 8px;

}

.roadmap-result {

    line-height: 1.8;

}

.section-title {

    color: #2563EB;

    font-size: 24px;

    font-weight: 700;

    margin-top: 30px;

    margin-bottom: 18px;

    padding-bottom: 10px;

    border-bottom: 2px solid #E5E7EB;

}

.subsection-title {

    color: #7C3AED;

    font-size: 19px;

    font-weight: 700;

    margin-top: 22px;

    margin-bottom: 10px;

}

.roadmap-result ul {

    padding-left: 25px;

    margin-top: 10px;

}

.roadmap-result li {

    margin-bottom: 9px;

    line-height: 1.7;

}

.roadmap-result strong {

    color: #111827;

}

.action-buttons {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 35px;

}

.btn-theme {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    color: white;

    border: none;

    padding: 12px 25px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

.btn-secondary-custom {

    background: #64748B;

    color: white;

    padding: 12px 25px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-secondary-custom:hover {

    background: #475569;

    color: white;

    transform: translateY(-2px);

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

    .action-buttons {

        flex-direction: column;

    }

    .action-buttons a {

        text-align: center;

    }

}

</style>

</head>

<body>

<div class="roadmap-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-route"></i>

            Your AI Career Roadmap

        </h1>

        <p>

            A personalized step-by-step career development plan created by CareerPilot AI.

        </p>

    </div>

    <div class="roadmap-content">

        <div class="introduction">

            <i class="fa-solid fa-circle-info"></i>

            <strong>Your Personalized Career Plan</strong>

            <br>

            Follow this roadmap step by step to build the skills, projects, and experience needed to move toward your career goal.

        </div>

        <?php

        $text = htmlspecialchars(

            $roadmap,

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

            '/(?:<li>.*?<\/li>\s*)+/s',

            '<ul>$0</ul>',

            $text

        );


        /* Convert line breaks */

        $text = nl2br($text);


        /* Remove excessive line breaks */

        $text = preg_replace(

            '/(<br\s*\/?>\s*){3,}/i',

            '<br><br>',

            $text

        );

        ?>

        <div class="roadmap-result">

            <?= $text ?>

        </div>

        <div class="action-buttons">

            <a href="careerroadmap.php" class="btn-theme">

                <i class="fa-solid fa-rotate-right"></i>

                Generate Another Roadmap

            </a>

            <a href="my_career.php" class="btn-theme">

                <i class="fa-solid fa-clock-rotate-left"></i>

                My Roadmaps

            </a>

            <a href="index.php" class="btn-secondary-custom">

                <i class="fa-solid fa-house"></i>

                Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>