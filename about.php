<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">About TechCart</h1>

        <p class="lead text-muted">
            Your trusted destination for quality electronics at affordable prices.
        </p>

    </div>

    <div class="row align-items-center g-5">

        <div class="col-lg-6">

            <img src="assets/images/macbook.jpg"
                 class="img-fluid rounded shadow">

        </div>

        <div class="col-lg-6">

            <h2 class="fw-bold mb-4">
                Who We Are
            </h2>

            <p>
                TechCart is a modern online electronics store dedicated to
                providing customers with premium laptops, smartphones,
                gaming accessories, wearables and other technology products.
            </p>

            <p>
                Our goal is to make shopping simple, secure and enjoyable
                while offering competitive prices and reliable customer service.
            </p>

        </div>

    </div>

    <hr class="my-5">

    <div class="row text-center">

        <div class="col-md-4">

            <i class="bi bi-truck fs-1 text-primary"></i>

            <h4 class="mt-3">Fast Delivery</h4>

            <p>
                Nationwide delivery with secure packaging.
            </p>

        </div>

        <div class="col-md-4">

            <i class="bi bi-shield-check fs-1 text-success"></i>

            <h4 class="mt-3">Secure Shopping</h4>

            <p>
                Every order is processed using secure technologies.
            </p>

        </div>

        <div class="col-md-4">

            <i class="bi bi-headset fs-1 text-warning"></i>

            <h4 class="mt-3">Customer Support</h4>

            <p>
                Friendly support whenever you need assistance.
            </p>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>