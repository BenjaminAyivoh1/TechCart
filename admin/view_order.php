<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Validate Order
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Update Order Status
|--------------------------------------------------------------------------
*/

$success = "";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['status'])) {

    $status = mysqli_real_escape_string(
        $conn,
        trim($_POST['status'])
    );

    mysqli_query(
        $conn,
        "
        UPDATE orders
        SET order_status='$status'
        WHERE id=$id
        "
    );

    $success = "Order status updated successfully.";
}

/*
|--------------------------------------------------------------------------
| Order Information
|--------------------------------------------------------------------------
*/

$orderQuery = mysqli_query(
    $conn,
    "
    SELECT *
    FROM orders
    WHERE id=$id
    "
);

if (mysqli_num_rows($orderQuery) == 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($orderQuery);

/*
|--------------------------------------------------------------------------
| Ordered Products
|--------------------------------------------------------------------------
*/

$orderItems = mysqli_query(
    $conn,
    "
    SELECT
        order_items.*,
        products.product_name,
        products.image
    FROM order_items

    JOIN products

    ON order_items.product_id=products.id

    WHERE order_items.order_id=$id
    "
);

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalItems = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT SUM(quantity) AS total
        FROM order_items
        WHERE order_id=$id
        "

    )

)['total'];

if(!$totalItems){

    $totalItems = 0;

}

/*
|--------------------------------------------------------------------------
| Status Badge
|--------------------------------------------------------------------------
*/

$statusClass = "secondary";
$statusIcon = "clock";

switch($order['order_status']){

    case "Pending":

        $statusClass="warning text-dark";
        $statusIcon="hourglass";

        break;

    case "Processing":

        $statusClass="info text-dark";
        $statusIcon="gear";

        break;

    case "Shipped":

        $statusClass="primary";
        $statusIcon="truck";

        break;

    case "Delivered":

        $statusClass="success";
        $statusIcon="check-circle";

        break;

    case "Cancelled":

        $statusClass="danger";
        $statusIcon="x-circle";

        break;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>View Order</title>

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
    padding:30px;
}

.sidebar h2{
    color:#fff;
    margin-bottom:40px;
}

.sidebar a{
    display:block;
    text-decoration:none;
    color:#d1d5db;
    padding:12px 14px;
    margin-bottom:6px;
    border-radius:10px;
    transition:.25s;
}

.sidebar a:hover{
    background:#2563eb;
    color:white;
}

.sidebar .active{
    background:#2563eb;
    color:white;
}

.main{
    margin-left:260px;
    padding:40px;
}

.card{
    border:none;
    border-radius:18px;
    transition:.25s;
}

.card:hover{
    transform:translateY(-3px);
}

.stat-icon{
    font-size:34px;
    opacity:.85;
}

.customer-icon{
    width:90px;
    height:90px;
    background:#0d6efd;
    color:white;
    border-radius:50%;
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

<a href="orders.php" class="active">

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

Order

ORD-<?php echo str_pad($order['id'],4,"0",STR_PAD_LEFT); ?>

</h2>

<p class="text-muted mb-0">

Complete order details and customer information.

</p>

</div>

<div>

<a
href="orders.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button
onclick="window.print()"
class="btn btn-primary">

<i class="bi bi-printer"></i>

Print Invoice

</button>

</div>

</div>

<?php if($success!=""){ ?>

<div class="alert alert-success shadow-sm">

<i class="bi bi-check-circle-fill"></i>

<?php echo $success; ?>

</div>

<?php } ?>

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="card bg-primary text-white">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6>Total Amount</h6>

<h3>

$<?php echo number_format($order['total'],2); ?>

</h3>

</div>

<i class="bi bi-cash-stack stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-success text-white">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6>Total Items</h6>

<h3>

<?php echo $totalItems; ?>

</h3>

</div>

<i class="bi bi-box-seam stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-<?php echo explode(' ',$statusClass)[0]; ?> text-white">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6>Status</h6>

<h5>

<?php echo $order['order_status']; ?>

</h5>

</div>

<i class="bi bi-<?php echo $statusIcon; ?> stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-dark text-white">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6>Order Date</h6>

<h6>

<?php echo date("M d, Y",strtotime($order['created_at'])); ?>

</h6>

</div>

<i class="bi bi-calendar-event stat-icon"></i>

</div>

</div>

</div>

</div>

</div>

<div class="card shadow-sm mb-4">

<div class="card-body">

<div class="row">

<div class="col-lg-2 text-center">

<div class="customer-icon">

<i class="bi bi-person-fill"></i>

</div>

</div>

<div class="col-lg-10">

<h4>

<?php echo htmlspecialchars($order['customer_name']); ?>

</h4>

<hr>

<div class="row">

<div class="col-md-6">

<p>

<i class="bi bi-envelope-fill text-primary"></i>

<strong>Email:</strong>

<?php echo htmlspecialchars($order['email']); ?>

</p>

<p>

<i class="bi bi-telephone-fill text-success"></i>

<strong>Phone:</strong>

<?php echo htmlspecialchars($order['phone']); ?>

</p>

</div>

<div class="col-md-6">

<p>

<i class="bi bi-geo-alt-fill text-danger"></i>

<strong>Address:</strong>

<?php echo htmlspecialchars($order['address']); ?>

</p>

<p>

<strong>Status:</strong>

<span class="badge bg-<?php echo explode(' ',$statusClass)[0]; ?>">

<i class="bi bi-<?php echo $statusIcon; ?>"></i>

<?php echo $order['order_status']; ?>

</span>

</p>

</div>

</div>

<form method="POST" class="mt-3">

<div class="row">

<div class="col-md-6">

<select
name="status"
class="form-select">

<?php

$statuses=[
"Pending",
"Processing",
"Shipped",
"Delivered",
"Cancelled"
];

foreach($statuses as $status){

?>

<option
value="<?php echo $status;?>"
<?php if($status==$order['order_status']) echo "selected"; ?>>

<?php echo $status; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-3">

<button
class="btn btn-success w-100">

<i class="bi bi-check2-circle"></i>

Update Status

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

<div class="card shadow-sm">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="bi bi-box-seam"></i>

Ordered Products

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Image</th>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>

</tr>

</thead>

<tbody>

<?php

$grandTotal = 0;

if(mysqli_num_rows($orderItems)>0){

while($item=mysqli_fetch_assoc($orderItems)){

$subtotal = $item['price'] * $item['quantity'];

$grandTotal += $subtotal;

$image = "../assets/images/".$item['image'];

?>

<tr>

<td width="100">

<?php if(!empty($item['image']) && file_exists($image)){ ?>

<img
src="<?php echo $image; ?>"
width="80"
height="80"
style="object-fit:cover;border-radius:12px;">

<?php }else{ ?>

<div
class="bg-light border rounded d-flex justify-content-center align-items-center"
style="width:80px;height:80px;">

<i class="bi bi-image fs-3 text-secondary"></i>

</div>

<?php } ?>

</td>

<td>

<strong>

<?php echo htmlspecialchars($item['product_name']); ?>

</strong>

</td>

<td>

$<?php echo number_format($item['price'],2); ?>

</td>

<td>

<span class="badge bg-primary">

<?php echo $item['quantity']; ?>

</span>

</td>

<td>

<strong>

$<?php echo number_format($subtotal,2); ?>

</strong>

</td>

</tr>

<?php }

}else{

?>

<tr>

<td colspan="5" class="text-center py-5">

<i class="bi bi-box display-4 text-muted"></i>

<h4 class="mt-3">

No products found for this order.

</h4>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="row mt-4">

<div class="col-lg-8">

</div>

<div class="col-lg-4">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

Order Summary

</h5>

</div>

<div class="card-body">

<div class="d-flex justify-content-between mb-3">

<span>Total Items</span>

<strong>

<?php echo $totalItems; ?>

</strong>

</div>

<div class="d-flex justify-content-between mb-3">

<span>Order Status</span>

<span class="badge bg-<?php echo explode(' ',$statusClass)[0]; ?>">

<?php echo $order['order_status']; ?>

</span>

</div>

<hr>

<div class="d-flex justify-content-between">

<h4>

Grand Total

</h4>

<h4 class="text-success">

$<?php echo number_format($grandTotal,2); ?>

</h4>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>