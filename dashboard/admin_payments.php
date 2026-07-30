<?php

require_once "../config.php";

$query = mysqli_query($conn,

"SELECT
payments.id,
payments.user_id,
users.full_name,
users.email,
payments.amount,
payments.payment_method,
payments.transaction_reference,
payments.payment_status

FROM payments

JOIN users

ON users.id=payments.user_id

ORDER BY payments.id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Admin Payments</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

body{

background:#F5F7FF;
font-family:'Segoe UI',sans-serif;

}

.container-box{

max-width:1200px;
margin:40px auto;

}

.card-box{

background:white;
padding:30px;
border-radius:25px;
box-shadow:0 20px 45px rgba(0,0,0,.08);

}

.header{

background:linear-gradient(135deg,#2563EB,#7C3AED);
color:white;
padding:25px;
border-radius:20px;
margin-bottom:25px;

}
thead{

background:#EEF2FF;

}

th{

font-weight:700;

}

.table{

vertical-align:middle;

}

.verify-btn{

background:linear-gradient(135deg,#2563EB,#7C3AED);
padding:10px 20px;
border-radius:30px;
font-weight:600;
text-decoration:none;
color:white;

}

.verify-btn:hover{

color:white;

}

.badge-pending{

background:#FFC107;
color:#000;
padding:8px 15px;
border-radius:30px;
font-size:13px;

}

.badge-success{

background:#16A34A;
padding:8px 15px;
border-radius:30px;
font-size:13px;

}

</style>

</head>

<body>

<div class="container-box">

<div class="card-box">

<div class="header">

<h2>

<i class="fa-solid fa-money-check-dollar"></i>

Payment Management Dashboard

</h2>
<p class="mb-0 mt-2">
Review customer payments and activate Premium subscriptions.
</p>

</div>

<table class="table table-bordered">

<thead>

<tr>

<th>User</th>

<th>Email</th>

<th>Amount</th>

<th>Method</th>

<th>Reference</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<tr>

<td><?php echo $row['full_name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td>$<?php echo $row['amount']; ?></td>

<td><?php echo ucfirst($row['payment_method']); ?></td>

<td><?php echo $row['transaction_reference']; ?></td>

<td>

<?php

if($row['payment_status']=="pending")
{

echo "<span class='badge badge-pending'>Pending</span>";

}

else

{

echo "<span class='badge badge-success'>Completed</span>";

}

?>

</td>

<td>

<?php

if($row['payment_status']=="pending")
{

?>

<a

href="verify_payment.php?id=<?php echo $row['id']; ?>"

class="verify-btn">

Verify

</a>

<?php

}

else
{
    echo "<span class='badge bg-success'>
            <i class='fa-solid fa-check'></i>
            Verified
          </span>";
}
?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>

</html>