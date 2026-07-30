<?php

require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


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
   FETCH ONLY USER'S OWN INTERVIEW
============================== */

$stmt = mysqli_prepare(

    $conn,

    "SELECT job_role, feedback, created_at
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


$job_role = htmlspecialchars(

    $interview['job_role'],

    ENT_QUOTES,

    'UTF-8'

);


$created_at = date(

    "F d, Y - h:i A",

    strtotime($interview['created_at'])

);


$feedback = htmlspecialchars(

    $interview['feedback'],

    ENT_QUOTES,

    'UTF-8'

);


/* ==============================
   FORMAT AI CONTENT FOR PDF
============================== */


/*
   Convert Markdown bold
*/

$feedback = preg_replace(

    '/\*\*(.*?)\*\*/s',

    '<strong>$1</strong>',

    $feedback

);


/*
   Convert Question headings
*/

$feedback = preg_replace(

    '/###\s*(Question\s*\d+)/i',

    '<h3 class="question-title">$1</h3>',

    $feedback

);


/*
   Convert main headings
*/

$feedback = preg_replace(

    '/##\s*(Common Interview Mistakes|Final Interview Preparation Tips)/i',

    '<h2 class="section-title">$1</h2>',

    $feedback

);


/*
   Convert actual interview question
*/

$feedback = preg_replace(

    '/The Interview Question\s*(.*?)\s*(?=Why the interviewer asks it|Tips for answering|A sample strong answer)/is',

    '<div class="question-text">$1</div>',

    $feedback

);


/*
   Convert section labels
*/

$feedback = str_replace(

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

        '<h2 class="section-title">

            Common Interview Mistakes

        </h2>',

        '<h2 class="section-title">

            Final Interview Preparation Tips

        </h2>'

    ],

    $feedback

);


/*
   Convert bullet points
*/

$feedback = preg_replace(

    '/^\s*[-*•]\s*(.*?)$/m',

    '<li>$1</li>',

    $feedback

);


/*
   Wrap list items

*/

$feedback = preg_replace(

    '/((?:<li>.*?<\/li>\s*)+)/s',

    '<ul>$1</ul>',

    $feedback

);


/*
   Convert line breaks

*/

$feedback = nl2br($feedback);


/* ==============================
   CREATE PDF HTML
============================== */

$html = '

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<style>

@page {

    margin: 30px;

}

body {

    font-family: DejaVu Sans, sans-serif;

    background: #F5F7FF;

    color: #1F2937;

    line-height: 1.6;

}

.header {

    background: #2563EB;

    color: white;

    padding: 25px;

    border-radius: 12px;

    margin-bottom: 25px;

}

.brand {

    font-size: 12px;

    font-weight: bold;

    letter-spacing: 1px;

    margin-bottom: 10px;

}

.header h1 {

    margin: 0;

    font-size: 25px;

}

.header p {

    margin-top: 8px;

    font-size: 13px;

}

.info {

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    padding: 15px;

    border-radius: 10px;

    margin-bottom: 25px;

}

.info strong {

    color: #2563EB;

}

.content {

    background: white;

    padding: 25px;

    border-radius: 12px;

}

.question-title {

    color: #2563EB;

    font-size: 20px;

    border-bottom: 1px solid #E5E7EB;

    padding-bottom: 8px;

    margin-top: 25px;

}

.question-text {

    background: #EFF6FF;

    border-left: 4px solid #7C3AED;

    padding: 12px;

    margin: 12px 0 20px;

    font-weight: bold;

}

.answer-heading {

    color: #374151;

    font-size: 15px;

    font-weight: bold;

    margin-top: 18px;

    margin-bottom: 8px;

}

.section-title {

    color: #2563EB;

    font-size: 21px;

    border-bottom: 1px solid #E5E7EB;

    padding-bottom: 8px;

    margin-top: 30px;

}

ul {

    padding-left: 25px;

}

li {

    margin-bottom: 6px;

}

</style>

</head>

<body>

<div class="header">

    <div class="brand">

        CAREERPILOT AI

    </div>

    <h1>

        AI Interview Preparation Report

    </h1>

    <p>

        Personalized interview preparation generated by CareerPilot AI

    </p>

</div>

<div class="info">

    <strong>Job Role:</strong>

    ' . $job_role . '

    <br>

    <strong>Created On:</strong>

    ' . $created_at . '

</div>

<div class="content">

    ' . $feedback . '

</div>

</body>

</html>

';


/* ==============================
   GENERATE PDF
============================== */

$options = new Options();

$options->set(

    'defaultFont',

    'DejaVu Sans'

);


$dompdf = new Dompdf($options);


$dompdf->loadHtml($html);


$dompdf->setPaper(

    'A4',

    'portrait'

);


$dompdf->render();


$filename = 'CareerPilot_Interview_' .

    preg_replace(

        '/[^A-Za-z0-9_-]/',

        '_',

        $interview['job_role']

    ) .

    '.pdf';


$dompdf->stream(

    $filename,

    [

        "Attachment" => true

    ]

);

exit();

?>