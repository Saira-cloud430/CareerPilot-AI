<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];


/* ==============================
   FETCH USER'S ROADMAPS
============================== */

$stmt = mysqli_prepare(

    $conn,

    "SELECT id, target_career, created_at
     FROM career_roadmaps
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

<title>My Career Roadmaps | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

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

.roadmap-container {

    max-width: 1100px;

    margin: auto;

}

/* HEADER */

.page-header {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 40px;

    border-radius: 25px 25px 0 0;

    box-shadow: 0 15px 40px rgba(37,99,235,.20);

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

    font-size: 32px;

    font-weight: 700;

}

.page-header p {

    margin-top: 10px;

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

/* CONTENT */

.roadmap-content {

    background: white;

    padding: 40px;

    border-radius: 0 0 25px 25px;

    box-shadow: 0 20px 50px rgba(0,0,0,.08);

}

.empty-state {

    text-align: center;

    padding: 60px 20px;

}

.empty-icon {

    width: 90px;

    height: 90px;

    margin: auto auto 25px;

    border-radius: 50%;

    background: #EFF6FF;

    color: #2563EB;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 38px;

}

.empty-state h3 {

    font-weight: 700;

    color: #1F2937;

}

.empty-state p {

    color: #64748B;

    margin: 15px auto 25px;

}

.btn-theme {

    display: inline-block;

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

/* ROADMAP CARD */

.roadmap-card {

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-left: 5px solid #2563EB;

    border-radius: 15px;

    padding: 22px;

    margin-bottom: 18px;

    transition: .3s;

}

.roadmap-card:hover {

    transform: translateY(-3px);

    box-shadow: 0 10px 25px rgba(37,99,235,.10);

}

.roadmap-card h4 {

    color: #2563EB;

    font-weight: 700;

    margin-bottom: 10px;

}

.roadmap-date {

    color: #64748B;

    font-size: 14px;

    margin-bottom: 18px;

}

.card-actions {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;

}

.btn-view {

    background: linear-gradient(135deg, #2563EB, #7C3AED);

    color: white;

    padding: 10px 20px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-view:hover {

    color: white;

}

.btn-delete {

    background: #FEE2E2;

    color: #B91C1C;

    padding: 10px 20px;

    border-radius: 25px;

    text-decoration: none;

    font-weight: 600;

}

.btn-delete:hover {

    background: #FECACA;

    color: #991B1B;

}

.bottom-actions {

    margin-top: 30px;

    padding-top: 25px;

    border-top: 1px solid #E5E7EB;

}

.btn-secondary-custom {

    background: #64748B;

    color: white;

    padding: 12px 24px;

    border-radius: 30px;

    text-decoration: none;

    font-weight: 600;

}

.btn-secondary-custom:hover {

    background: #475569;

    color: white;

}

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .roadmap-content {

        padding: 25px;

    }

    .page-header h1 {

        font-size: 26px;

    }

}

</style>

</head>

<body>

<div class="roadmap-container">

    <div class="page-header">

        <div class="brand">

            <i class="fa-solid fa-rocket"></i>

            CAREERPILOT AI

        </div>

        <h1>

            <i class="fa-solid fa-route"></i>

            My Career Roadmaps

        </h1>

        <p>

            View and manage the personalized career roadmaps you have generated.

        </p>

        <div class="header-actions">

            <a href="roadmap.php" class="history-btn">

                <i class="fa-solid fa-plus"></i>

                Generate New Roadmap

            </a>

        </div>

    </div>

    <div class="roadmap-content">

        <?php if (mysqli_num_rows($result) === 0): ?>

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="fa-solid fa-route"></i>

                </div>

                <h3>No Career Roadmaps Yet</h3>

                <p>

                    Create your first personalized AI career roadmap and start planning your career journey.

                </p>

                <a href="roadmap.php" class="btn-theme">

                    <i class="fa-solid fa-wand-magic-sparkles"></i>

                    Create My First Roadmap

                </a>

            </div>

        <?php else: ?>

            <?php while ($roadmap = mysqli_fetch_assoc($result)): ?>

                <div class="roadmap-card">

                    <h4>

                        <i class="fa-solid fa-bullseye"></i>

                        <?= htmlspecialchars($roadmap['target_career']); ?>

                    </h4>

                    <div class="roadmap-date">

                        <i class="fa-solid fa-calendar-days"></i>

                        Created on:

                        <?= date(

                            "F d, Y - h:i A",

                            strtotime($roadmap['created_at'])

                        ); ?>

                    </div>

                    <div class="card-actions">

                        <a

                            href="view_career.php?id=<?= $roadmap['id']; ?>"

                            class="btn-view"

                        >

                            <i class="fa-solid fa-eye"></i>

                            View Roadmap

                        </a>

                        <a

                            href="delete_career.php?id=<?= $roadmap['id']; ?>"

                            class="btn-delete"

                            onclick="return confirm('Are you sure you want to delete this roadmap?');"

                        >

                            <i class="fa-solid fa-trash"></i>

                            Delete

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php endif; ?>

        <div class="bottom-actions">

            <a href="index.php" class="btn-secondary-custom">

                <i class="fa-solid fa-house"></i>

                Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>