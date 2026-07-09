<?php
session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: my_orders.php");
    exit;
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$orderQuery = mysqli_query(
    $conn,
    "SELECT *
    FROM orders
    WHERE id = $order_id
    AND user_id = $user_id"
);

if (mysqli_num_rows($orderQuery) == 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($orderQuery);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

<h1 class="fw-bold mb-4">
<i class="bi bi-receipt-cutoff text-primary"></i>
Order #<?php echo $order['id']; ?>
</h1>

<div class="card shadow-sm border-0 mb-4">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<h5>Customer</h5>

<p class="mb-1">
<strong>Name:</strong>
<?php echo htmlspecialchars($order['customer_name']); ?>
</p>

<p class="mb-1">
<strong>Email:</strong>
<?php echo htmlspecialchars($order['email']); ?>
</p>

<p class="mb-1">
<strong>Phone:</strong>
<?php echo htmlspecialchars($order['phone']); ?>
</p>

<p>
<strong>Address:</strong>
<?php echo htmlspecialchars($order['address']); ?>
</p>

</div>

<div class="col-md-6 text-md-end">

<h5>Order Info</h5>

<p>
<strong>Status:</strong>

<span class="badge bg-primary">

<?php echo $order['order_status']; ?>

</span>

</p>

<p>

<strong>Date:</strong>

<?php echo date("F d, Y", strtotime($order['created_at'])); ?>

</p>

<p>

<strong>Total:</strong>

<span class="fw-bold text-success">

$<?php echo number_format($order['total'],2); ?>

</span>

</p>

</div>

</div>

</div>

</div>

<div class="card shadow-sm border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

Items Purchased

</h4>

</div>

<div class="card-body">

<table class="table align-middle">

<thead>

<tr>

<th>Product</th>

<th>Price</th>

<th>Qty</th>

<th>Total</th>

</tr>

</thead>

<tbody>

<?php

$itemQuery = mysqli_query(
$conn,
"
SELECT
order_items.*,
products.product_name,
products.image
FROM order_items

JOIN products
ON products.id = order_items.product_id

WHERE order_items.order_id = $order_id
"
);

while($item = mysqli_fetch_assoc($itemQuery)){

?>

<tr>

<td>

<div class="d-flex align-items-center">

<img
src="assets/images/<?php echo htmlspecialchars($item['image']); ?>"
width="70"
height="70"
class="rounded me-3"
style="object-fit:cover;">

<div>

<strong>

<?php echo htmlspecialchars($item['product_name']); ?>

</strong>

</div>

</div>

</td>

<td>

$<?php echo number_format($item['price'],2); ?>

</td>

<td>

<?php echo $item['quantity']; ?>

</td>

<td>

$<?php echo number_format($item['price'] * $item['quantity'],2); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<div class="mt-4">

<a href="my_orders.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back to Orders

</a>

</div>

</div>

<?php include 'includes/footer.php'; ?>