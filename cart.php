<?php
session_start();

require_once __DIR__ . '/config/database.php';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-5">

<div>

<h1 class="fw-bold">

<i class="bi bi-cart3 text-primary"></i>

Shopping Cart

</h1>

<p class="text-muted mb-0">

Review your items before proceeding to checkout.

</p>

</div>

<a
href="shop.php"
class="btn btn-outline-primary">

<i class="bi bi-arrow-left"></i>

Continue Shopping

</a>

</div>

<?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>

<div class="card shadow-sm border-0">

<div class="card-body text-center py-5">

<i class="bi bi-cart-x display-1 text-primary mb-4"></i>

<h2>

Your Cart Is Empty

</h2>

<p class="text-muted">

Looks like you haven't added any products yet.

</p>

<a
href="shop.php"
class="btn btn-primary btn-lg mt-3">

<i class="bi bi-bag"></i>

Start Shopping

</a>

</div>

</div>

<?php else: ?>

<?php $total = 0; ?>

<div class="card border-0 shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table align-middle">

    <thead class="table-dark">

<tr>

<th width="120">

Product

</th>

<th>

Details

</th>

<th>

Price

</th>

<th width="180">

Quantity

</th>

<th>

Subtotal

</th>

<th width="120">

Remove

</th>

</tr>

</thead>

    <tbody>

<?php

foreach ($_SESSION['cart'] as $id => $qty) {

    $id = (int)$id;

    $query = "SELECT * FROM products WHERE id = $id";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        continue;
    }

    if ($product = mysqli_fetch_assoc($result)) {

        $subtotal = $product['price'] * $qty;

        $total += $subtotal;

?>

<tr>

<td>

<img
src="assets/images/<?php echo htmlspecialchars($product['image']); ?>"
class="rounded"
style="width:90px;height:90px;object-fit:cover;"
alt="<?php echo htmlspecialchars($product['product_name']); ?>">

</td>

<td>

<h5 class="fw-bold mb-1">

<?php echo htmlspecialchars($product['product_name']); ?>

</h5>

<p class="text-muted mb-2">

<?php echo substr(htmlspecialchars($product['description']),0,70); ?>...

</p>

<span class="badge bg-primary">

Tech Product

</span>

</td>

<td>

<h5 class="text-primary fw-bold">

$<?php echo number_format($product['price'],2); ?>

</h5>

</td>

<td>

<div class="d-flex align-items-center">

<a
href="update_cart.php?id=<?php echo $id; ?>&action=decrease"
class="btn btn-outline-secondary btn-sm">

<i class="bi bi-dash"></i>

</a>

<span class="mx-3 fw-bold">

<?php echo $qty; ?>

</span>

<a
href="update_cart.php?id=<?php echo $id; ?>&action=increase"
class="btn btn-outline-primary btn-sm">

<i class="bi bi-plus"></i>

</a>

</div>

</td>

<td>

<h5 class="fw-bold text-success">

$<?php echo number_format($subtotal,2); ?>

</h5>

</td>

<td>

<a
href="remove_from_cart.php?id=<?php echo $id; ?>"
class="btn btn-outline-danger"
onclick="return confirm('Remove this item from cart?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php

    }

}

?>

    </tbody>

</table>

</div>

</div>

</div>

<div class="row justify-content-end mt-5">

<div class="col-lg-5">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-4">

<h3 class="fw-bold mb-4">

Order Summary

</h3>

<div class="d-flex justify-content-between mb-3">

<span>

Subtotal

</span>

<strong>

$<?php echo number_format($total,2); ?>

</strong>

</div>

<div class="d-flex justify-content-between mb-3">

<span>

Shipping

</span>

<span class="text-success">

FREE

</span>

</div>

<hr>

<div class="d-flex justify-content-between mb-4">

<h4>

Total

</h4>

<h4 class="text-primary fw-bold">

$<?php echo number_format($total,2); ?>

</h4>

</div>

<div class="d-grid gap-2">

<a
href="checkout.php"
class="btn btn-primary btn-lg">

<i class="bi bi-credit-card"></i>

Proceed To Checkout

</a>

<a
href="clear_cart.php"
class="btn btn-outline-danger">

<i class="bi bi-trash"></i>

Clear Cart

</a>

<a
href="shop.php"
class="btn btn-outline-secondary">

<i class="bi bi-arrow-left"></i>

Continue Shopping

</a>

</div>

</div>

</div>

</div>

</div>

<?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>