<?php

require_once "../config.php";


/* ==============================
   AUTHENTICATION
============================== */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


$user_id = $_SESSION['user_id'];


/* ==============================
   VALIDATE INTERVIEW ID
============================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: my_interviews.php");

    exit();

}


$interview_id = (int) $_GET['id'];


/* ==============================
   FETCH USER'S OWN INTERVIEW
============================== */

$stmt = mysqli_prepare(

    $conn,

    "SELECT id, job_role, feedback, created_at
     FROM interview_sessions
     WHERE id = ?
     AND user_id = ?"

);


mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $interview_id,

    $user_id

);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) === 0) {

    header("Location: my_interviews.php");

    exit();

}


$interview = mysqli_fetch_assoc($result);


$job_role = $interview['job_role'];

$feedback = $interview['feedback'];

$created_at = $interview['created_at'];


/* ==============================
   FORMAT AI RESPONSE
============================== */


/*
   Escape AI response first
*/

$text = htmlspecialchars(

    $feedback,

    ENT_QUOTES,

    'UTF-8'

);


/*
   Convert bold Markdown
*/

$text = preg_replace(

    '/\*\*(.*?)\*\*/s',

    '<strong>$1</strong>',

    $text

);


/*
   Convert Question headings
*/

$text = preg_replace(

    '/###\s*(Question\s*\d+)/i',

    '<h3 class="question-title">$1</h3>',

    $text

);


/*
   Convert main headings
*/

$text = preg_replace(

    '/##\s*(Common Interview Mistakes|Final Interview Preparation Tips)/i',

    '<h2 class="section-title">$1</h2>',

    $text

);


/*
   Convert actual question
*/

$text = preg_replace(

    '/The Interview Question\s*(.*?)\s*(?=Why the interviewer asks it|Tips for answering|A sample strong answer)/is',

    '<div class="question-text">$1</div>',

    $text

);


/*
   Convert section labels
*/

$text = str_replace(

    [

        'Why the interviewer asks it',

        'Tips for answering',

        'A sample strong answer',

        'Common Interview Mistakes',

        'Final Interview Preparation Tips'

    ],

    [

        '<div class="answer-heading">

            <i class="fa-solid fa-lightbulb"></i>

            Why the interviewer asks it

        </div>',

        '<div class="answer-heading">

            <i class="fa-solid fa-bullseye"></i>

            Tips for answering

        </div>',

        '<div class="answer-heading">

            <i class="fa-solid fa-circle-check"></i>

            A sample strong answer

        </div>',

        '<div class="answer-heading">

            <i class="fa-solid fa-triangle-exclamation"></i>

            Common Interview Mistakes

        </div>',

        '<div class="answer-heading">

            <i class="fa-solid fa-rocket"></i>

            Final Interview Preparation Tips

        </div>'

    ],

    $text

);


/*
   Convert bullet points
*/

$text = preg_replace(

    '/^\s*[-*•]\s*(.*?)$/m',

    '<li>$1</li>',

    $text

);


/*
   Wrap list items
*/

$text = preg_replace(

    '/((?:<li>.*?<\/li>\s*)+)/s',

    '<ul>$1</ul>',

    $text

);


/*
   Remove excessive empty lines
*/

$text = preg_replace(

    '/\n\s*\n/',

    "\n",

    $text

);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

View Interview | CareerPilot AI

</title>


<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"

rel="stylesheet">


<link

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"

rel="stylesheet">


<style>


/* ==============================
   GLOBAL
============================== */


* {

    box-sizing: border-box;

}


body {

    margin: 0;

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    color: #1F2937;

    padding: 40px 20px;

}


.interview-container {

    max-width: 1100px;

    margin: auto;

}


/* ==============================
   HEADER
============================== */


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


.brand {

    font-size: 14px;

    font-weight: 600;

    letter-spacing: .6px;

    opacity: .9;

    margin-bottom: 12px;

}


.page-header h1 {

    margin: 0;

    font-weight: 700;

    font-size: 32px;

}


.page-header p {

    margin-top: 10px;

    margin-bottom: 0;

    opacity: .9;

}


/* ==============================
   CONTENT
============================== */


.interview-content {

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


/* ==============================
   INTERVIEW INFO
============================== */


.interview-info {

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


/* ==============================
   INTRODUCTION
============================== */


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


/* ==============================
   RESULT
============================== */


.interview-result {

    line-height: 1.8;

    font-size: 16px;

}


.interview-result strong {

    font-weight: 700;

    color: #111827;

}


.question-title {

    color: #2563EB;

    font-size: 24px;

    font-weight: 700;

    margin-top: 30px;

    margin-bottom: 18px;

    padding-bottom: 10px;

    border-bottom: 2px solid #E5E7EB;

}


.question-text {

    background: linear-gradient(

        135deg,

        #EFF6FF,

        #F5F3FF

    );

    padding: 18px 20px;

    border-radius: 12px;

    font-size: 18px;

    font-weight: 700;

    color: #111827;

    margin-bottom: 24px;

}


.answer-heading {

    color: #374151;

    font-size: 17px;

    font-weight: 700;

    margin-top: 22px;

    margin-bottom: 10px;

}


.answer-heading i {

    color: #2563EB;

    margin-right: 8px;

}


.interview-result ul {

    padding-left: 25px;

    margin-top: 10px;

    margin-bottom: 18px;

}


.interview-result li {

    margin-bottom: 9px;

    line-height: 1.7;

}


.section-title {

    color: #2563EB;

    font-size: 25px;

    font-weight: 700;

    margin-top: 35px;

    margin-bottom: 20px;

    padding-bottom: 10px;

    border-bottom: 2px solid #E5E7EB;

}


/* ==============================
   ACTION BUTTONS
============================== */


.action-buttons {

    display: flex;

    gap: 14px;

    flex-wrap: wrap;

    margin-top: 35px;

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

    color: white;

    background: #475569;

}


.btn-danger-custom {

    background: #FEE2E2;

    color: #B91C1C;

    padding: 12px 24px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    display: inline-block;

}


.btn-danger-custom:hover {

    background: #FECACA;

    color: #991B1B;

}


/* ==============================
   RESPONSIVE
============================== */


@media (max-width: 768px) {


    body {

        padding: 20px 10px;

    }


    .page-header,

    .interview-content {

        padding: 25px;

    }


    .page-header h1 {

        font-size: 26px;

    }


    .question-text {

        font-size: 16px;

    }


}


</style>

</head>

<body>

<div class="interview-container">


    <div class="page-header">

        <div class="brand">

            <i class="fa-solid fa-rocket"></i>

            CAREERPILOT AI

        </div>

        <h1>

            <i class="fa-solid fa-microphone-lines"></i>

            Interview Preparation

        </h1>

        <p>

            Review your personalized AI-generated interview preparation.

        </p>

    </div>


    <div class="interview-content">


        <div class="interview-info">


            <div class="info-box">

                <strong>

                    <i class="fa-solid fa-briefcase"></i>

                    Job Role

                </strong>

                <span>

                    <?= htmlspecialchars($job_role); ?>

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

            <strong>Your Personalized Interview Guide</strong>

            <br>

            Review each question carefully, practice your answers, and use the guidance to prepare confidently for your interview.

        </div>


        <div class="interview-result">

            <?= $text ?>

        </div>


        <div class="action-buttons">

<a
href="download_interview.php?id=<?= $interview_id ?>"
class="btn-theme"
>

<i class="fa-solid fa-file-pdf"></i>

Download PDF

</a>
            <a

            href="interview.php"

            class="btn-theme"

            >

                <i class="fa-solid fa-wand-magic-sparkles"></i>

                Generate New Interview

            </a>


            <a

            href="my_interviews.php"

            class="btn-secondary-custom"

            >

                <i class="fa-solid fa-clock-rotate-left"></i>

                My Interviews

            </a>


            <a

            href="delete_interview.php?id=<?= $interview_id ?>"

            class="btn-danger-custom"

            onclick="return confirm('Are you sure you want to delete this interview preparation?');"

            >

                <i class="fa-solid fa-trash"></i>

                Delete Interview

            </a>


        </div>


    </div>

</div>

</body>

</html>