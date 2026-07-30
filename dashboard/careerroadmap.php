<?php

session_start();

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

<title>AI Career Roadmap | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    min-height: 100vh;

    background: #F5F7FF;

    font-family: 'Segoe UI', sans-serif;

    padding: 45px 20px;

}

/* Main Card */

.roadmap-card {

    max-width: 900px;

    margin: auto;

    background: white;

    border-radius: 25px;

    overflow: hidden;

    box-shadow: 0 20px 50px rgba(0,0,0,.08);

}

/* Header */

.header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 40px;

}

.header-content {

    display: flex;

    align-items: center;

    gap: 20px;

}

.header-icon {

    width: 65px;

    height: 65px;

    background: rgba(255,255,255,.2);

    border-radius: 18px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 30px;

}

.header h1 {

    font-size: 30px;

    font-weight: 700;

    margin: 0;

}

.header p {

    margin: 8px 0 0;

    opacity: .9;

}

.header-actions {

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

/* Form Area */

.form-area {

    padding: 40px;

}

.form-label {

    font-weight: 600;

    color: #374151;

    margin-bottom: 8px;

}

.input-group-text {

    background: #F8FAFC;

    border: 1px solid #D1D5DB;

    color: #2563EB;

}

.form-control,

.form-select {

    height: 52px;

    border-radius: 10px;

    border: 1px solid #D1D5DB;

    padding: 12px 15px;

}

.form-control:focus,

.form-select:focus {

    border-color: #2563EB;

    box-shadow: 0 0 0 4px rgba(37,99,235,.12);

}

/* Button */

.generate-btn {

    width: 100%;

    height: 55px;

    border: none;

    border-radius: 12px;

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    font-size: 16px;

    font-weight: 600;

    transition: .3s;

}

.generate-btn:hover {

    transform: translateY(-2px);

    box-shadow: 0 10px 25px rgba(37,99,235,.25);

}

/* Info Cards */

.info-box {

    background: #F8FAFC;

    border-radius: 15px;

    padding: 20px;

    margin-top: 30px;

    border-left: 4px solid #2563EB;

}

.info-box h6 {

    font-weight: 700;

    color: #1F2937;

}

.info-box p {

    color: #6B7280;

    margin: 0;

    font-size: 14px;

}

/* Back Button */

.back-btn {

    display: inline-block;

    margin-top: 25px;

    color: #2563EB;

    text-decoration: none;

    font-weight: 600;

}

.back-btn:hover {

    color: #7C3AED;

}

</style>

</head>

<body>

<div class="roadmap-card">

    <!-- HEADER -->

    <div class="header">

    <div class="header-content">

        <div class="header-icon">

            <i class="fa-solid fa-route"></i>

        </div>

        <div>

            <h1>AI Career Roadmap</h1>

            <p>Build a personalized path toward your dream career.</p>

        </div>

    </div>

    <div class="header-actions">

        <a href="my_career.php" class="history-btn">

            <i class="fa-solid fa-clock-rotate-left"></i>

            My Roadmaps

        </a>

    </div>

</div>


    <!-- FORM -->

    <div class="form-area">

        <form action="careerroadmap_generate.php" method="POST">

            <!-- CAREER -->

            <div class="mb-4">

                <label class="form-label">

                    <i class="fa-solid fa-bullseye text-primary me-2"></i>

                    Desired Career

                </label>

                <input

                    type="text"

                    name="career"

                    class="form-control"

                    placeholder="e.g. Software Engineer"

                    required>

            </div>


            <!-- EDUCATION -->

            <div class="mb-4">

                <label class="form-label">

                    <i class="fa-solid fa-graduation-cap text-primary me-2"></i>

                    Current Education

                </label>

                <input

                    type="text"

                    name="education"

                    class="form-control"

                    placeholder="e.g. BS Software Engineering"

                    required>

            </div>


            <!-- SKILL LEVEL -->

            <div class="mb-4">

                <label class="form-label">

                    <i class="fa-solid fa-chart-line text-primary me-2"></i>

                    Current Skill Level

                </label>

                <select

                    name="skill_level"

                    class="form-select"

                    required>

                    <option value="Beginner">Beginner</option>

                    <option value="Intermediate">Intermediate</option>

                    <option value="Advanced">Advanced</option>

                </select>

            </div>


            <!-- DURATION -->

            <div class="mb-4">

                <label class="form-label">

                    <i class="fa-solid fa-calendar-days text-primary me-2"></i>

                    Target Duration

                </label>

                <select

                    name="duration"

                    class="form-select"

                    required>

                    <option value="3 Months">3 Months</option>

                    <option value="6 Months">6 Months</option>

                    <option value="12 Months" selected>12 Months</option>

                    <option value="24 Months">24 Months</option>

                </select>

            </div>


            <!-- BUTTON -->

            <button

                type="submit"

                class="generate-btn">

                <i class="fa-solid fa-wand-magic-sparkles me-2"></i>

                Generate My AI Career Roadmap

            </button>

        </form>


        <!-- INFO -->

        <div class="info-box">

            <h6>

                <i class="fa-solid fa-lightbulb text-warning me-2"></i>

                How CareerPilot AI helps you

            </h6>

            <p>

                Our AI analyzes your career goal, education, skills, and timeline

                to create a personalized learning roadmap with recommended skills,

                technologies, projects, and milestones.

            </p>

        </div>


        <a href="index.php" class="back-btn">

            <i class="fa-solid fa-arrow-left me-2"></i>

            Back to Dashboard

        </a>

    </div>

</div>

</body>

</html>