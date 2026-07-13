<?php
require_once "../config.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Dashboard | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

body{
    background:#eef3fb;
    font-family:'Segoe UI',sans-serif;
}

.navbar{
    background:#0f172a !important;
}

.navbar-brand{
    font-size:24px;
    font-weight:bold;
}

h2{
    font-weight:700;
    color:#1f2937;
}

p{
    color:#6b7280;
}

.card{

    border:none;

    border-radius:20px;

    transition:.35s;

    box-shadow:0 10px 25px rgba(0,0,0,.08);

    cursor:pointer;

    overflow:hidden;

}

.card:hover{

transform:translateY(-10px);

box-shadow:0 20px 45px rgba(0,0,0,.18);

}

.card i{
    font-size:55px;
    margin-bottom:20px;
}

.card h4{

font-weight:700;

}

.btn-primary{

background:#2563eb;

border:none;

border-radius:12px;

padding:10px 25px;

}

.btn-primary:hover{

background:#1d4ed8;

}

.btn-danger{

border-radius:10px;

}

.btn-secondary{

border-radius:10px;

}

</style>

</head>

<body>

<nav class="navbar navbar-dark bg-dark">

<div class="container">

<span class="navbar-brand">
CareerPilot AI
</span>

<a href="../logout.php" class="btn btn-danger">
Logout
</a>

</div>

</nav>

<div class="container mt-5">

<h2 class="mb-2">

Welcome back,

<?= htmlspecialchars($_SESSION['user_name']); ?>

👋

</h2>

<p class="mb-5">

Your AI Career Assistant is ready to help you build your future.

</p>

<p class="text-muted">

Choose an AI-powered tool to accelerate your career journey.

</p>

<div class="row g-4 mt-3">

<div class="col-md-4">

<div class="card h-100 p-4 text-center">

<i class="fa-solid fa-file-lines fa-3x mb-3 text-primary"></i>

<h4>Resume Analysis</h4>

<a href="resume.php" class="btn btn-primary mt-3">

Launch

</a>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 p-4 text-center">

<i class="fa-solid fa-road fa-3x mb-3 text-success"></i>

<h4>Career Roadmap</h4>

<a href="roadmap.php" class="btn btn-primary mt-3">

Launch

</a>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 p-4 text-center">

<i class="fa-solid fa-user-tie fa-3x mb-3 text-danger"></i>

<h4>Interview Coach</h4>

<a href="interview.php" class="btn btn-primary mt-3">

Launch

</a>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 p-4 text-center">

<i class="fa-solid fa-book fa-3x mb-3 text-warning"></i>

<h4>Study Planner</h4>

<a href="studyplan.php" class="btn btn-primary mt-3">

Launch

</a>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 p-4 text-center">

<i class="fa-solid fa-robot fa-3x mb-3 text-info"></i>

<h4>AI Chat</h4>

<a href="ai_chat.php" class="btn btn-primary mt-3">
    Launch

</a>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 p-4 text-center">

<i class="fa-solid fa-user fa-3x mb-3 text-secondary"></i>

<h4>Profile</h4>

<a href="profile.php" class="btn btn-primary mt-3">

Launch

</a>

</div>

</div>

<div class="col-md-4">

    <div class="card h-100 p-4 text-center">

        <i class="fa-solid fa-crown fa-3x mb-3 text-warning"></i>

        <h4>Subscription</h4>

        <a href="subscription.php" class="btn btn-primary mt-3">

            Launch

        </a>

    </div>

</div>

<div class="col-md-4">

    <div class="card h-100 p-4 text-center">

        <i class="fa-solid fa-cog fa-3x mb-3 text-dark"></i>

        <h4>Settings</h4>

        <a href="settings.php" class="btn btn-primary mt-3">

            Launch

        </a>

    </div>

</div>

</div>

</div>

<footer class="text-center mt-5 mb-4 text-muted">

<hr>

CareerPilot AI © 2026

<br>

Built with ❤️ using PHP, MySQL & Google Gemini AI

</footer>

</body>

</html>