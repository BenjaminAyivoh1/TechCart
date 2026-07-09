<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Validate User ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Get User Details
|--------------------------------------------------------------------------
*/

$userQuery = mysqli_query(
    $conn,
    "SELECT *
    FROM users
    WHERE id = $user_id"
);

if (mysqli_num_rows($userQuery) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($userQuery);

/*
|--------------------------------------------------------------------------
| User Statistics
|--------------------------------------------------------------------------
*/

$totalOrders = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
        FROM orders
        WHERE user_id = $user_id"
    )
)['total'];

$totalSpent = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(total) AS amount
        FROM orders
        WHERE user_id = $user_id"
    )
)['amount'];

if (!$totalSpent) {
    $totalSpent = 0;
}

$latestOrder = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT created_at
        FROM orders
        WHERE user_id = $user_id
        ORDER BY created_at DESC
        LIMIT 1"
    )
);

/*
|--------------------------------------------------------------------------
| Customer Badge
|--------------------------------------------------------------------------
*/

$customerType = "New";
$badge = "secondary";

if ($totalOrders >= 5) {

    $customerType = "VIP";
    $badge = "success";

} elseif ($totalOrders >= 1) {

    $customerType = "Customer";
    $badge = "info text-dark";

}

/*
|--------------------------------------------------------------------------
| Order History
|--------------------------------------------------------------------------
*/

$orderHistory = mysqli_query(
    $conn,
    "
    SELECT *
    FROM orders
    WHERE user_id = $user_id
    ORDER BY created_at DESC
    "
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:#f5f7fb;
    font-family:Poppins,sans-serif;
}

.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:260px;
    height:100vh;
    background:#111827;
    color:white;
    padding:30px;
}

.sidebar h2{
    margin-bottom:40px;
}

.sidebar a{
    display:block;
    color:#d1d5db;
    text-decoration:none;
    padding:12px 0;
    font-size:18px;
}

.sidebar a:hover{
    color:white;
}

.main{
    margin-left:260px;
    padding:40px;
}

.card{
    border:none;
    border-radius:18px;
}

.profile-icon{
    width:90px;
    height:90px;
    border-radius:50%;
    background:#0d6efd;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:40px;
    margin:auto;
}

</style>

</head>

<body>

<div class="sidebar">

<h2>

<i class="bi bi-bag-fill"></i>

TechCart

</h2>

<a href="index.php">

<i class="bi bi-speedometer2"></i>

Dashboard

</a>

<a href="products.php">

<i class="bi bi-box-seam"></i>

Products

</a>

<a href="orders.php">

<i class="bi bi-receipt"></i>

Orders

</a>

<a href="users.php">

<i class="bi bi-people"></i>

Users

</a>

<a href="logout.php">

<i class="bi bi-box-arrow-right"></i>

Logout

</a>

</div>

<div class="main">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

Customer Profile

</h2>

<p class="text-muted">

View customer information and order history.

</p>

</div>

<a href="users.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<div class="row mb-4">

<div class="col-lg-4">

<div class="card shadow-sm">

<div class="card-body text-center">

<div class="profile-icon">

<i class="bi bi-person-fill"></i>

</div>

<h3 class="mt-3">

<?php echo htmlspecialchars($user['full_name']); ?>

</h3>

<span class="badge bg-<?php echo $badge; ?>">

<?php echo $customerType; ?>

</span>

<hr>

<p>

<i class="bi bi-envelope-fill text-primary"></i>

<?php echo htmlspecialchars($user['email']); ?>

</p>

<p>

<i class="bi bi-telephone-fill text-success"></i>

<?php echo htmlspecialchars($user['phone']); ?>

</p>

<p>

<i class="bi bi-geo-alt-fill text-danger"></i>

<?php echo htmlspecialchars($user['address']); ?>

</p>

<p>

<i class="bi bi-calendar-event"></i>

Joined

<strong>

<?php echo date("F d, Y",strtotime($user['created_at'])); ?>

</strong>

</p>

</div>

</div>

</div>

<div class="col-lg-8">

<div class="row g-4">

<div class="col-md-6">

<div class="card bg-primary text-white shadow-sm">

<div class="card-body">

<i class="bi bi-bag-check fs-1"></i>

<h6 class="mt-3">

Total Orders

</h6>

<h2>

<?php echo $totalOrders; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card bg-success text-white shadow-sm">

<div class="card-body">

<i class="bi bi-currency-dollar fs-1"></i>

<h6 class="mt-3">

Total Spent

</h6>

<h2>

$<?php echo number_format($totalSpent,2); ?>

</h2>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card bg-warning text-dark shadow-sm">

<div class="card-body">

<i class="bi bi-star-fill fs-1"></i>

<h6 class="mt-3">

Customer Type

</h6>

<h2>

<?php echo $customerType; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card bg-dark text-white shadow-sm">

<div class="card-body">

<i class="bi bi-clock-history fs-1"></i>

<h6 class="mt-3">

Latest Order

</h6>

<h5>

<?php

if($latestOrder){

echo date("M d, Y",strtotime($latestOrder['created_at']));

}else{

echo "No Orders";

}

?>

</h5>

</div>

</div>

</div>

</div>

</div>

</div>

<div class="card shadow-sm">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

Order History

</h4>

</div>

<div class="card-body">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Order ID</th>

<th>Total</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($orderHistory) > 0){ ?>

<?php while($order = mysqli_fetch_assoc($orderHistory)){ ?>

<tr>

    <td>

        #<?php echo $order['id']; ?>

    </td>

    <td>

        <strong>

            $<?php echo number_format($order['total'],2); ?>

        </strong>

    </td>

    <td>

        <?php

        switch($order['order_status']){

            case "Pending":
                echo "<span class='badge bg-warning text-dark'>Pending</span>";
                break;

            case "Processing":
                echo "<span class='badge bg-info text-dark'>Processing</span>";
                break;

            case "Shipped":
                echo "<span class='badge bg-primary'>Shipped</span>";
                break;

            case "Delivered":
                echo "<span class='badge bg-success'>Delivered</span>";
                break;

            case "Cancelled":
                echo "<span class='badge bg-danger'>Cancelled</span>";
                break;

            default:
                echo "<span class='badge bg-secondary'>".$order['order_status']."</span>";
        }

        ?>

    </td>

    <td>

        <?php echo date("M d, Y", strtotime($order['created_at'])); ?>

    </td>

    <td>

        <a
            href="view_order.php?id=<?php echo $order['id']; ?>"
            class="btn btn-outline-primary btn-sm">

            <i class="bi bi-eye"></i>

            View

        </a>

    </td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="5" class="text-center py-5">

<i class="bi bi-bag-x display-4 text-muted"></i>

<h5 class="mt-3">

No orders found

</h5>

<p class="text-muted">

This customer hasn't placed any orders yet.

</p>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>