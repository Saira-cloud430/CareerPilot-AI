<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>AI Interview Coach</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>🎤 AI Interview Coach</h3>

</div>

<div class="card-body">

<form action="interview_generate.php" method="POST">

<div class="mb-3">

<label>Job Role</label>

<input
type="text"
name="job"
class="form-control"
placeholder="PHP Backend Developer"
required>

</div>

<div class="mb-3">

<label>Experience Level</label>

<select
name="experience"
class="form-control">

<option>Beginner</option>

<option>Intermediate</option>

<option>Advanced</option>

</select>

</div>

<div class="mb-3">

<label>Number of Questions</label>

<select
name="questions"
class="form-control">

<option>5</option>
<option>10</option>
<option>15</option>

</select>

</div>

<button class="btn btn-primary">

Generate Interview

</button>

</form>

</div>

</div>

</div>

</body>

</html>