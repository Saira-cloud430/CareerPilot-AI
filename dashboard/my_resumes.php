<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];


/* ==============================
   FETCH USER'S RESUME REPORTS
============================== */

$stmt = mysqli_prepare(

    $conn,

    "SELECT id, resume_file, uploaded_at
     FROM resumes
     WHERE user_id = ?
     ORDER BY uploaded_at DESC"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $user_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Resume Analyses | CareerPilot AI</title>

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

.resume-container {

    max-width: 1100px;

    margin: auto;

}

/* HEADER */

.page-header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 40px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37, 99, 235, .20);

}

.page-header h1 {

    margin: 0;

    font-size: 32px;

    font-weight: 700;

}

.page-header p {

    margin-top: 10px;

    margin-bottom: 0;

    opacity: .9;

}

/* CONTENT */

.resume-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0, 0, 0, .08);

}

/* REPORT CARD */

.resume-card {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

    padding: 22px;

    margin-bottom: 18px;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-left: 5px solid #2563EB;

    border-radius: 15px;

    transition: .3s;

}

.resume-card:hover {

    transform: translateY(-3px);

    box-shadow: 0 10px 25px rgba(37, 99, 235, .10);

}

.resume-info {

    display: flex;

    align-items: center;

    gap: 18px;

}

.resume-icon {

    width: 55px;

    height: 55px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 14px;

    background: #EFF6FF;

    color: #2563EB;

    font-size: 25px;

}

.resume-info h5 {

    margin: 0;

    color: #172554;

    font-weight: 700;

}

.resume-info p {

    margin: 6px 0 0;

    color: #64748B;

    font-size: 14px;

}

.card-actions {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;

}

/* BUTTONS */

.btn-view {

    background: #2563EB;

    color: white;

    padding: 10px 18px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-view:hover {

    background: #1D4ED8;

    color: white;

}

.btn-download {

    background: #F1F5F9;

    color: #475569;

    padding: 10px 18px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-download:hover {

    background: #E2E8F0;

    color: #1E293B;

}

.btn-delete {

    background: #FEE2E2;

    color: #B91C1C;

    padding: 10px 18px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-delete:hover {

    background: #FECACA;

    color: #991B1B;

}

/* EMPTY STATE */

.empty-state {

    text-align: center;

    padding: 60px 20px;

}

.empty-state i {

    font-size: 65px;

    color: #CBD5E1;

    margin-bottom: 20px;

}

.empty-state h3 {

    color: #172554;

    font-weight: 700;

}

.empty-state p {

    color: #64748B;

}

/* BOTTOM ACTIONS */

.bottom-actions {

    display: flex;

    gap: 15px;

    flex-wrap: wrap;

    margin-top: 30px;

}

.btn-theme {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 12px 25px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

.btn-dashboard {

    background: #F1F5F9;

    color: #475569;

    padding: 12px 25px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

}

.btn-dashboard:hover {

    background: #E2E8F0;

    color: #1E293B;

}

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .resume-content {

        padding: 25px;

    }

    .resume-card {

        flex-direction: column;

        align-items: flex-start;

    }

    .card-actions {

        width: 100%;

    }

    .card-actions a {

        flex: 1;

        text-align: center;

    }

}

</style>

</head>

<body>

<div class="resume-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-clock-rotate-left"></i>

            My Resume Analyses

        </h1>

        <p>

            Review your previous AI-powered resume analysis reports anytime.

        </p>

    </div>

    <div class="resume-content">

        <?php if (mysqli_num_rows($result) > 0): ?>

            <?php while ($resume = mysqli_fetch_assoc($result)): ?>

                <div class="resume-card">

                    <div class="resume-info">

                        <div class="resume-icon">

                            <i class="fa-solid fa-file-pdf"></i>

                        </div>

                        <div>

                            <h5>

                                <?= htmlspecialchars($resume['resume_file']); ?>

                            </h5>

                            <p>

                                <i class="fa-solid fa-calendar-days"></i>

                                Analyzed on:

                                <?= date(

                                    "F d, Y - h:i A",

                                    strtotime($resume['uploaded_at'])

                                ); ?>

                            </p>

                        </div>

                    </div>

                    <div class="card-actions">

                        <a

                            href="view_resume.php?id=<?= $resume['id']; ?>"

                            class="btn-view"

                        >

                            <i class="fa-solid fa-eye"></i>

                            View

                        </a>

                        <a

                            href="download_report.php?id=<?= $resume['id']; ?>"

                            class="btn-download"

                        >

                            <i class="fa-solid fa-file-pdf"></i>

                            PDF

                        </a>

                        <a

                            href="delete_resume.php?id=<?= $resume['id']; ?>"

                            class="btn-delete"

                            onclick="return confirm('Are you sure you want to delete this resume analysis?');"

                        >

                            <i class="fa-solid fa-trash"></i>

                            Delete

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-state">

                <i class="fa-solid fa-file-circle-xmark"></i>

                <h3>No Resume Analyses Yet</h3>

                <p>

                    Upload your resume to get your first AI-powered analysis.

                </p>

                <a href="resume.php" class="btn-theme">

                    <i class="fa-solid fa-wand-magic-sparkles"></i>

                    Analyze My Resume

                </a>

            </div>

        <?php endif; ?>

        <div class="bottom-actions">

            <a href="resume.php" class="btn-theme">

                <i class="fa-solid fa-plus"></i>

                Analyze New Resume

            </a>

            <a href="index.php" class="btn-dashboard">

                <i class="fa-solid fa-house"></i>

                Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>