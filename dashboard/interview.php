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

<title>Interview Coach | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

:root {

    --primary: #2563EB;

    --secondary: #7C3AED;

    --dark: #172554;

    --text: #334155;

    --background: #F5F7FF;

}

* {

    box-sizing: border-box;

}

body {

    margin: 0;

    background: var(--background);

    font-family: "Segoe UI", sans-serif;

    color: var(--text);

}

.page-wrapper {

    min-height: 100vh;

    padding: 40px 20px;

}

.main-container {

    max-width: 1050px;

    margin: auto;

}

/* HERO */

.hero-section {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 45px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37, 99, 235, .20);

}

.brand {

    font-size: 14px;

    font-weight: 600;

    letter-spacing: .6px;

    opacity: .9;

}

.hero-title {

    font-size: 34px;

    font-weight: 750;

    margin-top: 12px;

}

.hero-text {

    max-width: 700px;

    margin-top: 12px;

    opacity: .92;

    font-size: 16px;

    line-height: 1.7;

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

/* FORM */

.form-section {

    background: white;

    padding: 42px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(15, 23, 42, .08);

}

.form-title {

    font-size: 22px;

    font-weight: 700;

    color: var(--dark);

    margin-bottom: 28px;

}

.form-label {

    font-weight: 600;

    color: #334155;

    margin-bottom: 8px;

}

.form-control,

.form-select {

    border: 1px solid #DBE3F0;

    border-radius: 12px;

    padding: 13px 15px;

    font-size: 15px;

}

.form-control:focus,

.form-select:focus {

    border-color: var(--primary);

    box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);

}

/* BUTTONS */

.generate-btn {

    border: none;

    color: white;

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    padding: 14px 28px;

    border-radius: 30px;

    font-weight: 600;

    font-size: 16px;

    transition: .3s;

}

.generate-btn:hover {

    transform: translateY(-2px);

    color: white;

}

/* INFO */

.info-card {

    background: #F8FAFC;

    border-radius: 16px;

    padding: 22px;

    margin-top: 30px;

    border-left: 5px solid var(--primary);

}

.info-card h6 {

    font-weight: 700;

    color: var(--dark);

}

.info-card p {

    margin: 0;

    font-size: 14px;

    line-height: 1.7;

    color: #64748B;

}

/* BACK BUTTON */

.back-btn {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    margin-top: 25px;

    padding: 12px 25px;

    border-radius: 30px;

    border: 1px solid #CBD5E1;

    color: #475569;

    background: white;

    text-decoration: none;

    font-weight: 600;

    transition: .3s;

}

.back-btn:hover {

    color: #2563EB;

    border-color: #2563EB;

    transform: translateY(-2px);

}

/* RESPONSIVE */

@media(max-width: 768px) {

    .hero-section {

        padding: 30px 22px;

        text-align: center;

    }

    .hero-title {

        font-size: 27px;

    }

    .form-section {

        padding: 25px 20px;

    }

    .back-btn {

        width: 100%;

        justify-content: center;

    }

}

</style>

</head>

<body>

<div class="page-wrapper">

<div class="main-container">

<!-- HERO -->

<div class="hero-section">

<div class="brand">

<i class="fa-solid fa-rocket"></i>

CAREERPILOT AI

</div>

<div class="hero-title">

<i class="fa-solid fa-microphone-lines"></i>

AI Interview Coach

</div>

<div class="hero-text">

Prepare smarter for your next interview with AI-generated questions, answer guidance, and role-specific preparation designed around your career goals.

</div>
<div class="hero-actions">

    <a href="my_interviews.php" class="history-btn">

        <i class="fa-solid fa-clock-rotate-left"></i>

        My Interviews

    </a>

</div>

</div>

<!-- FORM -->

<div class="form-section">

<div class="form-title">

<i class="fa-solid fa-sliders text-primary"></i>

Customize Your Interview Preparation

</div>

<form action="interview_generate.php" method="POST">

<div class="row g-4">

<div class="col-md-12">

<label class="form-label">

<i class="fa-solid fa-briefcase text-primary"></i>

Job Role

</label>

<input

type="text"

name="job"

class="form-control"

placeholder="e.g. PHP Backend Developer"

required>

</div>

<div class="col-md-6">

<label class="form-label">

<i class="fa-solid fa-layer-group text-primary"></i>

Experience Level

</label>

<select name="experience" class="form-select" required>

<option value="Beginner">Beginner</option>

<option value="Intermediate">Intermediate</option>

<option value="Advanced">Advanced</option>

</select>

</div>

<div class="col-md-6">

<label class="form-label">

<i class="fa-solid fa-list-ol text-primary"></i>

Number of Questions

</label>

<select name="questions" class="form-select" required>

<option value="5">5 Questions</option>

<option value="10">10 Questions</option>

<option value="15">15 Questions</option>

</select>

</div>

<div class="col-12">

<button type="submit" class="generate-btn">

<i class="fa-solid fa-wand-magic-sparkles"></i>

Generate My Interview

</button>

</div>

</div>

</form>

<!-- INFO -->

<div class="info-card">

<h6>

<i class="fa-solid fa-lightbulb text-warning"></i>

What you'll get

</h6>

<p>

AI-generated interview questions, why each question is asked, tips for answering, strong sample answers, common mistakes, and final preparation advice tailored to your selected role.

</p>

</div>

<!-- BACK TO DASHBOARD -->

<div class="text-center">

<a href="index.php" class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

Back to Dashboard

</a>

</div>

</div>

</div>

</div>

</body>

</html>