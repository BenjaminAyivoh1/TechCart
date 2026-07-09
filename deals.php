<?php
session_start();
require_once "config/database.php";

$deals = mysqli_query($conn, "
SELECT products.*, categories.category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
WHERE discount_price IS NOT NULL
ORDER BY discount_price ASC
");
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h1 class="fw-bold display-4 text-danger">

🔥 Today's Deals

</h1>

<p class="lead text-muted">

Limited-time discounts on our best products.

</p>

</div>

<div class="row g-4">

<?php while($product = mysqli_fetch_assoc($deals)){ ?>

<?php
$discount =
round(
(($product['price'] - $product['discount_price'])
/
$product['price']) * 100
);
?>

<div class="col-lg-3 col-md-6">

<div class="card h-100 shadow-sm border-0">

<div class="position-relative">

<img
src="assets/images/<?php echo $product['image']; ?>"
class="card-img-top"
style="height:250px;object-fit:cover;">

<span
class="badge bg-danger position-absolute top-0 end-0 m-2">

<?php echo $discount; ?>% OFF

</span>

</div>

<div class="card-body">

<span class="badge bg-primary mb-2">

<?php echo $product['category_name']; ?>

</span>

<h5 class="fw-bold">

<?php echo $product['product_name']; ?>

</h5>

<p class="text-muted">

<?php echo substr($product['description'],0,70); ?>...

</p>

<h4>

<span
class="text-decoration-line-through text-muted me-2">

$<?php echo number_format($product['price'],2); ?>

</span>

<span class="text-danger fw-bold">

$<?php echo number_format($product['discount_price'],2); ?>

</span>

</h4>

<a
href="product.php?id=<?php echo $product['id']; ?>"
class="btn btn-danger w-100 mt-3">

View Deal

</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</section>

<?php include "includes/footer.php"; ?>