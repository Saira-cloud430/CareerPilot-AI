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

<title>AI Interview Preparation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center mb-4">

AI Interview Preparation

</h2>

<form action="interview_generate.php" method="POST">

<div class="mb-3">

<label class="form-label">

Job Role

</label>

<input
type="text"
name="job"
class="form-control"
placeholder="Software Engineer"
required>

</div>

<div class="mb-3">

<label class="form-label">

Experience Level

</label>

<select
name="experience"
class="form-select">

<option>Fresher</option>
<option>1-2 Years</option>
<option>3-5 Years</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Number of Questions

</label>

<select
name="questions"
class="form-select">

<option>10</option>
<option selected>15</option>
<option>20</option>

</select>

</div>

<button
class="btn btn-success w-100">

Generate Interview Questions

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>