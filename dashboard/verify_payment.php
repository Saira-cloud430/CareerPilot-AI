<?php

require_once "../config.php";

if(!isset($_GET['id']))
{

header("Location: admin_payments.php");

exit();

}

$payment_id=(int)$_GET['id'];

$get=mysqli_query($conn,

"SELECT *

FROM payments

WHERE id='$payment_id'

LIMIT 1");

$payment=mysqli_fetch_assoc($get);

if(!$payment)
{

die("Payment not found");

}

$user_id=$payment['user_id'];

mysqli_query($conn,

"UPDATE payments

SET payment_status='completed'

WHERE id='$payment_id'");

mysqli_query($conn,

"UPDATE subscriptions

SET status='expired'

WHERE user_id='$user_id'

AND status='active'");

$start=date("Y-m-d");

$end=date("Y-m-d",strtotime("+1 month"));

mysqli_query($conn,

"INSERT INTO subscriptions

(

user_id,

plan,

start_date,

end_date,

status

)

VALUES

(

'$user_id',

'premium',

'$start',

'$end',

'active'

)");

header("Location: payment_success.php");

exit();

?>