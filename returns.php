<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="display-3 fw-bold">
            <i class="bi bi-arrow-counterclockwise text-danger"></i>
            Returns & Refunds
        </h1>

        <p class="lead text-muted">
            Easy returns with a simple refund process.
        </p>

    </div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-5">

            <h3 class="fw-bold mb-4">
                Return Policy
            </h3>

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    Products can be returned within <strong>7 days</strong> of delivery.
                </li>

                <li class="list-group-item">
                    Items must be unused and in their original packaging.
                </li>

                <li class="list-group-item">
                    Proof of purchase is required.
                </li>

                <li class="list-group-item">
                    Refunds are processed within 5–7 business days.
                </li>

                <li class="list-group-item">
                    Damaged or defective products qualify for free replacement.
                </li>

            </ul>

            <div class="alert alert-info mt-4">

                <i class="bi bi-info-circle-fill"></i>

                Need help with a return? Contact our support team anytime.

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>