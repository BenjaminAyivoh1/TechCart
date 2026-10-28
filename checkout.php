<?php
session_start();

require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirect BEFORE any HTML is sent, otherwise header() cannot work
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Error message left by place_order.php (shown once)
$checkoutError = $_SESSION['checkout_error'] ?? '';
unset($_SESSION['checkout_error']);

// Prefill the form for logged-in customers
$prefill = ['full_name' => '', 'email' => '', 'phone' => '', 'address' => ''];

if (isset($_SESSION['user_id'])) {

    $userId = (int)$_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT full_name, email, phone, address FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    if ($row = $stmt->get_result()->fetch_assoc()) {
        $prefill = array_map(fn($v) => (string)$v, $row);
    }
}

$total = 0;

$pageTitle = 'Checkout';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-5">

<div>

<h1 class="fw-bold display-5">

<i class="bi bi-credit-card-2-front text-primary"></i>

Checkout

</h1>

<p class="text-muted mb-0">

Enter your delivery details to complete the order.

</p>

</div>

<a
href="cart.php"
class="btn btn-outline-primary">

<i class="bi bi-arrow-left"></i>

Back To Cart

</a>

</div>

<?php if ($checkoutError !== ''): ?>

<div class="alert alert-danger">

<i class="bi bi-exclamation-triangle-fill"></i>

<?php echo e($checkoutError); ?>

</div>

<?php endif; ?>

<form action="place_order.php" method="POST">

<div class="row g-4">

        <!-- Customer Information -->
        <div class="col-lg-7">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-header bg-white border-0 pt-4">

                    <h3 class="fw-bold mb-1">

                    <i class="bi bi-person-circle text-primary"></i>

                    Customer Information

                    </h3>

                    <p class="text-muted mb-0">

                    Please enter your delivery details.

                    </p>

                </div>

                <div class="card-body p-4">

                        <div class="mb-3">

                            <label class="form-label">Full Name</label>

                            <input
                            type="text"
                            name="name"
                            maxlength="100"
                            class="form-control form-control-lg rounded-pill"
                            placeholder="John Doe"
                            value="<?php echo e($prefill['full_name']); ?>"
                            required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">Email</label>

                            <input
                            type="email"
                            name="email"
                            maxlength="100"
                            class="form-control form-control-lg rounded-pill"
                            placeholder="john@email.com"
                            value="<?php echo e($prefill['email']); ?>"
                            required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">Phone</label>

                            <input
                            type="text"
                            name="phone"
                            maxlength="20"
                            class="form-control form-control-lg rounded-pill"
                            placeholder="+233 XX XXX XXXX"
                            value="<?php echo e($prefill['phone']); ?>"
                            required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">Address</label>

                            <textarea
                                name="address"
                                rows="5"
                                class="form-control rounded-4"
                                placeholder="House number, street, city..."
                                required><?php echo e($prefill['address']); ?></textarea>
                        </div>

                </div>

            </div>

        </div>

        <!-- Order Summary -->
        <div class="col-lg-5">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-header bg-primary text-white py-3">

                    <h4 class="mb-0">

                    <i class="bi bi-bag-check-fill"></i>

                    Order Summary

                    </h4>

                </div>

                <div class="card-body p-4">

                    <?php

                    $lookup = $conn->prepare("SELECT * FROM products WHERE id = ?");

                    foreach ($_SESSION['cart'] as $id => $qty) {

                        $id = (int)$id;

                        $lookup->bind_param("i", $id);
                        $lookup->execute();

                        if ($product = $lookup->get_result()->fetch_assoc()) {

                            $subtotal = effective_price($product) * $qty;
                            $total += $subtotal;

                    ?>

                            <div class="d-flex align-items-center mb-4">

                                <img
                                src="assets/images/<?php echo e($product['image']); ?>"
                                class="rounded"
                                style="width:65px;height:65px;object-fit:cover;"
                                alt="<?php echo e($product['product_name']); ?>">

                                <div class="ms-3 flex-grow-1">

                                <h6 class="fw-bold mb-1">

                                <?php echo e($product['product_name']); ?>

                                </h6>

                                <small class="text-muted">

                                Qty: <?php echo (int)$qty; ?>

                                </small>

                                </div>

                                <strong class="text-primary">

                                $<?php echo number_format($subtotal,2); ?>

                                </strong>

                            </div>
                    <?php

                        }

                    }

                    ?>

                    <hr>

                        <div class="d-flex justify-content-between mb-2">

                            <span>

                            Subtotal

                            </span>

                            <strong>

                            $<?php echo number_format($total,2); ?>

                            </strong>

                        </div>

                        <div class="d-flex justify-content-between mb-3">

                            <span>

                            Shipping

                            </span>

                            <span class="text-success">

                            FREE

                            </span>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h3>

                            Total

                            </h3>

                            <h3 class="text-primary fw-bold">

                            $<?php echo number_format($total,2); ?>

                            </h3>

                        </div>

                    <button
                    type="submit"
                    class="btn btn-success btn-lg w-100 rounded-pill">
                    <i class="bi bi-bag-check"></i>
                    Place Order
                    </button>

                    <p class="text-muted small text-center mt-3 mb-0">
                    Demo store: no payment is taken.
                    </p>

                </div>

            </div>

        </div>

    </div>

</form>

</div>

<?php include 'includes/footer.php'; ?>
