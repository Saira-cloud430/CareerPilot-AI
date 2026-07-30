<?php

require_once "../config.php";

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

<title>Resume Analyzer | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

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

.resume-container {

    max-width: 1100px;

    margin: auto;

}

/* =========================
   HERO SECTION
========================= */

.resume-hero {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 45px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37, 99, 235, .20);

}

.resume-hero h1 {

    margin: 0;

    font-size: 34px;

    font-weight: 700;

}

.resume-hero p {

    margin-top: 14px;

    margin-bottom: 0;

    max-width: 750px;

    line-height: 1.7;

    opacity: .92;

}

.hero-icon {

    font-size: 150px;

    opacity: .16;

}

.hero-actions {

    margin-top: 25px;

}

.history-btn {

    display: inline-block;

    background: rgba(255,255,255,.18);

    color: white;

    border: 1px solid rgba(255,255,255,.35);

    padding: 11px 22px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

    transition: .3s;

}

.history-btn:hover {

    background: white;

    color: #2563EB;

}
/* =========================
   UPLOAD CARD
========================= */

.upload-card {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

.upload-title {

    font-size: 22px;

    font-weight: 700;

    color: #172554;

    margin-bottom: 25px;

}

.upload-area {

    border: 2px dashed #2563EB;

    border-radius: 18px;

    padding: 45px 25px;

    text-align: center;

    background: #F8FAFC;

    transition: .3s;

}

.upload-area:hover {

    background: #EFF6FF;

    border-color: #7C3AED;

}

.upload-area i {

    font-size: 58px;

    color: #2563EB;

    margin-bottom: 15px;

}

.upload-area h4 {

    font-weight: 700;

    color: #1F2937;

}

.upload-area p {

    color: #64748B;

    margin-bottom: 0;

}

.form-control {

    border: 1px solid #D1D5DB;

    border-radius: 10px;

    padding: 12px;

}

.form-control:focus {

    border-color: #2563EB;

    box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);

}

/* =========================
   BUTTONS
========================= */

.action-buttons {

    display: flex;

    justify-content: center;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 30px;

}

.btn-theme {

    border: none;

    color: white;

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    padding: 13px 28px;

    border-radius: 30px;

    font-weight: 600;

    transition: .3s;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

    box-shadow: 0 8px 20px rgba(37, 99, 235, .25);

}

.btn-dashboard {

    background: white;

    color: #2563EB;

    border: 1px solid #2563EB;

    padding: 13px 28px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    transition: .3s;

}

.btn-dashboard:hover {

    background: #2563EB;

    color: white;

    transform: translateY(-2px);

}

/* =========================
   FEATURE CARDS
========================= */

.feature-card {

    height: 100%;

    background: white;

    padding: 28px;

    border-radius: 18px;

    text-align: center;

    border: 1px solid #E5E7EB;

    box-shadow: 0 10px 30px rgba(0, 0, 0, .05);

    transition: .3s;

}

.feature-card:hover {

    transform: translateY(-5px);

    box-shadow: 0 15px 35px rgba(37, 99, 235, .12);

}

.feature-card i {

    font-size: 38px;

    color: #2563EB;

    margin-bottom: 15px;

}

.feature-card h5 {

    font-weight: 700;

    color: #172554;

}

.feature-card p {

    color: #64748B;

    line-height: 1.6;

    margin-bottom: 0;

}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .resume-hero,

    .upload-card {

        padding: 28px 22px;

    }

    .resume-hero h1 {

        font-size: 27px;

    }

    .hero-icon {

        display: none;

    }

}

</style>

</head>

<body>

<div class="resume-container">

    <!-- HERO -->

    <div class="resume-hero">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <h1>

                    <i class="fa-solid fa-file-lines"></i>

                    AI Resume Analyzer

                </h1>

                <p>

                    Upload your resume and let CareerPilot AI analyze it like an ATS recruiter.

                    Get an ATS score, identify missing keywords, improve your formatting,

                    and receive personalized recommendations to improve your chances of landing interviews.

                </p>

            </div>

            <div class="col-lg-4 text-center">

                <i class="fa-solid fa-brain hero-icon"></i>

            </div>

        </div>

    </div>
<div class="hero-actions">

    <a href="my_resumes.php" class="history-btn">

        <i class="fa-solid fa-clock-rotate-left"></i>

        My Resume Analyses

    </a>

</div>
    <!-- UPLOAD CARD -->

    <div class="upload-card">

        <div class="upload-title">

            <i class="fa-solid fa-cloud-arrow-up text-primary"></i>

            Upload Your Resume

        </div>

        <form

            action="resume_analyze.php"

            method="POST"

            enctype="multipart/form-data"

        >

            <div class="upload-area">

                <i class="fa-solid fa-file-pdf"></i>

                <h4>Select Your Resume</h4>

                <p>

                    Upload your resume in PDF format for AI-powered analysis.

                </p>

                <input

                    type="file"

                    name="resume"

                    accept=".pdf"

                    class="form-control mt-4"

                    required

                >

            </div>

            <div class="action-buttons">

                <button

                    type="submit"

                    class="btn btn-theme"

                >

                    <i class="fa-solid fa-wand-magic-sparkles"></i>

                    Analyze Resume

                </button>

            </div>

        </form>

    </div>

    <!-- FEATURES -->

    <div class="row mt-5 g-4">

        <div class="col-md-4">

            <div class="feature-card">

                <i class="fa-solid fa-chart-line"></i>

                <h5>ATS Score</h5>

                <p>

                    Measure how likely your resume is to pass Applicant Tracking Systems.

                </p>

            </div>

        </div>

        <div class="col-md-4">

            <div class="feature-card">

                <i class="fa-solid fa-lightbulb"></i>

                <h5>AI Suggestions</h5>

                <p>

                    Receive personalized recommendations to improve your skills, projects, and experience.

                </p>

            </div>

        </div>

        <div class="col-md-4">

            <div class="feature-card">

                <i class="fa-solid fa-user-tie"></i>

                <h5>Recruiter Perspective</h5>

                <p>

                    Understand how recruiters and ATS systems may evaluate your resume.

                </p>

            </div>

        </div>

    </div>

    <!-- BACK TO DASHBOARD -->

    <div class="action-buttons mb-4">

        <a

            href="index.php"

            class="btn-dashboard"

        >

            <i class="fa-solid fa-house"></i>

            Back to Dashboard

        </a>

    </div>

</div>

</body>

</html>