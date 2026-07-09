<?php
session_start();

require_once 'config/database.php';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Process only when form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Customer Information
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    // Calculate Order Total
    $total = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {

        $id = (int)$id;

        $result = mysqli_query($conn, "SELECT price FROM products WHERE id = $id");

        if ($product = mysqli_fetch_assoc($result)) {

            $total += $product['price'] * $qty;

        }
    }

    // Check if user already exists
    $userQuery = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE email = '$email'"
    );

    if (mysqli_num_rows($userQuery) > 0) {

    // Existing user
    $user = mysqli_fetch_assoc($userQuery);
    $user_id = $user['id'];

    } else {

    // Create new user
    $insertUser = mysqli_query(
        $conn,
        "INSERT INTO users
        (
            full_name,
            email,
            password,
            phone,
            address
        )
        VALUES
        (
            '$name',
            '$email',
            '',
            '$phone',
            '$address'
        )"
    );

    if (!$insertUser) {
        die("User Insert Error: " . mysqli_error($conn));
    }

    $user_id = mysqli_insert_id($conn);
}
    // Save Order
    $query = "
        INSERT INTO orders
        (
            user_id,
            customer_name,
            email,
            phone,
            address,
            total,
            order_status
        )
        VALUES
        (
            '$user_id',
            '$name',
            '$email',
            '$phone',
            '$address',
            '$total',
            'Pending'
        )
    ";

    if (!mysqli_query($conn, $query)) {
        die("Order Error: " . mysqli_error($conn));
    }

    // Get Order ID
    $order_id = mysqli_insert_id($conn);

    // Save Order Items & Update Stock
foreach ($_SESSION['cart'] as $id => $qty) {

    $id = (int)$id;

    $result = mysqli_query(
        $conn,
        "SELECT price, stock
        FROM products
        WHERE id = $id"
    );

    if ($product = mysqli_fetch_assoc($result)) {

        // Prevent ordering more than available stock
        if ($qty > $product['stock']) {

            die("Error: Not enough stock available for this product.");

        }

        $price = $product['price'];

        // Save order item
        if (!mysqli_query(
            $conn,
            "INSERT INTO order_items
            (order_id, product_id, quantity, price)
            VALUES
            ($order_id, $id, $qty, $price)"
        )) {
            die("Order Item Error: " . mysqli_error($conn));
        }

        // Reduce stock
        if (!mysqli_query(
            $conn,
            "UPDATE products
            SET stock = stock - $qty
            WHERE id = $id"
        )) {
            die("Stock Update Error: " . mysqli_error($conn));
        }

    }

}

    // Empty Cart
    unset($_SESSION['cart']);

    // Redirect
    header("Location: order_success.php");
    exit;

} else {

    header("Location: checkout.php");
    exit;

}
?>