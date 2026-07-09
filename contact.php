<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">Contact Us</h1>

        <p class="lead text-muted">
            We'd love to hear from you. Reach out anytime.
        </p>

    </div>

    <div class="row g-5">

        <!-- Contact Form -->
        <div class="col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <h3 class="mb-4">

                        Send us a Message

                    </h3>

                    <form>

                        <div class="mb-3">

                            <label class="form-label">Full Name</label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="John Doe">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                class="form-control"
                                placeholder="john@example.com">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Subject</label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="How can we help?">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">Message</label>

                            <textarea
                                class="form-control"
                                rows="6"
                                placeholder="Write your message here..."></textarea>

                        </div>

                        <button
                            class="btn btn-primary btn-lg w-100"
                            type="submit">

                            <i class="bi bi-send-fill"></i>

                            Send Message

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Contact Information -->
        <div class="col-lg-5">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-body p-4">

                    <h3 class="mb-4">

                        Contact Information

                    </h3>

                    <p>

                        <i class="bi bi-geo-alt-fill text-danger"></i>

                        <strong> Address</strong><br>

                        Accra, Ghana

                    </p>

                    <p>

                        <i class="bi bi-envelope-fill text-primary"></i>

                        <strong> Email</strong><br>

                        support@techcart.com

                    </p>

                    <p>

                        <i class="bi bi-telephone-fill text-success"></i>

                        <strong> Phone</strong><br>

                        +233 20 123 4567

                    </p>

                    <p>

                        <i class="bi bi-clock-fill text-warning"></i>

                        <strong> Working Hours</strong><br>

                        Monday - Friday<br>
                        8:00 AM - 6:00 PM

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-body text-center p-4">

                    <i class="bi bi-headset text-primary display-3"></i>

                    <h4 class="mt-3">

                        24/7 Customer Support

                    </h4>

                    <p class="text-muted">

                        Our support team is always ready to assist you with orders,
                        returns and product enquiries.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>