<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

// Make failed queries throw, so we can react to foreign-key errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Deleting changes data, so it must be a POST with a valid CSRF token.
// A plain link (GET) could be triggered by any page the admin visits.
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: products.php");
    exit;
}

$token = $_POST['csrf'] ?? '';

if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
    $_SESSION['admin_error'] = "Invalid request. Please reload the page and try again.";
    header("Location: products.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

// Remember the image filename before the row is gone
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit;
}

// Delete the database row FIRST. If it fails, the image must stay.
try {

    $delete = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete->bind_param("i", $id);
    $delete->execute();

} catch (mysqli_sql_exception $e) {

    if ((int)$e->getCode() === 1451) {
        // Foreign key: the product appears in past orders
        $_SESSION['admin_error'] = "This product appears in existing orders, so it can't be deleted. "
            . "Set its stock to 0 instead.";
    } else {
        error_log("TechCart delete_product failed: " . $e->getMessage());
        $_SESSION['admin_error'] = "Could not delete the product.";
    }

    header("Location: products.php");
    exit;
}

// The row is gone, so now it is safe to remove the image file
if (!empty($product['image'])) {

    $path = __DIR__ . "/../assets/images/" . basename($product['image']);

    if (is_file($path)) {
        unlink($path);
    }
}

header("Location: products.php");
exit;
