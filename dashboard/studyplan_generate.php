<?php

require_once "../config.php";
require_once "../api/gemini.php";
require_once "subscription_check.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid Request.");
}

$user_id = $_SESSION['user_id'];

/* ==============================
   STUDY PLAN LIMIT
============================== */

if ($isPremium) {

    // Premium users can generate up to 10 study plans per month

    $limit_query = mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM study_plans
         WHERE user_id='$user_id'
         AND MONTH(created_at)=MONTH(CURDATE())
         AND YEAR(created_at)=YEAR(CURDATE())"

    );

    $limit_data = mysqli_fetch_assoc($limit_query);

    if ($limit_data['total'] >= 10) {

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

        <i class='fa-solid fa-book-open'></i>

        </div>

        <h2 style='color:#172554;font-weight:700;'>

        Monthly Premium Limit Reached

        </h2>

        <p style='color:#64748B;font-size:16px;line-height:1.7;'>

        You have used your 10 study plans for this month.

        </p>

        <p style='color:#64748B;font-size:16px;line-height:1.7;'>

        Your premium access will reset next month.

        </p>

        <a href='studyplan.php' style='display:inline-block;margin-top:20px;padding:13px 28px;border-radius:30px;background:linear-gradient(135deg,#2563EB,#7C3AED);color:white;text-decoration:none;font-weight:600;'>

        <i class='fa-solid fa-arrow-left'></i>

        Back to Study Plans

        </a>

        </div>

        </div>

        </body>

        </html>

        ");

    }

} else {

    // Free users can generate only 1 study plan per day

    $limit_query = mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM study_plans
         WHERE user_id='$user_id'
         AND DATE(created_at)=CURDATE()"

    );

    $limit_data = mysqli_fetch_assoc($limit_query);

    if ($limit_data['total'] >= 1) {

        die("

        <h2>Daily Study Plan Limit Reached</h2>

        <p>Free users can generate 1 study plan per day.</p>

        <a href='subscription.php'>Upgrade to Premium</a>

        ");

    }

}
$technology = trim($_POST['technology'] ?? '');
$level = trim($_POST['level'] ?? '');
$hours = (int)($_POST['hours'] ?? 0);
$weeks = (int)($_POST['weeks'] ?? 0);

if (empty($technology) || empty($level) || $hours <= 0 || $weeks <= 0) {
    die("Please provide valid study plan details.");
}

$prompt = "

You are an experienced AI learning mentor.

Create a personalized study plan for the learner below.

Technology or Skill:
$technology

Current Skill Level:
$level

Available Study Hours Per Day:
$hours hours

Study Duration:
$weeks weeks

Create a practical and realistic study plan.

Include the following sections:

## Weekly Learning Roadmap

Break the learning journey into weekly phases.

## Daily Study Schedule

Suggest how the learner should use their available study hours.

## Skills and Topics to Learn

List the most important concepts in the correct learning order.

## Practice Exercises

Suggest practical exercises for each stage.

## Mini Projects

Recommend small projects that help build practical skills.

## Final Portfolio Project

Suggest one strong project that can be added to a portfolio.

## Recommended Free Resources

Suggest useful types of learning resources.

## Interview Preparation

Provide important topics and preparation tips.

## Productivity Tips

Give practical advice for staying consistent.

Use clear headings and bullet points.

Make the plan realistic, beginner-friendly, and actionable.

Do not use unnecessary symbols or decorative formatting.

";

$response = askGemini($prompt);

$user_id = $_SESSION['user_id'];

$technologySafe = mysqli_real_escape_string($conn, $technology);
$levelSafe = mysqli_real_escape_string($conn, $level);
$studyPlanSafe = mysqli_real_escape_string($conn, $response);

$sql = "INSERT INTO study_plans
(user_id, technology, level, hours, weeks, study_plan)
VALUES
('$user_id', '$technologySafe', '$levelSafe', '$hours', '$weeks', '$studyPlanSafe')";

if (!mysqli_query($conn, $sql)) {
    die("Database Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Study Plan | CareerPilot AI</title>

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

.study-container {
    max-width: 1100px;
    margin: auto;
}

.page-header {
    background: linear-gradient(135deg, #2563EB, #7C3AED);
    color: white;
    padding: 35px 40px;
    border-radius: 25px 25px 0 0;
    box-shadow: 0 15px 40px rgba(37, 99, 235, 0.20);
}

.page-header h1 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
}

.page-header p {
    margin: 10px 0 0;
    opacity: 0.9;
}

.study-content {
    background: white;
    padding: 40px;
    border-radius: 0 0 25px 25px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
}

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
    margin-bottom: 5px;
}

.info-box span {
    color: #6B7280;
}

.introduction {
    background: #F8FAFC;
    border-left: 5px solid #2563EB;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    line-height: 1.7;
}

.study-result {
    font-size: 16px;
    line-height: 1.8;
}

.study-result h2 {
    color: #2563EB;
    font-size: 24px;
    font-weight: 700;
    margin-top: 28px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid #E5E7EB;
}

.study-result h3 {
    color: #7C3AED;
    font-size: 20px;
    font-weight: 700;
    margin-top: 20px;
    margin-bottom: 8px;
}

.study-result p {
    margin-bottom: 10px;
}

.study-result ul {
    padding-left: 25px;
    margin-top: 5px;
    margin-bottom: 15px;
}

.study-result li {
    margin-bottom: 6px;
}

.study-result strong {
    color: #111827;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 35px;
}

.btn-theme {
    background: linear-gradient(135deg, #2563EB, #7C3AED);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 30px;
    font-weight: 600;
}

.btn-theme:hover {
    color: white;
    transform: translateY(-2px);
}

</style>

</head>

<body>

<div class="study-container">

    <div class="page-header">

        <h1>
            <i class="fa-solid fa-book-open-reader"></i>
            Your AI Study Plan
        </h1>

        <p>
            A personalized learning roadmap created to help you learn consistently and achieve your goals.
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
                    <?= htmlspecialchars($technology); ?>
                </span>
            </div>

            <div class="info-box">
                <strong>
                    <i class="fa-solid fa-signal"></i>
                    Skill Level
                </strong>

                <span>
                    <?= htmlspecialchars($level); ?>
                </span>
            </div>

            <div class="info-box">
                <strong>
                    <i class="fa-solid fa-clock"></i>
                    Study Time
                </strong>

                <span>
                    <?= $hours; ?> hours/day
                </span>
            </div>

            <div class="info-box">
                <strong>
                    <i class="fa-solid fa-calendar-days"></i>
                    Duration
                </strong>

                <span>
                    <?= $weeks; ?> weeks
                </span>
            </div>

        </div>

        <div class="introduction">

            <i class="fa-solid fa-circle-info text-primary"></i>

            <strong>Your Personalized Learning Plan</strong>

            <br>

            Follow this roadmap step by step, practice consistently, and build projects as you learn.

        </div>

        <?php

$text = htmlspecialchars($response);

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

        <div class="study-result">

            <?= $text ?>

        </div>

        <div class="action-buttons">

            <a href="studyplan.php" class="btn btn-theme">

                <i class="fa-solid fa-rotate-right"></i>

                Generate Another Study Plan

            </a>

            <a href="index.php" class="btn btn-secondary">

                <i class="fa-solid fa-house"></i>

                Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>