<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int)$_GET['id'];

// Get image filename
$result = mysqli_query($conn, "SELECT image FROM products WHERE id = $id");

if ($product = mysqli_fetch_assoc($result)) {

    if (!empty($product['image'])) {

        $path = "../assets/images/" . $product['image'];

        if (file_exists($path)) {
            unlink($path);
        }
    }
}

// Delete product
mysqli_query($conn, "DELETE FROM products WHERE id = $id");

header("Location: products.php");
exit;
?>