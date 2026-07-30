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

<link rel="stylesheet" href="../assets/css/dashboard.css">

<script defer src="../assets/js/dashboard.js"></script>

<style>

.tool-actions {

    display: flex;

    gap: 8px;

    flex-wrap: wrap;

    justify-content: center;

}

.history-btn {

    display: inline-block;

    padding: 8px 14px;

    border: 1px solid #2563EB;

    border-radius: 20px;

    color: #2563EB;

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    transition: .3s;

}

.history-btn:hover {

    background: #2563EB;

    color: white;

}

</style>

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="#">

<i class="fa-solid fa-rocket"></i>

CareerPilot AI

</a>

<div class="d-flex align-items-center">

<span class="me-4 fw-semibold">

<i class="fa-solid fa-circle-user"></i>

<?= htmlspecialchars($_SESSION['user_name']); ?>

</span>

<a href="../logout.php" class="btn btn-danger logout-btn">

Logout

</a>

</div>

</div>

</nav>


<div class="container py-5">


<!-- HERO -->

<div class="hero">

<div class="row align-items-center">

<div class="col-lg-8">

<h1 id="greeting"></h1>

<p>

Your AI Career Assistant is ready.

Let's build your future today.

</p>

<div class="mt-4">

<a href="resume.php" class="btn btn-light me-3">

Analyze Resume

</a>

<a href="roadmap.php" class="btn btn-outline-light">

Career Roadmap

</a>

</div>

</div>

<div class="col-lg-4 text-center">

<i class="fa-solid fa-brain hero-icon"></i>

</div>

</div>

</div>


<!-- STATS -->

<div class="row stats">

<div class="col-md-3">

<div class="stat-card">

<h2>87%</h2>

<p>Resume Score</p>

</div>

</div>

<div class="col-md-3">

<div class="stat-card">

<h2>5</h2>

<p>Interviews</p>

</div>

</div>

<div class="col-md-3">

<div class="stat-card">

<h2>3</h2>

<p>Roadmaps</p>

</div>

</div>

<div class="col-md-3">

<div class="stat-card">

<h2>18</h2>

<p>AI Chats</p>

</div>

</div>

</div>


<h2 class="section-title">

AI Career Tools

</h2>


<div class="row g-4">


<?php

$tools=[

["Resume Analysis","resume.php","fa-file-lines","primary"],

["Career Roadmap","careerroadmap.php","fa-road","success"],

["Interview Coach","interview.php","fa-user-tie","danger"],

["Study Planner","studyplan.php","fa-book","warning"],

["AI Chat","ai_chat.php","fa-robot","info"],

["Profile","profile.php","fa-user","secondary"],

["Subscription","subscription.php","fa-crown","warning"],

["Settings","settings.php","fa-cog","dark"]

];


foreach($tools as $tool){

?>

<div class="col-lg-3 col-md-6">

<div class="tool-card">

<div class="icon">

<i class="fa-solid <?= $tool[2] ?> text-<?= $tool[3] ?>"></i>

</div>

<h4>

<?= $tool[0] ?>

</h4>

<p>

Launch AI powered <?= $tool[0] ?> instantly.

</p>


<div class="tool-actions">


<a href="<?= $tool[1] ?>" class="launch-btn">

Launch →

</a>


<?php if($tool[0] == "AI Chat"){ ?>

<a href="my_chats.php" class="history-btn">

<i class="fa-solid fa-clock-rotate-left"></i>

History

</a>

<?php } ?>


</div>


</div>

</div>

<?php } ?>


</div>

</div>


<footer>

CareerPilot AI © 2026

</footer>


</body>

</html>