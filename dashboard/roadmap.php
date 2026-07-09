<?php
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Career Roadmap | CareerPilot AI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow">

                <div class="card-body">

                    <h2 class="mb-4 text-center">
                        AI Career Roadmap Generator
                    </h2>

                    <form action="roadmap_generate.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Desired Career
                            </label>

                            <input
                                type="text"
                                name="career"
                                class="form-control"
                                placeholder="e.g. Software Engineer"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Current Education
                            </label>

                            <input
                                type="text"
                                name="education"
                                class="form-control"
                                placeholder="e.g. BS Software Engineering"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Current Skill Level
                            </label>

                            <select
                                name="level"
                                class="form-select">

                                <option>Beginner</option>
                                <option>Intermediate</option>
                                <option>Advanced</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Target Duration
                            </label>

                            <select
                                name="duration"
                                class="form-select">

                                <option>6 Months</option>
                                <option selected>12 Months</option>
                                <option>24 Months</option>

                            </select>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Generate AI Roadmap

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>