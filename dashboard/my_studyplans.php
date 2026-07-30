<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        id,
        technology,
        level,
        hours,
        weeks,
        study_plan,
        created_at
     FROM study_plans
     WHERE user_id = ?
     ORDER BY created_at DESC"

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

<title>My Study Plans | CareerPilot AI</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
rel="stylesheet">

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

    max-width: 1150px;

    margin: auto;

}

/* ============================
   HEADER
============================ */

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

    font-size: 32px;

    font-weight: 700;

}

.page-header p {

    margin: 12px 0 0;

    opacity: .9;

    font-size: 16px;

}

/* ============================
   CONTENT
============================ */

.study-content {

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

/* ============================
   TOP ACTION
============================ */

.top-actions {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    flex-wrap: wrap;

    margin-bottom: 30px;

}

.top-actions h4 {

    margin: 0;

    color: #172554;

    font-weight: 700;

}

.btn-theme {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    border: none;

    color: white;

    padding: 12px 22px;

    border-radius: 30px;

    font-weight: 600;

    text-decoration: none;

    display: inline-block;

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

/* ============================
   EMPTY STATE
============================ */

.empty-state {

    text-align: center;

    padding: 70px 20px;

    background: #F8FAFC;

    border-radius: 20px;

}

.empty-state i {

    font-size: 65px;

    color: #2563EB;

    margin-bottom: 20px;

}

.empty-state h3 {

    color: #172554;

    font-weight: 700;

}

.empty-state p {

    color: #64748B;

    margin: 15px auto 25px;

    max-width: 500px;

}

/* ============================
   STUDY PLAN CARD
============================ */

.plan-card {

    background: #FFFFFF;

    border: 1px solid #E5E7EB;

    border-left: 6px solid #2563EB;

    border-radius: 18px;

    padding: 25px;

    margin-bottom: 22px;

    box-shadow: 0 8px 25px rgba(

        0,

        0,

        0,

        .06

    );

    transition: .3s;

}

.plan-card:hover {

    transform: translateY(-4px);

    box-shadow: 0 15px 35px rgba(

        37,

        99,

        235,

        .12

    );

}

.plan-header {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 15px;

    flex-wrap: wrap;

    margin-bottom: 20px;

}

.plan-title {

    color: #172554;

    font-size: 22px;

    font-weight: 700;

    margin: 0;

}

.plan-date {

    color: #64748B;

    font-size: 14px;

}

.plan-meta {

    display: flex;

    gap: 12px;

    flex-wrap: wrap;

    margin-bottom: 22px;

}

.meta-item {

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-radius: 10px;

    padding: 10px 14px;

    color: #475569;

    font-size: 14px;

}

.meta-item i {

    color: #2563EB;

    margin-right: 6px;

}

.plan-preview {

    background: #F8FAFC;

    border-left: 4px solid #7C3AED;

    border-radius: 10px;

    padding: 18px;

    margin-bottom: 20px;

    color: #475569;

    line-height: 1.7;

    max-height: 150px;

    overflow: hidden;

    position: relative;

}

.plan-preview::after {

    content: "";

    position: absolute;

    bottom: 0;

    left: 0;

    right: 0;

    height: 45px;

    background: linear-gradient(

        transparent,

        #F8FAFC

    );

}

.plan-actions {

    display: flex;

    gap: 12px;

    flex-wrap: wrap;

}

.btn-view {

    background: #EFF6FF;

    color: #2563EB;

    border: 1px solid #BFDBFE;

    padding: 10px 20px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-view:hover {

    background: #DBEAFE;

    color: #1D4ED8;

}

.btn-new {

    background: linear-gradient(

        135deg,

        #2563EB,

        #7C3AED

    );

    color: white;

    padding: 10px 20px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-new:hover {

    color: white;

    transform: translateY(-2px);

}

/* ============================
   RESPONSIVE
============================ */

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .study-content {

        padding: 25px;

    }

    .page-header h1 {

        font-size: 26px;

    }

    .plan-header {

        flex-direction: column;

    }

}

</style>

</head>

<body>

<div class="study-container">

    <div class="page-header">

        <h1>

            <i class="fa-solid fa-book-open-reader"></i>

            My Study Plans

        </h1>

        <p>

            View and revisit all your personalized AI-generated learning plans.

        </p>

    </div>

    <div class="study-content">

        <div class="top-actions">

            <h4>

                <i class="fa-solid fa-clock-rotate-left text-primary"></i>

                Saved Study Plans

            </h4>

            <a

                href="studyplan.php"

                class="btn-theme"

            >

                <i class="fa-solid fa-plus"></i>

                Create New Study Plan

            </a>

        </div>

        <?php if (mysqli_num_rows($result) === 0): ?>

            <div class="empty-state">

                <i class="fa-solid fa-book-open"></i>

                <h3>No Study Plans Yet</h3>

                <p>

                    You have not created a study plan yet.

                    Create your first personalized AI study plan and start your learning journey.

                </p>

                <a

                    href="studyplan.php"

                    class="btn-theme"

                >

                    <i class="fa-solid fa-wand-magic-sparkles"></i>

                    Create My First Study Plan

                </a>

            </div>

        <?php else: ?>

            <?php while ($plan = mysqli_fetch_assoc($result)): ?>

                <div class="plan-card">

                    <div class="plan-header">

                        <h3 class="plan-title">

                            <i class="fa-solid fa-code text-primary"></i>

                            <?= htmlspecialchars($plan['technology']) ?>

                        </h3>

                        <div class="plan-date">

                            <i class="fa-regular fa-calendar"></i>

                            <?= date(

                                "d M Y, h:i A",

                                strtotime($plan['created_at'])

                            ) ?>

                        </div>

                    </div>

                    <div class="plan-meta">

                        <div class="meta-item">

                            <i class="fa-solid fa-signal"></i>

                            <?= htmlspecialchars($plan['level']) ?>

                        </div>

                        <div class="meta-item">

                            <i class="fa-solid fa-clock"></i>

                            <?= (int)$plan['hours'] ?> hours/day

                        </div>

                        <div class="meta-item">

                            <i class="fa-solid fa-calendar-days"></i>

                            <?= (int)$plan['weeks'] ?> weeks

                        </div>

                    </div>

                    <div class="plan-preview">

                        <?= nl2br(

                            htmlspecialchars(

                                $plan['study_plan']

                            )

                        ) ?>

                    </div>

                    <div class="plan-actions">

                        <a

                            href="view_studyplan.php?id=<?= (int)$plan['id'] ?>"

                            class="btn-view"

                        >

                            <i class="fa-solid fa-eye"></i>

                            View Full Plan

                        </a>

                        <a

                            href="studyplan.php"

                            class="btn-new"

                        >

                            <i class="fa-solid fa-plus"></i>

                            New Plan

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php endif; ?>

        <div class="text-center mt-4">

            <a

                href="index.php"

                class="btn btn-secondary"

            >

                <i class="fa-solid fa-house"></i>

                Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>