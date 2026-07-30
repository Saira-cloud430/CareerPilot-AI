<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

$user_id = mysqli_real_escape_string($conn, $user_id);

$query = mysqli_query(

    $conn,

    "SELECT id, job_role, feedback, created_at
     FROM interview_sessions
     WHERE user_id='$user_id'
     ORDER BY created_at DESC"

);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Interviews | CareerPilot AI</title>

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

    min-height: 100vh;

    padding: 40px 20px;

    background: #F5F7FF;

    color: #1F2937;

    font-family: 'Segoe UI', sans-serif;

}

.container-custom {

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

    box-shadow: 0 15px 40px rgba(

        37,

        99,

        235,

        .20

    );

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

.content-area {

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

.top-actions {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    flex-wrap: wrap;

    margin-bottom: 30px;

}

.top-actions h3 {

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

}

.btn-theme:hover {

    color: white;

    transform: translateY(-2px);

}

.interview-card {

    background: #F8FAFC;

    border: 1px solid #E5E7EB;

    border-left: 5px solid #7C3AED;

    border-radius: 16px;

    padding: 22px;

    margin-bottom: 18px;

    transition: .3s;

}

.interview-card:hover {

    transform: translateY(-3px);

    box-shadow: 0 10px 25px rgba(

        37,

        99,

        235,

        .10

    );

}

.interview-card h5 {

    color: #172554;

    font-weight: 700;

    margin-bottom: 10px;

}

.interview-meta {

    color: #64748B;

    font-size: 14px;

    margin-bottom: 15px;

}

.interview-meta i {

    color: #2563EB;

    margin-right: 5px;

}

.card-actions {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;

}

.btn-view {

    background: #2563EB;

    color: white;

    padding: 9px 18px;

    border-radius: 8px;

    text-decoration: none;

    font-weight: 600;

}

.btn-view:hover {

    background: #1D4ED8;

    color: white;

}

.btn-delete {

    background: #FEE2E2;

    color: #B91C1C;

    padding: 9px 18px;

    border-radius: 8px;

    text-decoration: none;

    font-weight: 600;

}

.btn-delete:hover {

    background: #FECACA;

    color: #991B1B;

}

.empty-state {

    text-align: center;

    padding: 70px 20px;

    color: #64748B;

}

.empty-state i {

    font-size: 60px;

    color: #CBD5E1;

    margin-bottom: 20px;

}

.empty-state h4 {

    color: #334155;

    font-weight: 700;

}

@media (max-width: 768px) {

    body {

        padding: 20px 10px;

    }

    .page-header,

    .content-area {

        padding: 25px;

    }

}

</style>

</head>

<body>

<div class="container-custom">

<div class="page-header">

<h1>

<i class="fa-solid fa-microphone-lines"></i>

My Interview Preparations

</h1>

<p>

Review your previous AI-generated interview preparations and continue practicing for your career goals.

</p>

</div>

<div class="content-area">

<div class="top-actions">

<h3>

<i class="fa-solid fa-clock-rotate-left text-primary"></i>

Previous Interviews

</h3>

<a href="interview.php" class="btn-theme">

<i class="fa-solid fa-plus"></i>

Generate New Interview

</a>

</div>

<?php if (mysqli_num_rows($query) > 0): ?>

<?php while ($row = mysqli_fetch_assoc($query)): ?>

<div class="interview-card">

<h5>

<i class="fa-solid fa-briefcase text-primary"></i>

<?= htmlspecialchars($row['job_role']) ?>

</h5>

<div class="interview-meta">

<i class="fa-solid fa-calendar-days"></i>

<?= date("F d, Y - h:i A", strtotime($row['created_at'])) ?>

</div>

<div class="card-actions">

<a

href="view_interview.php?id=<?= $row['id'] ?>"

class="btn-view">

<i class="fa-solid fa-eye"></i>

View Preparation

</a>

<a

href="delete_interview.php?id=<?= $row['id'] ?>"

class="btn-delete"

onclick="return confirm('Are you sure you want to delete this interview preparation?');">

<i class="fa-solid fa-trash"></i>

Delete

</a>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="empty-state">

<i class="fa-solid fa-microphone-slash"></i>

<h4>No Interview Preparations Yet</h4>

<p>

Generate your first personalized AI interview preparation to start practicing.

</p>

<a href="interview.php" class="btn-theme">

<i class="fa-solid fa-wand-magic-sparkles"></i>

Generate Interview

</a>

</div>

<?php endif; ?>

</div>

</div>

</body>

</html>