<?php
session_start();

require_once 'config/database.php';

$id     = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id > 0 && isset($_SESSION['cart'][$id])) {

    if ($action === "increase") {

        $stmt = $conn->prepare("SELECT product_name, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) {

            // Product was removed from the store
            unset($_SESSION['cart'][$id]);

        } elseif ($_SESSION['cart'][$id] + 1 > (int)$product['stock']) {

            $_SESSION['cart_notice'] = "Only " . (int)$product['stock'] . " of " . $product['product_name'] . " in stock.";

        } else {

            $_SESSION['cart'][$id]++;
        }

    } elseif ($action === "decrease") {

        $_SESSION['cart'][$id]--;

        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

header("Location: cart.php");
exit;
