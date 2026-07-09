<?php
session_start();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="display-3 fw-bold">
            <i class="bi bi-question-circle-fill text-primary"></i>
            Frequently Asked Questions
        </h1>

        <p class="lead text-muted">
            Find answers to the questions we receive most often.
        </p>

    </div>

    <div class="accordion shadow rounded-4" id="faqAccordion">

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button fw-semibold"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq1">

                    How long does shipping take?

                </button>

            </h2>

            <div id="faq1"
                class="accordion-collapse collapse show"
                data-bs-parent="#faqAccordion">

                <div class="accordion-body">

                    Standard delivery takes 2–5 business days depending on your location.

                </div>

            </div>

        </div>

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed fw-semibold"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq2">

                    Can I return a product?

                </button>

            </h2>

            <div id="faq2"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion">

                <div class="accordion-body">

                    Yes. Products can be returned within 14 days provided they are unused and in their original packaging.

                </div>

            </div>

        </div>

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed fw-semibold"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq3">

                    Which payment methods are accepted?

                </button>

            </h2>

            <div id="faq3"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion">

                <div class="accordion-body">

                    TechCart accepts Visa, Mastercard, Mobile Money, and other secure payment methods.

                </div>

            </div>

        </div>

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed fw-semibold"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq4">

                    How do I track my order?

                </button>

            </h2>

            <div id="faq4"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion">

                <div class="accordion-body">

                    After placing an order, you will receive a confirmation email with your tracking information.

                </div>

            </div>

        </div>

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed fw-semibold"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq5">

                    Is my payment secure?

                </button>

            </h2>

            <div id="faq5"
                class="accordion-collapse collapse"
                data-bs-parent="#faqAccordion">

                <div class="accordion-body">

                    Absolutely. All payments are processed using encrypted, secure payment gateways.

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>