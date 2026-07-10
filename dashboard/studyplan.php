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

<title>AI Study Planner</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3>AI Study Planner</h3>

</div>

<div class="card-body">

<form action="studyplan_generate.php" method="POST">

<div class="mb-3">

<label class="form-label">Technology</label>

<input
type="text"
name="technology"
class="form-control"
placeholder="Example: MERN Stack, Flutter, AI, Python"
required>

</div>

<div class="mb-3">

<label class="form-label">Current Level</label>

<select
name="level"
class="form-select">

<option value="Beginner">Beginner</option>
<option value="Intermediate">Intermediate</option>
<option value="Advanced">Advanced</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">Hours Per Day</label>

<input
type="number"
name="hours"
class="form-control"
min="1"
max="24"
required>

</div>

<div class="mb-3">

<label class="form-label">Duration (Weeks)</label>

<input
type="number"
name="weeks"
class="form-control"
min="1"
required>

</div>

<button class="btn btn-success">

Generate AI Study Plan

</button>

</form>

</div>

</div>

</div>

</body>

</html>