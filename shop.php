<?php
session_start();

require_once __DIR__ . "/config/database.php";

include "includes/header.php";
include "includes/navbar.php";

/*
|--------------------------------------------------------------------------
| Search & Filters
|--------------------------------------------------------------------------
*/

$search = "";
$category = "";
$status = "";
$sort = "newest";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

if(isset($_GET['category'])){
    $category = mysqli_real_escape_string(
        $conn,
        $_GET['category']
    );
}

if(isset($_GET['status'])){
    $status = mysqli_real_escape_string(
        $conn,
        $_GET['status']
    );
}

if(isset($_GET['sort'])){
    $sort = $_GET['sort'];
}

/*
|--------------------------------------------------------------------------
| Product Query
|--------------------------------------------------------------------------
*/

$query = "

SELECT

products.*,

categories.category_name

FROM products

LEFT JOIN categories

ON products.category_id = categories.id

WHERE

(

products.product_name LIKE '%$search%'

OR

products.description LIKE '%$search%'

OR

categories.category_name LIKE '%$search%'

)

";

/*
|--------------------------------------------------------------------------
| Category Filter
|--------------------------------------------------------------------------
*/

if($category != ""){

$category = (int)$category;

$query .= "

AND products.category_id = $category

";

}

/*
|--------------------------------------------------------------------------
| Stock Filter
|--------------------------------------------------------------------------
*/

if($status=="instock"){

$query .= "

AND products.stock > 0

";

}

elseif($status=="low"){

$query .= "

AND products.stock BETWEEN 1 AND 5

";

}

elseif($status=="out"){

$query .= "

AND products.stock=0

";

}

/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/

switch($sort){

case "oldest":

$query .= "

ORDER BY products.id ASC

";

break;

case "price_high":

$query .= "

ORDER BY products.price DESC

";

break;

case "price_low":

$query .= "

ORDER BY products.price ASC

";

break;

case "name":

$query .= "

ORDER BY products.product_name ASC

";

break;

default:

$query .= "

ORDER BY products.id DESC

";

}

/*
|--------------------------------------------------------------------------
| Execute Query
|--------------------------------------------------------------------------
*/

$products = mysqli_query(
    $conn,
    $query
);

if(!$products){

die(

mysqli_error($conn)

);

}

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

$categories = mysqli_query(

$conn,

"

SELECT *

FROM categories

ORDER BY category_name

"

);

$productCount = mysqli_num_rows($products);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>

Shop

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

.shop-header{

background:linear-gradient(135deg,#0d6efd,#2563eb);

padding:80px 0;

color:white;

margin-bottom:50px;

}

.shop-header h1{

font-size:50px;

font-weight:700;

}

.shop-header p{

font-size:18px;

opacity:.9;

}

.filter-card{

border:none;

border-radius:18px;

box-shadow:0 10px 25px rgba(0,0,0,.08);

}

.product-card{

border:none;

border-radius:18px;

overflow:hidden;

transition:.3s;

}

.product-card:hover{

transform:translateY(-8px);

box-shadow:0 18px 35px rgba(0,0,0,.12);

}

.card-img-top{

height:260px;

object-fit:cover;

}

.stock-badge{

position:absolute;

top:15px;

right:15px;

}

</style>

</head>

<body>

<!-- ======================================= -->

<!-- SHOP HEADER -->

<!-- ======================================= -->

<section class="shop-header">

<div class="container text-center">

<h1>

Shop

</h1>

<p>

Browse our collection of premium electronics.

</p>

</div>

</section>

<!-- ======================================= -->
<!-- FILTERS -->
<!-- ======================================= -->

<div class="container mb-5">

<div class="card filter-card">

<div class="card-body">

<form method="GET">

<div class="row g-3">

<div class="col-lg-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search products..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-lg-2">

<select
name="category"
class="form-select">

<option value="">

All Categories

</option>

<?php

while($cat = mysqli_fetch_assoc($categories)){

?>

<option
value="<?php echo $cat['id']; ?>"
<?php if($category == $cat['id']) echo "selected"; ?>>

<?php echo htmlspecialchars($cat['category_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-lg-2">

<select
name="status"
class="form-select">

<option value="">

All Stock

</option>

<option
value="instock"
<?php if($status=="instock") echo "selected"; ?>>

In Stock

</option>

<option
value="low"
<?php if($status=="low") echo "selected"; ?>>

Low Stock

</option>

<option
value="out"
<?php if($status=="out") echo "selected"; ?>>

Out Of Stock

</option>

</select>

</div>

<div class="col-lg-2">

<select
name="sort"
class="form-select">

<option
value="newest"
<?php if($sort=="newest") echo "selected"; ?>>

Newest

</option>

<option
value="oldest"
<?php if($sort=="oldest") echo "selected"; ?>>

Oldest

</option>

<option
value="price_high"
<?php if($sort=="price_high") echo "selected"; ?>>

Highest Price

</option>

<option
value="price_low"
<?php if($sort=="price_low") echo "selected"; ?>>

Lowest Price

</option>

<option
value="name"
<?php if($sort=="name") echo "selected"; ?>>

A - Z

</option>

</select>

</div>

<div class="col-lg-2 d-grid">

<button
class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</div>

<div class="mt-3 d-flex justify-content-between align-items-center">

<div>

<strong>

Showing

<?php echo $productCount; ?>

Product<?php echo ($productCount!=1) ? "s" : ""; ?>

</strong>

</div>

<div>

<a
href="shop.php"
class="btn btn-outline-secondary">

<i class="bi bi-arrow-clockwise"></i>

Reset Filters

</a>

</div>

</div>

</form>

</div>

</div>

</div>

<!-- ======================================= -->
<!-- PRODUCTS -->
<!-- ======================================= -->

<div class="container">

<div class="row g-4">

<?php

if(mysqli_num_rows($products) > 0){

while($product = mysqli_fetch_assoc($products)){

$image = "assets/images/".$product['image'];

?>

<div class="col-xl-3 col-lg-4 col-md-6">

<div class="card product-card h-100 shadow-sm">

<div class="position-relative">

<?php

if(
!empty($product['image'])
&& file_exists($image)
){

?>

<img
src="<?php echo $image; ?>"
class="card-img-top"
alt="<?php echo htmlspecialchars($product['product_name']); ?>">

<?php

}else{

?>

<div
class="bg-light d-flex justify-content-center align-items-center"
style="height:260px;">

<i class="bi bi-image display-3 text-secondary"></i>

</div>

<?php

}

?>

<?php

if($product['stock']==0){

?>

<span class="badge bg-danger stock-badge">

Out Of Stock

</span>

<?php

}elseif($product['stock']<=5){

?>

<span class="badge bg-warning text-dark stock-badge">

Low Stock

</span>

<?php

}else{

?>

<span class="badge bg-success stock-badge">

In Stock

</span>

<?php

}

?>

</div>

<div class="card-body d-flex flex-column">

<span class="badge bg-primary mb-3 align-self-start">

<?php echo htmlspecialchars($product['category_name']); ?>

</span>

<h5 class="fw-bold">

<?php echo htmlspecialchars($product['product_name']); ?>

</h5>

<p class="text-muted small flex-grow-1">

<?php

echo strlen($product['description']) > 80
    ? htmlspecialchars(substr($product['description'],0,80))."..."
    : htmlspecialchars($product['description']);
?>
</p>

<?php if(!empty($product['discount_price'])){ ?>

<h4 class="fw-bold text-danger">

    $<?php echo number_format($product['discount_price'],2); ?>

    <small class="text-muted text-decoration-line-through">

        $<?php echo number_format($product['price'],2); ?>

    </small>

</h4>

<?php } else { ?>

<h4 class="text-primary fw-bold">

    $<?php echo number_format($product['price'],2); ?>

</h4>

<?php } ?>

<p class="small text-muted">

Stock:

<strong>

<?php echo $product['stock']; ?>

</strong>

</p>

<div class="d-grid gap-2 mt-3">

<a
href="product.php?id=<?php echo $product['id']; ?>"
class="btn btn-outline-primary">

<i class="bi bi-eye"></i>

View Product

</a>

<?php if($product['stock']>0){ ?>

<a
href="add_to_cart.php?id=<?php echo $product['id']; ?>"
class="btn btn-primary">

<i class="bi bi-cart-plus"></i>

Add To Cart

</a>

<?php }else{ ?>

<button
class="btn btn-secondary"
disabled>

Out Of Stock

</button>

<?php } ?>

</div>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="col-12">

<div class="card shadow-sm">

<div class="card-body text-center py-5">

<i class="bi bi-search display-2 text-muted"></i>

<h3 class="mt-4">

No Products Found

</h3>

<p class="text-muted">

No products matched your search or filters.

</p>

<a
href="shop.php"
class="btn btn-primary">

Reset Filters

</a>

</div>

</div>

</div>

<?php

}

?>

</div>

</div>

