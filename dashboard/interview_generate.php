<?php

/* ============================================================
   PHP + DATABASE + AI PROCESSING
============================================================ */

require_once "../config.php";
require_once "../api/gemini.php";
require_once "subscription_check.php";

$user_id = $_SESSION['user_id'];
/* ============================================================
   CHECK LOGIN
============================================================ */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


/* ============================================================
   CHECK REQUEST
============================================================ */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: interview.php");

    exit();

}


/* ============================================================
   FREE USER DAILY LIMIT
============================================================ */

$daily_limit = $isPremium ? 10 : 2;

$limit_query = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total
     FROM interview_sessions
     WHERE user_id='$user_id'
     AND DATE(created_at)=CURDATE()"

);

$limit_data = mysqli_fetch_assoc($limit_query);

if ($limit_data['total'] >= $daily_limit) {

        die("

<!DOCTYPE html>

<html lang='en'>

<head>

<meta charset='UTF-8'>

<meta name='viewport' content='width=device-width, initial-scale=1.0'>

<title>Premium Required | CareerPilot AI</title>

<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css' rel='stylesheet'>

<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>

<style>

body{

    background:#F5F7FF;

    font-family:'Segoe UI',sans-serif;

}

.limit-container{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:20px;

}

.limit-card{

    max-width:550px;

    width:100%;

    background:white;

    border-radius:25px;

    padding:45px;

    text-align:center;

    box-shadow:0 20px 50px rgba(0,0,0,.10);

}

.limit-icon{

    width:90px;

    height:90px;

    margin:0 auto 25px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:40px;

}

.limit-card h2{

    color:#172554;

    font-weight:700;

    margin-bottom:15px;

}

.limit-card p{

    color:#64748B;

    font-size:16px;

    line-height:1.7;

}

.btn-upgrade{

    display:inline-block;

    margin-top:20px;

    padding:13px 28px;

    border-radius:30px;

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    text-decoration:none;

    font-weight:600;

}

.btn-upgrade:hover{

    color:white;

    transform:translateY(-2px);

}

.btn-back{

    display:block;

    margin-top:15px;

    color:#64748B;

    text-decoration:none;

}

</style>

</head>

<body>

<div class='limit-container'>

<div class='limit-card'>

<div class='limit-icon'>

<i class='fa-solid fa-crown'></i>

</div>

<h2>Daily Interview Limit Reached</h2>

<p>

You have used your 2 free AI interview preparations for today.

</p>

<p>

Upgrade to Premium to unlock up to 10 AI interview preparations per day.

</p>

<a href='subscription.php' class='btn-upgrade'>

<i class='fa-solid fa-crown'></i>

Upgrade to Premium

</a>

<a href='interview.php' class='btn-back'>

<i class='fa-solid fa-arrow-left'></i>

Back to Interview Preparation

</a>

</div>

</div>

</body>

</html>

");

    }



/* ============================================================
   GET FORM DATA
============================================================ */

$job = trim($_POST['job'] ?? '');

$experience = trim($_POST['experience'] ?? '');

$questions = intval($_POST['questions'] ?? 5);


/* ============================================================
   BASIC VALIDATION
============================================================ */

if ($job === '') {

    die("Please enter a job role.");

}

if (!in_array($questions, [5, 10, 15])) {

    $questions = 5;

}


/* ============================================================
   AI PROMPT
============================================================ */

$prompt = "

You are an expert technical interviewer.

Prepare a personalized interview for the following candidate.

Job Role:
$job

Experience Level:
$experience

Generate exactly $questions interview questions.

For every question, follow this exact format:

### Question 1

The Interview Question

Write the actual interview question.

Why the interviewer asks it

Explain what the interviewer wants to evaluate.

Tips for answering

- Give practical answering advice.
- Mention important points the candidate should include.

A sample strong answer

Write a professional sample answer.

Continue the same structure for all questions.

At the end include:

## Common Interview Mistakes

- Mistake 1
- Mistake 2
- Mistake 3

## Final Interview Preparation Tips

- Tip 1
- Tip 2
- Tip 3

IMPORTANT RULES:

Do not use colons after headings.

Do not use semicolons.

Do not use unnecessary symbols or decorative formatting.

Use clear, professional language.

";


/* ============================================================
   GENERATE INTERVIEW
============================================================ */

$interview = askGemini($prompt);

if (is_array($interview) && isset($interview['error'])) {

    die("

    <div style='
        padding:30px;
        margin:50px auto;
        max-width:700px;
        font-family:Arial;
        background:#fff3cd;
        border:1px solid #ffeeba;
        border-radius:10px;
        color:#856404;
    '>

        <h2>AI Service Temporarily Unavailable</h2>

        <p>
        Gemini API daily limit has been reached.
        Please try again later.
        </p>

    </div>

    ");

}


/* ============================================================
   SAVE INTERVIEW TO DATABASE
============================================================ */

$insert = mysqli_prepare(

    $conn,

    "INSERT INTO interview_sessions
    (user_id, job_role, feedback)
    VALUES (?, ?, ?)"

);

mysqli_stmt_bind_param(

    $insert,

    "iss",

    $user_id,

    $job,

    $interview

);

mysqli_stmt_execute($insert);
/* ============================================================
   GET GENERATED INTERVIEW ID
============================================================ */

$interviewId = mysqli_insert_id($conn);


/* ============================================================
   FORMAT AI RESPONSE
============================================================ */

$text = htmlspecialchars(

    $interview,

    ENT_QUOTES,

    'UTF-8'

);


/* Convert bold Markdown */

$text = preg_replace(

    '/\*\*(.*?)\*\*/s',

    '<strong>$1</strong>',

    $text

);


/* Convert Question headings */

$text = preg_replace(

    '/###\s*(Question\s*\d+)/i',

    '<h3 class="question-title">$1</h3>',

    $text

);


/* Convert main headings */

$text = preg_replace(

    '/##\s*(Common Interview Mistakes|Final Interview Preparation Tips)/i',

    '<h2 class="section-title">$1</h2>',

    $text

);


/* Highlight actual interview question */

$text = preg_replace(

    '/The Interview Question\s*(.*?)\s*(?=Why the interviewer asks it|Tips for answering|A sample strong answer)/is',

    '<div class="question-text">$1</div>',

    $text

);


/* Convert section labels */

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


/* Convert bullet points */

$text = preg_replace(

    '/^\s*[-*•]\s*(.*?)$/m',

    '<li>$1</li>',

    $text

);


/* Wrap list items */

$text = preg_replace(

    '/((?:<li>.*?<\/li>\s*)+)/s',

    '<ul>$1</ul>',

    $text

);


/* Remove extra empty lines */

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

AI Interview Preparation | CareerPilot AI

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

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    padding: 40px 20px;

    color: #1F2937;

}

.interview-container {

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

    box-shadow: 0 15px 40px rgba(37, 99, 235, .20);

}

.page-header h1 {

    margin: 0;

    font-weight: 700;

}

.page-header p {

    margin-top: 10px;

    margin-bottom: 0;

    opacity: .9;

}

.interview-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

.introduction {

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 30px;

    line-height: 1.8;

}

.introduction i {

    color: #2563EB;

    margin-right: 8px;

}

.introduction p {

    margin: 8px 0 0;

}

.question-title {

    color: #2563EB;

    font-size: 24px;

    font-weight: 700;

    margin-top: 30px;

    margin-bottom: 20px;

    padding-bottom: 12px;

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

    margin-bottom: 25px;

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

.interview-result {

    line-height: 1.8;

}

.interview-result strong {

    font-weight: 700;

}

.interview-result ul {

    padding-left: 25px;

    margin-top: 10px;

}

.interview-result li {

    margin-bottom: 10px;

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

    border: none;

    color: white;

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

    border: none;

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

    .page-header {

        padding: 25px;

    }

    .page-header h1 {

        font-size: 25px;

    }

    .interview-content {

        padding: 20px;

    }

    .question-text {

        font-size: 16px;

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

<div class="interview-container">

<div class="page-header">

<h1>

<i class="fa-solid fa-microphone-lines"></i>

AI Interview Preparation

</h1>

<p>

Personalized interview questions and expert preparation guidance generated by CareerPilot AI.

</p>

</div>

<div class="interview-content">

<div class="introduction">

<i class="fa-solid fa-circle-info"></i>

<strong>

Your Personalized Interview Guide

</strong>

<p>

Use the questions below to practice your answers and prepare confidently for your interview.

</p>

</div>

<div class="interview-result">

<?= $text ?>

</div>
<div class="action-buttons">

<a
href="download_interview.php?id=<?= $interviewId ?>"
class="btn btn-theme">

<i class="fa-solid fa-file-pdf"></i>

Download Interview PDF

</a>

<a
href="interview.php"
class="btn btn-theme">

<i class="fa-solid fa-rotate-right"></i>

Generate Another Interview

</a>

<a
href="index.php"
class="btn btn-secondary">

<i class="fa-solid fa-house"></i>

Back to Dashboard

</a>

</div>

</div>

</div>

</body>

</html>