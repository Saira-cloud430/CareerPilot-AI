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

    <title>Resume Analyzer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">
        AI Resume Analyzer
    </h2>

    <form
        action="resume_analyze.php"
        method="POST"
        enctype="multipart/form-data">

        <div class="mb-3">

            <label class="form-label">

                Upload Resume (PDF)

            </label>

            <input
                type="file"
                name="resume"
                accept=".pdf"
                class="form-control"
                required>

        </div>

        <button
            class="btn btn-primary">

            Analyze Resume

        </button>

    </form>

</div>

</body>

</html>