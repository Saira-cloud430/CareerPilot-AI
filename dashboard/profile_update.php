<?php

require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$full_name = mysqli_real_escape_string(
    $conn,
    trim($_POST['full_name'])
);

$email = mysqli_real_escape_string(
    $conn,
    trim($_POST['email'])
);

$university = mysqli_real_escape_string(
    $conn,
    trim($_POST['university'])
);

$degree = mysqli_real_escape_string(
    $conn,
    trim($_POST['degree'])
);

$semester = mysqli_real_escape_string(
    $conn,
    trim($_POST['semester'])
);

$cgpa = trim($_POST['cgpa']);

$skills = mysqli_real_escape_string(
    $conn,
    trim($_POST['skills'])
);

$career_goal = mysqli_real_escape_string(
    $conn,
    trim($_POST['career_goal'])
);

$bio = mysqli_real_escape_string(
    $conn,
    trim($_POST['bio'])
);


/* Update basic user information */

$updateUser = "

UPDATE users

SET
full_name = '$full_name',
email = '$email'

WHERE id = '$user_id'

";

mysqli_query($conn, $updateUser);


/* Check if profile already exists */

$checkProfile = mysqli_query(
    $conn,
    "SELECT id FROM user_profiles WHERE user_id='$user_id'"
);

if(mysqli_num_rows($checkProfile) > 0)
{

    $sql = "

    UPDATE user_profiles

    SET

    university = '$university',

    degree = '$degree',

    semester = '$semester',

    cgpa = '$cgpa',

    skills = '$skills',

    career_goal = '$career_goal',

    bio = '$bio'

    WHERE user_id = '$user_id'

    ";

}
else
{

    $sql = "

    INSERT INTO user_profiles

    (
        user_id,
        university,
        degree,
        semester,
        cgpa,
        skills,
        career_goal,
        bio
    )

    VALUES

    (
        '$user_id',
        '$university',
        '$degree',
        '$semester',
        '$cgpa',
        '$skills',
        '$career_goal',
        '$bio'
    )

    ";

}


if(!mysqli_query($conn,$sql))
{
    die("Database Error: " . mysqli_error($conn));
}

header("Location: profile.php?success=1");

exit();

?>