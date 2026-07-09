<?php
session_start();

require_once 'config/database.php';

include 'includes/header.php';
include 'includes/navbar.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$total = 0;
?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-5">

<div>

<h1 class="fw-bold display-5">

<i class="bi bi-credit-card-2-front text-primary"></i>

Checkout

</h1>

<p class="text-muted mb-0">

Complete your order securely.

</p>

</div>

<a
href="cart.php"
class="btn btn-outline-primary">

<i class="bi bi-arrow-left"></i>

Back To Cart

</a>

</div>

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

                    <form action="place_order.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">Full Name</label>

                            <input
                            type="text"
                            name="name"
                            class="form-control form-control-lg rounded-pill"
                            placeholder="John Doe"
                            required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">Email</label>

                            <input
                            type="email"
                            name="email"
                            class="form-control form-control-lg rounded-pill"
                            placeholder="john@email.com"
                            required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">Phone</label>

                        <input
                        type="text"
                        name="phone"
                        class="form-control form-control-lg rounded-pill"
                        placeholder="+233 XX XXX XXXX"
                        required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">Address</label>

                            <textarea
                                name="address"
                                rows="5"
                                class="form-control rounded-4"
                                placeholder="House number, street, city..."
                                required></textarea>
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

                    foreach ($_SESSION['cart'] as $id => $qty) {
                        echo "<pre>";
                            print_r($_SESSION['cart']);
                            echo "</pre>";

                        $id = (int)$id;

                        $query = "SELECT * FROM products WHERE id = $id";
                        $result = mysqli_query($conn, $query);

                        if ($product = mysqli_fetch_assoc($result)) {

                            $subtotal = $product['price'] * $qty;
                            $total += $subtotal;

                    ?>

                            <div class="d-flex align-items-center mb-4">

                                <img
                                src="assets/images/<?php echo htmlspecialchars($product['image']); ?>"
                                class="rounded"
                                style="width:65px;height:65px;object-fit:cover;">

                                <div class="ms-3 flex-grow-1">

                                <h6 class="fw-bold mb-1">

                                <?php echo htmlspecialchars($product['product_name']); ?>

                                </h6>

                                <small class="text-muted">

                                Qty: <?php echo $qty; ?>

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
                    <i class="bi bi-lock-fill"></i>
                    Place Secure Order
                    </button>

                </div>

            </div>

        </div>

    </div>

    </form>

</div>

<?php include 'includes/footer.php'; ?>