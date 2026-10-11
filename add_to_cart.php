<?php
session_start();

require_once 'config/database.php';

// The product page sends id + quantity; the shop grid links send id only.
$productId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
$quantity  = (int)($_POST['quantity'] ?? $_GET['quantity'] ?? 1);

if ($quantity < 1) {
    $quantity = 1;
}

if ($productId > 0) {

    $stmt = $conn->prepare("SELECT product_name, stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {

        $_SESSION['cart_notice'] = "That product no longer exists.";

    } elseif ((int)$product['stock'] < 1) {

        $_SESSION['cart_notice'] = $product['product_name'] . " is out of stock.";

    } else {

        $inCart = (int)($_SESSION['cart'][$productId] ?? 0);
        $wanted = $inCart + $quantity;
        $stock  = (int)$product['stock'];

        // Never let the cart hold more than we have
        if ($wanted > $stock) {
            $wanted = $stock;
            $_SESSION['cart_notice'] = "Only " . $stock . " of " . $product['product_name'] . " in stock. Quantity adjusted.";
        }

        $_SESSION['cart'][$productId] = $wanted;
    }
}

header("Location: cart.php");
exit;
