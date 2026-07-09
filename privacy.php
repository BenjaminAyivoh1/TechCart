<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="display-3 fw-bold">

            <i class="bi bi-shield-lock-fill text-success"></i>

            Privacy Policy

        </h1>

        <p class="lead text-muted">

            Your privacy and personal information matter to us.

        </p>

    </div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-5">

            <h3 class="fw-bold mb-4">

                How We Protect Your Data

            </h3>

            <p>

                TechCart collects only the information necessary to process your
                orders and provide customer support.

            </p>

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    Your personal information is never sold to third parties.
                </li>

                <li class="list-group-item">
                    Payment information is securely processed.
                </li>

                <li class="list-group-item">
                    Your account information is stored securely.
                </li>

                <li class="list-group-item">
                    Cookies are used only to improve your shopping experience.
                </li>

                <li class="list-group-item">
                    You may request deletion of your personal information at any
                    time.
                </li>

            </ul>

            <div class="alert alert-success mt-4">

                <i class="bi bi-shield-check"></i>

                We are committed to keeping your information safe.

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>