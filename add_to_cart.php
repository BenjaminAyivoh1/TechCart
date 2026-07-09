<?php

session_start();

$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($product_id > 0) {

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {

        $_SESSION['cart'][$product_id]++;

    } else {

        $_SESSION['cart'][$product_id] = 1;

    }

}

header("Location: cart.php");
exit;

?>