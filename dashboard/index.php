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
background:#f5f7fb;
}

.card{
transition:.3s;
cursor:pointer;
}

.card:hover{
transform:translateY(-8px);
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

<h2>

Welcome,

<?php echo htmlspecialchars($_SESSION['user_name']); ?>

👋

</h2>

<p>

Choose a feature below.

</p>

<div class="row g-4 mt-3">

<div class="col-md-4">

<div class="card p-4 text-center">

<i class="fa-solid fa-file-lines fa-3x mb-3"></i>

<h4>Resume Analysis</h4>

<a href="resume.php" class="btn btn-primary mt-3">

Open

</a>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<i class="fa-solid fa-road fa-3x mb-3"></i>

<h4>Career Roadmap</h4>

<a href="roadmap.php" class="btn btn-primary mt-3">

Open

</a>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<i class="fa-solid fa-user-tie fa-3x mb-3"></i>

<h4>Interview Coach</h4>

<a href="interview.php" class="btn btn-primary mt-3">

Open

</a>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<i class="fa-solid fa-book fa-3x mb-3"></i>

<h4>Study Planner</h4>

<a href="studyplan.php" class="btn btn-primary mt-3">

Open

</a>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<i class="fa-solid fa-robot fa-3x mb-3"></i>

<h4>AI Chat</h4>

<a href="ai_chat.php" class="btn btn-primary mt-3">

Open

</a>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<i class="fa-solid fa-user fa-3x mb-3"></i>

<h4>Profile</h4>

<a href="profile.php" class="btn btn-primary mt-3">

Open

</a>

</div>

</div>

</div>

</div>

</body>

</html>