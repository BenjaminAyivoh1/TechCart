<?php
session_start();

require_once __DIR__ . '/config/database.php';

include 'includes/header.php';
include 'includes/navbar.php';

/*
|--------------------------------------------------------------------------
| Featured Products
|--------------------------------------------------------------------------
*/

$featuredProducts = mysqli_query(
    $conn,
    "
    SELECT *
    FROM products
    ORDER BY id DESC
    LIMIT 4
    "
);

if(!$featuredProducts){
    die("Database Error: ".mysqli_error($conn));
}
?>

<!-- ====================================================== -->
<!-- HERO -->
<!-- ====================================================== -->

<section class="hero">

<div class="container">

<div class="row align-items-center gy-5">

<div class="col-lg-6">

<span class="badge bg-light text-primary px-3 py-2 mb-3">

<i class="bi bi-lightning-charge-fill"></i>

Latest Tech Deals

</span>

<h1>

Shop The Latest
Electronics

</h1>

<p class="hero-text">

Discover premium laptops,
smartphones,
gaming accessories,
smart watches and audio devices
at unbeatable prices.

</p>

<div class="d-flex flex-wrap gap-3 mt-4">

<a
href="shop.php"
class="btn btn-light btn-lg px-4">

<i class="bi bi-cart-fill"></i>

Shop Now

</a>

<a
href="#why-us"
class="btn btn-outline-light btn-lg px-4">

<i class="bi bi-arrow-down-circle"></i>

Learn More

</a>

</div>

<div class="row mt-5">

<div class="col-4">

<h3 class="text-white fw-bold">

500+

</h3>

<p class="text-light">

Products

</p>

</div>

<div class="col-4">

<h3 class="text-white fw-bold">

24/7

</h3>

<p class="text-light">

Support

</p>

</div>

<div class="col-4">

<h3 class="text-white fw-bold">

100%

</h3>

<p class="text-light">

Secure

</p>

</div>

</div>

</div>

<div class="col-lg-6 text-center">

<img

src="assets/images/iphone.jpg"

class="img-fluid rounded-4 shadow-lg"

alt="TechCart Hero"

style="max-height:600px;object-fit:cover;">

</div>

</div>

</div>

</section>

<!-- ====================================================== -->
<!-- CATEGORIES -->
<!-- ====================================================== -->

<section class="categories">

<div class="container">

<h2 class="section-title">

Shop By Category

</h2>

<p class="text-center text-muted mb-5">

Browse products by category.

</p>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<a
href="shop.php?category=Laptops"
class="text-decoration-none">

<div class="category-card">

<i class="bi bi-laptop"></i>

<h5>

Laptops

</h5>

<p class="text-muted mb-0">

Powerful laptops for work,
gaming and creativity.

</p>

</div>

</a>

</div>

<div class="col-lg-3 col-md-6">

<a
href="shop.php?category=Phones"
class="text-decoration-none">

<div class="category-card">

<i class="bi bi-phone"></i>

<h5>

Phones

</h5>

<p class="text-muted mb-0">

Latest flagship smartphones.

</p>

</div>

</a>

</div>

<div class="col-lg-3 col-md-6">

<a
href="shop.php?category=Audio"
class="text-decoration-none">

<div class="category-card">

<i class="bi bi-headset"></i>

<h5>

Audio

</h5>

<p class="text-muted mb-0">

Headphones,
earbuds and speakers.

</p>

</div>

</a>

</div>

<div class="col-lg-3 col-md-6">

<a
href="shop.php?category=Wearables"
class="text-decoration-none">

<div class="category-card">

<i class="bi bi-smartwatch"></i>

<h5>

Wearables

</h5>

<p class="text-muted mb-0">

Smart watches and fitness gear.

</p>

</div>

</a>

</div>

</div>

</div>

</section>

<!-- ====================================================== -->
<!-- FEATURED PRODUCTS -->
<!-- ====================================================== -->

<section class="featured-products py-5">

<div class="container">

<h2 class="section-title">

Featured Products

</h2>

<p class="text-center text-muted mb-5">

Our newest arrivals selected for you.

</p>

<div class="row g-4">
<?php while($product = mysqli_fetch_assoc($featuredProducts)){ ?>

<div class="col-lg-3 col-md-6">

<div class="card product-card border-0 shadow-sm h-100">

<div class="position-relative">

<img
src="assets/images/<?php echo htmlspecialchars($product['image']); ?>"
class="card-img-top"
alt="<?php echo htmlspecialchars($product['product_name']); ?>"
style="
height:260px;
object-fit:cover;
">

<?php

if($product['stock'] == 0){

echo '

<span class="badge bg-danger position-absolute top-0 end-0 m-3">

Out Of Stock

</span>

';

}elseif($product['stock'] < 5){

echo '

<span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">

Low Stock

</span>

';

}else{

echo '

<span class="badge bg-success position-absolute top-0 end-0 m-3">

In Stock

</span>

';

}

?>

</div>

<div class="card-body d-flex flex-column">

<h5 class="fw-bold">

<?php echo htmlspecialchars($product['product_name']); ?>

</h5>

<p class="text-muted small flex-grow-1">

<?php

echo substr(

htmlspecialchars($product['description']),

0,

70

);

?>

...

</p>

<h3 class="text-primary fw-bold">

$<?php echo number_format($product['price'],2); ?>

</h3>

<div class="mt-3">

<?php if($product['stock'] > 0){ ?>

<a

href="product.php?id=<?php echo $product['id']; ?>"

class="btn btn-outline-dark w-100 mb-2">

<i class="bi bi-eye"></i>

View Product

</a>

<a

href="add_to_cart.php?id=<?php echo $product['id']; ?>"

class="btn btn-primary w-100">

<i class="bi bi-cart-plus"></i>

Add To Cart

</a>

<?php }else{ ?>

<button

class="btn btn-secondary w-100"

disabled>

Out Of Stock

</button>

<?php } ?>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

<div class="text-center mt-5">

<a

href="shop.php"

class="btn btn-primary btn-lg px-5">

<i class="bi bi-grid"></i>

View All Products
</a>

</div>

</div>

</section>

<!-- ====================================================== -->
<!-- WHY CHOOSE US -->
<!-- ====================================================== -->

<section class="why-us py-5 bg-light" id="why-us">

<div class="container">

<div class="text-center mb-5">

<h2 class="section-title">

Why Shop With TechCart?

</h2>

<p class="text-muted">

We provide premium electronics backed by excellent customer service.

</p>

</div>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="card h-100 border-0 shadow-sm text-center">

<div class="card-body p-4">

<i class="bi bi-truck display-4 text-primary mb-3"></i>

<h5>

Fast Delivery

</h5>

<p class="text-muted">

Quick nationwide delivery with secure packaging.

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card h-100 border-0 shadow-sm text-center">

<div class="card-body p-4">

<i class="bi bi-shield-check display-4 text-success mb-3"></i>

<h5>

Secure Payments

</h5>

<p class="text-muted">

Safe and encrypted payment processing.

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card h-100 border-0 shadow-sm text-center">

<div class="card-body p-4">

<i class="bi bi-award display-4 text-warning mb-3"></i>

<h5>

Premium Quality

</h5>

<p class="text-muted">

We stock trusted brands and genuine electronics.

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card h-100 border-0 shadow-sm text-center">

<div class="card-body p-4">

<i class="bi bi-headset display-4 text-info mb-3"></i>

<h5>

24/7 Support

</h5>

<p class="text-muted">

Our support team is always ready to assist you.

</p>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- ====================================================== -->
<!-- NEWSLETTER -->
<!-- ====================================================== -->

<section class="newsletter py-5">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card border-0 shadow-lg">

<div class="card-body text-center p-5">

<i class="bi bi-envelope-paper display-3 text-primary mb-3"></i>

<h2>

Subscribe To Our Newsletter

</h2>

<p class="text-muted mb-4">

Receive updates on new arrivals, exclusive discounts and special offers.

</p>

<form class="row g-3">

<div class="col-md-8">

<input
type="email"
class="form-control form-control-lg"
placeholder="Enter your email address">

</div>

<div class="col-md-4 d-grid">

<button
class="btn btn-primary btn-lg">

Subscribe

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<?php

include 'includes/footer.php';

?>