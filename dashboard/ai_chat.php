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

<title>AI Career Chat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h3>AI Career Assistant</h3>

</div>

<div class="card-body">

<form action="ai_chat_generate.php" method="POST">

<div class="mb-3">

<label class="form-label">
Ask Anything
</label>

<textarea
name="question"
class="form-control"
rows="6"
placeholder="Example: How can I become a Full Stack Developer?"
required></textarea>

</div>

<button class="btn btn-dark">

Ask AI

</button>

</form>

</div>

</div>

</div>

</body>

</html>