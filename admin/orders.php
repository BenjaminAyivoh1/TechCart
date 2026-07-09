<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";
// Search
$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Statistics
$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders")
)['total'];

$pendingOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Pending'")
)['total'];

$deliveredOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Delivered'")
)['total'];

$cancelledOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Cancelled'")
)['total'];

// Orders Query
$query = "
SELECT *
FROM orders
WHERE
customer_name LIKE '%$search%'
OR email LIKE '%$search%'
OR order_status LIKE '%$search%'
ORDER BY created_at DESC
";

$orders = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Orders</title>

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

.badge{
    font-size:14px;
}

</style>

</head>

<body>

<div class="sidebar">

<h2>
<i class="bi bi-bag-fill"></i>
TechCart
</h2>

<a href="index.php">Dashboard</a>
<a href="products.php">Products</a>
<a href="orders.php">Orders</a>
<a href="users.php">Users</a>
<a href="logout.php">Logout</a>

</div>

<div class="main">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold mb-1">Orders</h2>

<p class="text-muted">

Manage customer orders

</p>

</div>

</div>

<!-- Statistics -->

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="card bg-primary text-white shadow-sm border-0">

<div class="card-body">

<i class="bi bi-receipt fs-1"></i>

<h6 class="mt-3">Total Orders</h6>

<h2><?php echo $totalOrders; ?></h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-warning text-dark shadow-sm border-0">

<div class="card-body">

<i class="bi bi-hourglass-split fs-1"></i>

<h6 class="mt-3">Pending</h6>

<h2><?php echo $pendingOrders; ?></h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-success text-white shadow-sm border-0">

<div class="card-body">

<i class="bi bi-check-circle fs-1"></i>

<h6 class="mt-3">Delivered</h6>

<h2><?php echo $deliveredOrders; ?></h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-danger text-white shadow-sm border-0">

<div class="card-body">

<i class="bi bi-x-circle fs-1"></i>

<h6 class="mt-3">Cancelled</h6>

<h2><?php echo $cancelledOrders; ?></h2>

</div>

</div>

</div>

</div>

<!-- Search -->

<div class="card mb-4 shadow-sm border-0">

<div class="card-body">

<form method="GET">

<div class="row g-3">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search customer, email or status..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-2 d-grid">

<button class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</div>

</form>

</div>

</div>

<div class="card">

<div class="card-body">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Total</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($order = mysqli_fetch_assoc($orders)){ ?>

<tr>

<td>

#<?php echo $order['id']; ?>

</td>

<td>

<?php echo $order['customer_name']; ?>

</td>

<td>

$<?php echo number_format($order['total'],2); ?>

</td>

<td>

<?php

$status = $order['order_status'];

$badge = "secondary";

if($status=="Pending"){

$badge="warning text-dark";

}elseif($status=="Processing"){

$badge="primary";

}elseif($status=="Shipped"){

$badge="info";

}elseif($status=="Delivered"){

$badge="success";

}elseif($status=="Cancelled"){

$badge="danger";

}

?>

<span class="badge bg-<?php echo $badge; ?>">

<?php echo $status; ?>

</span>

</td>

<td>

<?php echo date("M d, Y", strtotime($order['created_at'])); ?>

</td>

<td>

<a
href="view_order.php?id=<?php echo $order['id']; ?>"
class="btn btn-outline-primary btn-sm">

View

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>