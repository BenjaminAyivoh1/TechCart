<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="display-3 fw-bold">
            <i class="bi bi-truck text-primary"></i>
            Shipping Information
        </h1>

        <p class="lead text-muted">
            Everything you need to know about our delivery process.
        </p>

    </div>

    <div class="row g-4">

        <div class="col-md-4">

            <div class="card shadow border-0 h-100 text-center p-4">

                <i class="bi bi-lightning-charge-fill text-warning display-3"></i>

                <h4 class="mt-3">Fast Delivery</h4>

                <p class="text-muted">
                    Orders are processed within 24 hours and delivered within
                    2–5 business days.
                </p>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow border-0 h-100 text-center p-4">

                <i class="bi bi-geo-alt-fill text-danger display-3"></i>

                <h4 class="mt-3">Nationwide Coverage</h4>

                <p class="text-muted">
                    We deliver to every region in Ghana using trusted courier
                    partners.
                </p>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow border-0 h-100 text-center p-4">

                <i class="bi bi-box-seam text-success display-3"></i>

                <h4 class="mt-3">Secure Packaging</h4>

                <p class="text-muted">
                    Every product is carefully packed to prevent damage during
                    delivery.
                </p>

            </div>

        </div>

    </div>

    <div class="card shadow border-0 rounded-4 mt-5">

        <div class="card-body p-5">

            <h3 class="fw-bold mb-4">

                Delivery Schedule

            </h3>

            <table class="table table-bordered align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Location</th>

                        <th>Estimated Delivery</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>Accra</td>

                        <td>1–2 Business Days</td>

                    </tr>

                    <tr>

                        <td>Kumasi</td>

                        <td>2–3 Business Days</td>

                    </tr>

                    <tr>

                        <td>Takoradi</td>

                        <td>2–4 Business Days</td>

                    </tr>

                    <tr>

                        <td>Other Regions</td>

                        <td>3–5 Business Days</td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>