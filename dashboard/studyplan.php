<?php

require_once "../config.php";
require_once "subscription_check.php";


/* ==============================
   AUTHENTICATION
============================== */

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>AI Study Planner | CareerPilot AI</title>


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

    min-height: 100vh;

    padding: 40px 20px;

    background: #F5F7FF;

    color: #1F2937;

    font-family: 'Segoe UI', sans-serif;

}


.study-container {

    max-width: 1100px;

    margin: auto;

}


/* ==============================
   HERO
============================== */


.page-header {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    color: white;

    padding: 45px;

    border-radius: 28px 28px 0 0;

    box-shadow: 0 15px 40px rgba(

        37,

        99,

        235,

        .20

    );

}


.page-header h1 {

    margin: 0;

    font-weight: 800;

    font-size: 34px;

}


.page-header p {

    margin-top: 14px;

    margin-bottom: 0;

    opacity: .92;

    font-size: 16px;

    line-height: 1.7;

    max-width: 700px;

}


.hero-icon {

    font-size: 110px;

    opacity: .16;

}


/* ==============================
   CONTENT
============================== */


.study-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 28px 28px;

    box-shadow: 0 20px 50px rgba(

        0,

        0,

        0,

        .08

    );

}


/* ==============================
   PREMIUM INFO
============================== */


.plan-info {

    display: flex;

    align-items: center;

    gap: 14px;

    background: #F8FAFC;

    border-left: 5px solid #2563EB;

    padding: 18px 20px;

    border-radius: 14px;

    margin-bottom: 30px;

}


.plan-info i {

    font-size: 24px;

    color: #2563EB;

}


.plan-info strong {

    color: #172554;

}


.premium-badge {

    display: inline-block;

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    color: white;

    padding: 5px 12px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: 700;

    margin-left: 8px;

}


/* ==============================
   INTRODUCTION
============================== */


.introduction {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    background: #F8FAFC;

    border-left: 5px solid #7C3AED;

    padding: 20px;

    border-radius: 12px;

    margin-bottom: 35px;

    line-height: 1.7;

}


.introduction i {

    color: #7C3AED;

    font-size: 20px;

    margin-top: 3px;

}


/* ==============================
   FORM GRID
============================== */


.form-grid {

    display: grid;

    grid-template-columns: repeat(

        2,

        1fr

    );

    gap: 25px;

}


.form-group {

    margin-bottom: 5px;

}


.form-group.full-width {

    grid-column: 1 / -1;

}


/* ==============================
   LABELS
============================== */


.form-label {

    font-weight: 600;

    color: #374151;

    margin-bottom: 8px;

}


/* ==============================
   INPUTS
============================== */


.form-control,

.form-select {

    min-height: 50px;

    border: 1px solid #D1D5DB;

    border-radius: 12px;

    padding: 12px 15px;

    transition: .2s;

}


.form-control:focus,

.form-select:focus {

    border-color: #2563EB;

    box-shadow: 0 0 0 3px rgba(

        37,

        99,

        235,

        .12

    );

}


/* ==============================
   INPUT ICONS
============================== */


.input-wrapper {

    position: relative;

}


.input-wrapper i {

    position: absolute;

    left: 16px;

    top: 17px;

    color: #6B7280;

    z-index: 2;

}


.input-wrapper .form-control,

.input-wrapper .form-select {

    padding-left: 45px;

}


/* ==============================
   HELP TEXT
============================== */


.form-help {

    color: #6B7280;

    font-size: 13px;

    margin-top: 6px;

}


/* ==============================
   ACTION AREA
============================== */


.action-area {

    margin-top: 35px;

    padding-top: 25px;

    border-top: 1px solid #E5E7EB;

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

}


.btn-theme {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    border: none;

    color: white;

    padding: 13px 28px;

    border-radius: 30px;

    font-weight: 600;

    transition: .3s;

}


.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

    box-shadow: 0 8px 20px rgba(

        37,

        99,

        235,

        .25

    );

}


.back-btn {

    padding: 13px 28px;

    border-radius: 30px;

    font-weight: 600;

}


/* ==============================
   FEATURE CARDS
============================== */


.feature-row {

    margin-top: 35px;

}


.feature-card {

    height: 100%;

    padding: 22px;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-radius: 16px;

    transition: .3s;

}


.feature-card:hover {

    transform: translateY(-5px);

    box-shadow: 0 10px 25px rgba(

        37,

        99,

        235,

        .10

    );

}


.feature-card i {

    color: #2563EB;

    font-size: 28px;

    margin-bottom: 12px;

}


.feature-card h6 {

    font-weight: 700;

    color: #172554;

}


.feature-card p {

    color: #64748B;

    font-size: 14px;

    margin-bottom: 0;

    line-height: 1.6;

}


/* ==============================
   RESPONSIVE
============================== */


@media (max-width: 768px) {


    body {

        padding: 20px 10px;

    }


    .page-header,

    .study-content {

        padding: 25px;

    }


    .page-header h1 {

        font-size: 27px;

    }


    .hero-icon {

        display: none;

    }


    .form-grid {

        grid-template-columns: 1fr;

    }


    .form-group.full-width {

        grid-column: auto;

    }


    .action-area {

        flex-direction: column;

    }


    .action-area .btn {

        width: 100%;

    }


}


</style>


</head>


<body>


<div class="study-container">


<!-- ==============================
     HEADER
============================== -->


<div class="page-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <h1>

                <i class="fa-solid fa-book-open-reader"></i>

                AI Study Planner

            </h1>

            <p>

                Create a personalized learning plan designed around your goals, skill level, and available study time.

            </p>
<div class="mt-4">

    <a href="my_studyplans.php" class="btn btn-light">

        <i class="fa-solid fa-folder-open"></i>

        View My Saved Study Plans

    </a>

</div>
        </div>

        <a href="my_studyplans.php" class="btn btn-light">

            <i class="fa-solid fa-clock-rotate-left"></i>

            My Study Plans

        </a>

    </div>

</div>


<div class="col-md-4 text-center">


<i class="fa-solid fa-graduation-cap hero-icon"></i>


</div>


</div>


</div>


<!-- ==============================
     CONTENT
============================== -->


<div class="study-content">


<!-- PLAN STATUS -->


<div class="plan-info">


<i class="fa-solid fa-crown"></i>


<div>


<strong>

Your CareerPilot AI Study Planner

</strong>


<br>


<span class="text-muted">


<?php if ($isPremium) { ?>


Premium plan active — enjoy advanced study planning features.


<span class="premium-badge">

PREMIUM

</span>


<?php } else { ?>


Free plan users can create a limited number of study plans. Upgrade anytime for more AI-powered planning.


<?php } ?>


</span>


</div>


</div>


<!-- INTRODUCTION -->


<div class="introduction">


<i class="fa-solid fa-circle-info"></i>


<div>


<strong>

Your Personalized Study Plan

</strong>


<br>


Tell us what you want to learn and how much time you can dedicate. CareerPilot AI will create a structured study plan to help you learn consistently and reach your goal.


</div>


</div>


<!-- FORM -->


<form

action="studyplan_generate.php"

method="POST"


>


<div class="form-grid">


<!-- TECHNOLOGY -->


<div class="form-group full-width">


<label class="form-label">


<i class="fa-solid fa-code text-primary me-1"></i>


Technology or Skill


</label>


<div class="input-wrapper">


<i class="fa-solid fa-code"></i>


<input

type="text"

name="technology"

class="form-control"

placeholder="e.g. MERN Stack, Flutter, AI, Python"

required


>


</div>


<div class="form-help">


Enter the technology, programming language, or skill you want to learn.


</div>


</div>


<!-- LEVEL -->


<div class="form-group">


<label class="form-label">


<i class="fa-solid fa-chart-line text-primary me-1"></i>


Current Skill Level


</label>


<div class="input-wrapper">


<i class="fa-solid fa-chart-line"></i>


<select

name="level"

class="form-select"

required


>


<option value="Beginner">

Beginner

</option>


<option value="Intermediate">

Intermediate

</option>


<option value="Advanced">

Advanced

</option>


</select>


</div>


</div>


<!-- HOURS -->


<div class="form-group">


<label class="form-label">


<i class="fa-solid fa-clock text-primary me-1"></i>


Hours Per Day


</label>


<div class="input-wrapper">


<i class="fa-solid fa-clock"></i>


<input

type="number"

name="hours"

class="form-control"

placeholder="e.g. 2"

min="1"

max="24"

required


>


</div>


</div>


<!-- DURATION -->


<div class="form-group">


<label class="form-label">


<i class="fa-solid fa-calendar-days text-primary me-1"></i>


Study Duration


</label>


<div class="input-wrapper">


<i class="fa-solid fa-calendar-days"></i>


<input

type="number"

name="weeks"

class="form-control"

placeholder="e.g. 12"

min="1"

max="52"

required


>


</div>


<div class="form-help">


Enter the number of weeks you want to follow the study plan.


</div>


</div>


</div>


<!-- ACTIONS -->


<div class="action-area">


<button

type="submit"

class="btn btn-theme"


>


<i class="fa-solid fa-wand-magic-sparkles"></i>


Generate AI Study Plan


</button>


<a

href="index.php"

class="btn btn-outline-primary back-btn"


>


<i class="fa-solid fa-arrow-left"></i>


Back to Dashboard


</a>


</div>


</form>


<!-- FEATURES -->


<div class="row g-3 feature-row">


<div class="col-md-4">


<div class="feature-card">


<i class="fa-solid fa-calendar-check"></i>


<h6>

Structured Learning

</h6>


<p>

Get a step-by-step learning plan organized according to your selected duration.


</p>


</div>


</div>


<div class="col-md-4">


<div class="feature-card">


<i class="fa-solid fa-bullseye"></i>


<h6>

Goal-Focused Progress

</h6>


<p>

Follow a clear path based on your current skill level and learning goals.


</p>


</div>


</div>


<div class="col-md-4">


<div class="feature-card">


<i class="fa-solid fa-file-pdf"></i>


<h6>

Download Your Plan

</h6>


<p>

After generating your study plan, you will be able to download it as a PDF report.


</p>


</div>


</div>


</div>


</div>


</div>


</body>


</html>