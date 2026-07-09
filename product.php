<?php
session_start();

require_once __DIR__ . "/config/database.php";

include "includes/header.php";
include "includes/navbar.php";

/*
|--------------------------------------------------------------------------
| Validate Product ID
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    header("Location: shop.php");
    exit;

}

$productID = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Fetch Product
|--------------------------------------------------------------------------
*/

$query = "
SELECT
    products.*,
    categories.category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
WHERE products.id = $productID
LIMIT 1
";

$productResult = mysqli_query($conn,$query);

if(
!$productResult ||
mysqli_num_rows($productResult)==0
){

    header("Location: shop.php");
    exit;

}

$product = mysqli_fetch_assoc($productResult);

/*
|--------------------------------------------------------------------------
| Related Products
|--------------------------------------------------------------------------
*/

$relatedProducts = mysqli_query(
$conn,
"
SELECT
products.*,
categories.category_name
FROM products
LEFT JOIN categories
ON products.category_id=categories.id
WHERE
products.category_id=".$product['category_id']."
AND
products.id!=".$productID."
LIMIT 4
"
);
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>

<?php echo htmlspecialchars($product['product_name']); ?>

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
rel="stylesheet">

<style>

body{

background:#f5f7fb;

font-family:Poppins,sans-serif;

}

.product-image{

border-radius:20px;

overflow:hidden;

background:white;

box-shadow:0 10px 30px rgba(0,0,0,.08);

}

.product-image img{

width:100%;

height:500px;

object-fit:cover;

}

.product-info{

background:white;

border-radius:20px;

padding:40px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

}

.stock-badge{

font-size:15px;

padding:8px 14px;

}

.related-card{

border:none;

border-radius:18px;

overflow:hidden;

transition:.3s;

}

.related-card:hover{

transform:translateY(-6px);

box-shadow:0 15px 30px rgba(0,0,0,.12);

}

</style>

</head>

<body>

<div class="container py-5">

<div class="row g-5">

<!-- ======================================= -->
<!-- PRODUCT IMAGE -->
<!-- ======================================= -->

<div class="col-lg-6">

<div class="product-image">

<?php

$image = "assets/images/".$product['image'];

if(
!empty($product['image'])
&& file_exists($image)
){

?>

<img
src="<?php echo $image; ?>"
alt="<?php echo htmlspecialchars($product['product_name']); ?>">

<?php

}else{

?>

<div
class="d-flex justify-content-center align-items-center bg-light"
style="height:500px;">

<i class="bi bi-image display-1 text-secondary"></i>

</div>

<?php } ?>

</div>

</div>

<!-- ======================================= -->
<!-- PRODUCT INFO -->
<!-- ======================================= -->

<div class="col-lg-6">

<div class="product-info">

<nav class="mb-3">

    <a href="index.php" class="text-decoration-none">
        Home
    </a>

    <i class="bi bi-chevron-right mx-1"></i>

    <a href="shop.php" class="text-decoration-none">
        Shop
    </a>

    <i class="bi bi-chevron-right mx-1"></i>

    <span class="text-muted">
        <?php echo htmlspecialchars($product['product_name']); ?>
    </span>

</nav>

<span class="badge bg-primary mb-3">

<?php echo htmlspecialchars($product['category_name']); ?>

</span>

<h1 class="fw-bold mb-3">

<?php echo htmlspecialchars($product['product_name']); ?>

</h1>

<?php if(!empty($product['discount_price'])){ ?>

<h2 class="fw-bold mb-4">

<span class="text-danger">
    $<?php echo number_format($product['discount_price'],2); ?>
</span>

<small class="text-muted text-decoration-line-through ms-2">
    $<?php echo number_format($product['price'],2); ?>
</small>

</h2>

<?php } else { ?>

<h2 class="text-primary fw-bold mb-4">
    $<?php echo number_format($product['price'],2); ?>
</h2>

<?php } ?>

<?php

if($product['stock']==0){

?>

<span class="badge bg-danger stock-badge">

<i class="bi bi-x-circle"></i>

Out Of Stock

</span>

<?php

}elseif($product['stock']<5){

?>

<span class="badge bg-warning text-dark stock-badge">

<i class="bi bi-exclamation-circle"></i>

Low Stock

</span>

<?php

}else{

?>

<span class="badge bg-success stock-badge">

<i class="bi bi-check-circle"></i>

In Stock

</span>

<?php } ?>

<hr class="my-4">

<p class="text-muted">

<?php echo nl2br(htmlspecialchars($product['description'])); ?>

</p>

<div class="row mt-4">

<div class="col-md-6">

<p>

<strong>Product ID</strong>

<br>

PRD-<?php echo str_pad($product['id'],4,"0",STR_PAD_LEFT); ?>

</p>

</div>

<div class="col-md-6">

<p>

<strong>Available Stock</strong>

<br>

<?php echo $product['stock']; ?>

Units

</p>

</div>

</div>

<?php if($product['stock']>0){ ?>

<form
action="add_to_cart.php"
method="GET"
class="mt-4">

<input
type="hidden"
name="id"
value="<?php echo $product['id']; ?>">

<div class="row g-3">

<div class="col-md-4">

<input
type="number"
name="quantity"
class="form-control"
value="1"
min="1"
max="<?php echo $product['stock']; ?>">

</div>

<div class="col-md-8 d-grid">

<button
class="btn btn-primary btn-lg">

<i class="bi bi-cart-plus"></i>

Add To Cart

</button>

</div>

</div>

</form>

<?php }else{ ?>

<button
class="btn btn-secondary btn-lg w-100 mt-4"
disabled>

Currently Unavailable

</button>

<?php } ?>

<div class="row text-center mt-5">

<div class="col-4">

<i class="bi bi-truck display-6 text-primary"></i>

<p class="small mt-2">

Fast Delivery

</p>

</div>

<div class="col-4">

<i class="bi bi-shield-check display-6 text-success"></i>

<p class="small mt-2">

Secure Payment

</p>

</div>

<div class="col-4">

<i class="bi bi-arrow-repeat display-6 text-warning"></i>

<p class="small mt-2">

Easy Returns

</p>

</div>

</div>

</div>

</div>

</div>

<!-- ======================================= -->
<!-- RELATED PRODUCTS -->
<!-- ======================================= -->

<div class="container pb-5">

<?php if(mysqli_num_rows($relatedProducts) > 0){ ?>

<hr class="my-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

You May Also Like

</h2>

<p class="text-muted mb-0">

More products from the same category

</p>

</div>

<a
href="shop.php"
class="btn btn-outline-primary">

View All Products

</a>

</div>

<div class="row g-4">

<?php while($related = mysqli_fetch_assoc($relatedProducts)){ ?>

<div class="col-lg-3 col-md-6">

<div class="card related-card h-100 shadow-sm">

<?php

$relatedImage = "assets/images/".$related['image'];

if(
!empty($related['image'])
&& file_exists($relatedImage)
){

?>

<img
src="<?php echo $relatedImage; ?>"
class="card-img-top"
style="height:220px;object-fit:cover;"
alt="<?php echo htmlspecialchars($related['product_name']); ?>">

<?php }else{ ?>

<div
class="bg-light d-flex justify-content-center align-items-center"
style="height:220px;">

<i class="bi bi-image display-4 text-secondary"></i>

</div>

<?php } ?>

<div class="card-body d-flex flex-column">

<span class="badge bg-primary align-self-start mb-3">

<?php echo htmlspecialchars($related['category_name']); ?>

</span>

<h5 class="fw-bold">

<?php echo htmlspecialchars($related['product_name']); ?>

</h5>

<p class="text-primary fw-bold fs-4">

$<?php echo number_format($related['price'],2); ?>

</p>

<div class="mt-auto d-grid">

<a
href="product.php?id=<?php echo $related['id']; ?>"
class="btn btn-outline-primary">

<i class="bi bi-eye"></i>

View Product

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

<!-- ======================================= -->
<!-- PRODUCT FEATURES -->
<!-- ======================================= -->

<section class="py-5 bg-light">

<div class="container">

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-truck display-4 text-primary"></i>

<h5 class="mt-3">

Fast Delivery

</h5>

<p class="text-muted">

Quick nationwide delivery with secure packaging.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-shield-lock display-4 text-success"></i>

<h5 class="mt-3">

Secure Payment

</h5>

<p class="text-muted">

Your payment information is always protected.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-arrow-repeat display-4 text-warning"></i>

<h5 class="mt-3">

Easy Returns

</h5>

<p class="text-muted">

Hassle-free returns within 30 days.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-headset display-4 text-info"></i>

<h5 class="mt-3">

24/7 Support

</h5>

<p class="text-muted">

Friendly customer support whenever you need help.

</p>

</div>

</div>

</div>

</section>

<?php

include "includes/footer.php";

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
