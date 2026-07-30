<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    users.full_name,
    users.email,
    user_profiles.university,
    user_profiles.degree,
    user_profiles.semester,
    user_profiles.cgpa,
    user_profiles.skills,
    user_profiles.career_goal,
    user_profiles.bio,
    user_profiles.profile_picture
FROM users
LEFT JOIN user_profiles
ON users.id = user_profiles.user_id
WHERE users.id = '$user_id'
";

$query = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>My Profile | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

*{
    box-sizing:border-box;
}

body{

    background:#F5F7FF;

    font-family:'Segoe UI',sans-serif;

    padding:40px 20px;

    color:#1F2937;

}

.profile-container{

    max-width:1000px;

    margin:auto;

}

.profile-header{

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    padding:35px;

    border-radius:25px 25px 0 0;

    box-shadow:0 15px 40px rgba(37,99,235,.20);

}

.profile-header h1{

    font-weight:700;

    margin:0;

}

.profile-header p{

    margin-top:8px;

    opacity:.9;

}

.profile-content{

    background:white;

    padding:40px;

    border-radius:0 0 25px 25px;

    box-shadow:0 20px 50px rgba(0,0,0,.08);

}

.profile-picture{

    width:120px;

    height:120px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:45px;

    margin:auto;

}

.form-label{

    font-weight:600;

    color:#374151;

}

.form-control{

    border-radius:10px;

    padding:12px;

}

.form-control:focus{

    border-color:#2563EB;

    box-shadow:0 0 0 3px rgba(37,99,235,.12);

}

.btn-theme{

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    border:none;

    color:white;

    padding:12px 28px;

    border-radius:30px;

    font-weight:600;

}

.btn-theme:hover{

    color:white;

    transform:translateY(-2px);

}

</style>

</head>

<body>

<div class="profile-container">

<div class="profile-header">

<h1>

<i class="fa-solid fa-user"></i>

My Career Profile

</h1>

<p>

Manage your personal, academic, and career information.

</p>

</div>

<div class="profile-content">

<?php if(isset($_GET['success'])){ ?>

<div class="alert alert-success">

<i class="fa-solid fa-circle-check"></i>

Profile updated successfully.

</div>

<?php } ?>

<form action="profile_update.php" method="POST">

<div class="text-center mb-4">

<div class="profile-picture">

<i class="fa-solid fa-user"></i>

</div>

<h4 class="mt-3">

<?= htmlspecialchars($user['full_name'] ?? '') ?>

</h4>

<p class="text-muted">

<?= htmlspecialchars($user['email'] ?? '') ?>

</p>

</div>

<hr class="mb-4">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

<i class="fa-solid fa-user text-primary"></i>

Full Name

</label>

<input
type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

<i class="fa-solid fa-envelope text-primary"></i>

Email

</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user['email'] ?? '') ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

<i class="fa-solid fa-building-columns text-primary"></i>

University

</label>

<input
type="text"
name="university"
class="form-control"
value="<?= htmlspecialchars($user['university'] ?? '') ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

<i class="fa-solid fa-graduation-cap text-primary"></i>

Degree

</label>

<input
type="text"
name="degree"
class="form-control"
value="<?= htmlspecialchars($user['degree'] ?? '') ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

<i class="fa-solid fa-layer-group text-primary"></i>

Semester

label>

<input
type="text"
name="semester"
class="form-control"
value="<?= htmlspecialchars($user['semester'] ?? '') ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

<i class="fa-solid fa-chart-line text-primary"></i>

CGPA

</label>

<input
type="number"
step="0.01"
name="cgpa"
class="form-control"
value="<?= htmlspecialchars($user['cgpa'] ?? '') ?>">

</div>

<div class="col-12 mb-3">

<label class="form-label">

<i class="fa-solid fa-code text-primary"></i>

Skills

</label>

<textarea
name="skills"
class="form-control"
rows="3"
placeholder="Example: HTML, CSS, JavaScript, PHP, MySQL"><?= htmlspecialchars($user['skills'] ?? '') ?></textarea>

</div>

<div class="col-12 mb-3">

<label class="form-label">

<i class="fa-solid fa-bullseye text-primary"></i>

Career Goal

</label>

<input
type="text"
name="career_goal"
class="form-control"
value="<?= htmlspecialchars($user['career_goal'] ?? '') ?>"
placeholder="Example: Become a Full Stack Developer">

</div>

<div class="col-12 mb-4">

<label class="form-label">

<i class="fa-solid fa-align-left text-primary"></i>

About Me

</label>

<textarea
name="bio"
class="form-control"
rows="5"
placeholder="Write a short introduction about yourself"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

</div>

</div>

<button class="btn btn-theme">

<i class="fa-solid fa-save"></i>

Save Profile

</button>

<a href="index.php" class="btn btn-secondary ms-2">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</form>

</div>

</div>

</body>

</html>