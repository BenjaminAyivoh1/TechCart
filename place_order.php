<?php
session_start();

require_once 'config/database.php';
require_once 'includes/functions.php';

// Make every mysqli failure throw, so the transaction below can roll back.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * A problem the customer can understand and fix (out of stock, etc).
 * Separate from mysqli_sql_exception, which extends RuntimeException,
 * so database errors are never shown to the customer.
 */
class OrderException extends Exception
{
}

/**
 * Send the customer back to checkout with a message.
 */
function checkout_fail(string $message): void
{
    $_SESSION['checkout_error'] = $message;
    header("Location: checkout.php");
    exit;
}

// Only accept the checkout form
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: checkout.php");
    exit;
}

// Nothing to order
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Validate customer details
|--------------------------------------------------------------------------
*/

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $address === '') {
    checkout_fail("Please fill in all the fields.");
}

if (mb_strlen($name) > 100) {
    checkout_fail("Name is too long (100 characters maximum).");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
    checkout_fail("Please enter a valid email address.");
}

if (mb_strlen($phone) > 20) {
    checkout_fail("Phone number is too long (20 characters maximum).");
}

/*
|--------------------------------------------------------------------------
| Clean the cart: positive integer quantities only
|--------------------------------------------------------------------------
*/

$items = [];

foreach ($_SESSION['cart'] as $productId => $qty) {

    $productId = (int)$productId;
    $qty       = (int)$qty;

    if ($productId > 0 && $qty > 0) {
        $items[$productId] = $qty;
    }
}

if (empty($items)) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}

// Lock rows in a consistent order so two checkouts cannot deadlock
ksort($items);

// Logged-in customers order as themselves; guests get user_id NULL.
// (We never attach an order to an account just because the email matches.)
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

/*
|--------------------------------------------------------------------------
| Place the order inside one transaction
|--------------------------------------------------------------------------
*/

try {

    $conn->begin_transaction();

    // 1. Lock and validate every product, price it from the database
    $select = $conn->prepare(
        "SELECT id, product_name, price, discount_price, stock
         FROM products
         WHERE id = ?
         FOR UPDATE"
    );

    $lines = [];
    $total = 0.0;

    foreach ($items as $productId => $qty) {

        $select->bind_param("i", $productId);
        $select->execute();
        $product = $select->get_result()->fetch_assoc();

        if (!$product) {
            throw new OrderException("An item in your cart is no longer available.");
        }

        if ($qty > (int)$product['stock']) {
            throw new OrderException(
                "Sorry, only " . (int)$product['stock'] . " of \"" . $product['product_name'] . "\" left in stock."
            );
        }

        $unitPrice = effective_price($product);

        $lines[] = [
            'product_id' => $productId,
            'qty'        => $qty,
            'price'      => $unitPrice,
        ];

        $total += $unitPrice * $qty;
    }

    $total = round($total, 2);

    // 2. Create the order
    $insertOrder = $conn->prepare(
        "INSERT INTO orders
            (user_id, customer_name, email, phone, address, total, order_status)
         VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
    );

    $insertOrder->bind_param("issssd", $userId, $name, $email, $phone, $address, $total);
    $insertOrder->execute();

    $orderId = $conn->insert_id;

    // 3. Save the items and take the stock
    $insertItem = $conn->prepare(
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );

    $reduceStock = $conn->prepare(
        "UPDATE products
         SET stock = stock - ?
         WHERE id = ? AND stock >= ?"
    );

    foreach ($lines as $line) {

        $insertItem->bind_param("iiid", $orderId, $line['product_id'], $line['qty'], $line['price']);
        $insertItem->execute();

        $reduceStock->bind_param("iii", $line['qty'], $line['product_id'], $line['qty']);
        $reduceStock->execute();

        if ($reduceStock->affected_rows !== 1) {
            throw new OrderException("Stock changed while you were checking out. Please review your cart.");
        }
    }

    $conn->commit();

} catch (OrderException $e) {

    // A problem the customer can understand and fix
    $conn->rollback();
    checkout_fail($e->getMessage());

} catch (Throwable $e) {

    // Anything else: log the detail, show a generic message
    $conn->rollback();
    error_log("TechCart place_order failed: " . $e->getMessage());
    checkout_fail("Something went wrong while placing your order. Please try again.");
}

// Success: empty the cart and show the confirmation page
unset($_SESSION['cart']);
$_SESSION['last_order_id'] = $orderId;

header("Location: order_success.php");
exit;
