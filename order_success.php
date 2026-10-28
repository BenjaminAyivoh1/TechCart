<?php
// The navbar reads the session (login state, cart count), so it must be started here
session_start();

require_once 'includes/functions.php';

// Set by place_order.php; shown once
$orderId = (int)($_SESSION['last_order_id'] ?? 0);
unset($_SESSION['last_order_id']);

$pageTitle = 'Order Confirmed';

include 'includes/header.php';
include 'includes/navbar.php';

?>

<div class="container py-5">

    <div class="text-center">

        <h1 class="text-success">
            🎉 Order Placed Successfully!
        </h1>

        <?php if ($orderId > 0): ?>

        <p class="lead mb-1">
            Your order number is
            <strong>#<?php echo $orderId; ?></strong>.
        </p>

        <?php endif; ?>

        <p class="lead">
            Thank you for shopping with TechCart.
        </p>

        <?php if (isset($_SESSION['user_id'])): ?>

        <a href="my_orders.php" class="btn btn-outline-primary me-2">
            View My Orders
        </a>

        <?php else: ?>

        <p class="text-muted">
            Keep your order number for reference.
            Log in or register to track your orders.
        </p>

        <?php endif; ?>

        <a href="index.php" class="btn btn-primary">
            Continue Shopping
        </a>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
