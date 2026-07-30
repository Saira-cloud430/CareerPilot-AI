<?php

require_once "../config.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,

"SELECT plan, status, end_date
 FROM subscriptions
 WHERE user_id='$user_id'
 AND status='active'
 AND end_date >= CURDATE()
 ORDER BY id DESC
 LIMIT 1"

);

$subscription = mysqli_fetch_assoc($query);

$currentPlan = $subscription ? $subscription['plan'] : 'free';

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Subscription | CareerPilot AI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

*{
    box-sizing:border-box;
}

body{

    margin:0;

    min-height:100vh;

    background:#F5F7FF;

    font-family:'Segoe UI',sans-serif;

    padding:40px 20px;

    color:#1F2937;

}

.subscription-container{

    max-width:1100px;

    margin:auto;

}

.page-header{

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    padding:40px;

    border-radius:25px;

    text-align:center;

    margin-bottom:40px;

    box-shadow:0 15px 40px rgba(37,99,235,.20);

}

.page-header h1{

    font-weight:700;

    margin-bottom:10px;

}

.page-header p{

    margin:0;

    opacity:.9;

}

.pricing-container{

    display:flex;

    justify-content:center;

    gap:30px;

    flex-wrap:wrap;

}

.plan-card{

    background:white;

    width:340px;

    border-radius:25px;

    padding:35px;

    box-shadow:0 15px 40px rgba(0,0,0,.08);

    border:1px solid #E5E7EB;

    transition:.3s;

}

.plan-card:hover{

    transform:translateY(-8px);

    box-shadow:0 20px 45px rgba(37,99,235,.15);

}

.premium-card{

    border:2px solid #7C3AED;

    position:relative;

}

.badge-premium{

    position:absolute;

    top:-15px;

    right:25px;

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    padding:7px 18px;

    border-radius:20px;

    font-size:13px;

    font-weight:600;

}

.plan-icon{

    width:65px;

    height:65px;

    border-radius:18px;

    background:#EFF6FF;

    color:#2563EB;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:28px;

    margin-bottom:20px;

}

.premium-card .plan-icon{

    background:#F5F3FF;

    color:#7C3AED;

}

.plan-card h2{

    font-weight:700;

}

.price{

    font-size:42px;

    font-weight:800;

    color:#2563EB;

    margin:20px 0;

}

.premium-card .price{

    color:#7C3AED;

}

.price span{

    font-size:16px;

    color:#6B7280;

    font-weight:400;

}

.features{

    list-style:none;

    padding:0;

    margin:25px 0;

}

.features li{

    margin-bottom:14px;

    color:#374151;

}

.features i{

    color:#16A34A;

    margin-right:8px;

}

.btn-theme{

    width:100%;

    padding:13px;

    border:none;

    border-radius:30px;

    background:linear-gradient(135deg,#2563EB,#7C3AED);

    color:white;

    font-weight:600;

}

.btn-theme:hover{

    color:white;

    transform:translateY(-2px);

}

.current-plan{

    width:100%;

    padding:13px;

    border-radius:30px;

    background:#E5E7EB;

    color:#374151;

    text-align:center;

    font-weight:600;

}

</style>

</head>

<body>

<div class="subscription-container">

<div class="page-header">

<h1>

<i class="fa-solid fa-crown"></i>

Choose Your CareerPilot AI Plan

</h1>

<p>

Unlock powerful AI tools to accelerate your career journey.

</p>

</div>

<div class="pricing-container">

<!-- FREE PLAN -->

<div class="plan-card">

<div class="plan-icon">

<i class="fa-solid fa-user"></i>

</div>

<h2>Free</h2>

<div class="price">

$0

<span>/ forever</span>

</div>

<ul class="features">

<li>

<i class="fa-solid fa-check"></i>

Basic AI career guidance

</li>

<li>

<i class="fa-solid fa-check"></i>

Limited resume analysis

</li>

<li>

<i class="fa-solid fa-check"></i>

Basic interview preparation

</li>

<li>

<i class="fa-solid fa-check"></i>

Limited AI chat

</li>

</ul>

<?php if($currentPlan == 'free'){ ?>

<div class="current-plan">

Current Plan

</div>

<?php } ?>

</div>


<!-- PREMIUM PLAN -->

<div class="plan-card premium-card">

<div class="badge-premium">

MOST POPULAR

</div>

<div class="plan-icon">

<i class="fa-solid fa-crown"></i>

</div>

<h2>Premium</h2>

<div class="price">

$5

<span>/ month</span>

</div>

<ul class="features">

<li>

<i class="fa-solid fa-check"></i>

Advanced Resume Analyzer

</li>

<li>

<i class="fa-solid fa-check"></i>

Unlimited Interview Preparation

</li>

<li>

<i class="fa-solid fa-check"></i>

Personalized Career Roadmaps

</li>

<li>

<i class="fa-solid fa-check"></i>

Advanced Study Planner

</li>

<li>

<i class="fa-solid fa-check"></i>

More AI Career Chat Usage

</li>

</ul>

<?php if($currentPlan == 'free'){ ?>

<a href="payment.php" class="btn btn-theme">

    <i class="fa-solid fa-lock"></i>

    Upgrade to Premium

</a>

<?php } else { ?>

<a href="subscription_update.php?plan=free"
   class="btn btn-secondary w-100 rounded-pill">

    Switch to Free Plan

</a>

<?php } ?>

</div>

</div>

</div>

</body>

</html>