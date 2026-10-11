<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";
require_once "../includes/functions.php";

// Make failed queries throw so we can clean up after them
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$imageDir = __DIR__ . "/../assets/images";

$error = "";

$old = [
    'product_name'   => '',
    'description'    => '',
    'price'          => '',
    'discount_price' => '',
    'stock'          => '',
    'category_id'    => '',
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Keep what was typed so a mistake doesn't lose everything
    foreach ($old as $field => $unused) {
        $old[$field] = trim((string)($_POST[$field] ?? ''));
    }

    $price    = is_numeric($old['price']) ? (float)$old['price'] : -1;
    $stock    = filter_var($old['stock'], FILTER_VALIDATE_INT);
    $category = filter_var($old['category_id'], FILTER_VALIDATE_INT);

    // Discount is optional: blank means "no discount"
    $discount = null;

    if ($old['discount_price'] !== '') {
        $discount = is_numeric($old['discount_price']) ? (float)$old['discount_price'] : -1;
    }

    if ($old['product_name'] === '' || mb_strlen($old['product_name']) > 150) {
        $error = "Enter a product name (150 characters maximum).";
    } elseif ($old['description'] === '') {
        $error = "Enter a description.";
    } elseif ($price < 0 || $price > 99999999.99) {
        $error = "Enter a valid price.";
    } elseif ($discount !== null && ($discount <= 0 || $discount >= $price)) {
        $error = "The discount price must be more than 0 and less than the normal price, or left blank.";
    } elseif ($stock === false || $stock < 0) {
        $error = "Stock must be a whole number, 0 or more.";
    } elseif ($category === false) {
        $error = "Choose a category.";
    } else {

        // The category must really exist
        $check = $conn->prepare("SELECT id FROM categories WHERE id = ?");
        $check->bind_param("i", $category);
        $check->execute();

        if (!$check->get_result()->fetch_assoc()) {
            $error = "Choose a valid category.";
        }
    }

    // Image last, so a rejected form never leaves a stray file behind
    if ($error === "") {
        [$imageName, $imageError] = store_product_image($_FILES['image'] ?? [], $imageDir);

        if ($imageError !== null) {
            $error = $imageError;
        }
    }

    if ($error === "") {

        try {

            $stmt = $conn->prepare(
                "INSERT INTO products
                    (category_id, product_name, description, price, discount_price, stock, image)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "issddis",
                $category,
                $old['product_name'],
                $old['description'],
                $price,
                $discount,
                $stock,
                $imageName
            );

            $stmt->execute();

            header("Location: products.php");
            exit;

        } catch (mysqli_sql_exception $e) {

            // The product was not saved, so don't keep its image either
            delete_product_image($imageName, $imageDir);

            error_log("TechCart add_product failed: " . $e->getMessage());
            $error = "Could not save the product. Please try again.";
        }
    }
}

// Load categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Product</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:#f5f7fb;
    font-family:Poppins,sans-serif;
}

.sidebar{
    position:fixed;
    top:0;
    left:0;
    width:260px;
    height:100vh;
    background:#111827;
    color:white;
    padding:30px;
}

.sidebar h2{
    margin-bottom:40px;
}

.sidebar a{
    display:block;
    color:#d1d5db;
    text-decoration:none;
    padding:12px 0;
    font-size:18px;
}

.sidebar a:hover{
    color:white;
}

.main{
    margin-left:260px;
    padding:40px;
}

.card{
    border:none;
    border-radius:18px;
}

</style>

</head>

<body>

<div class="sidebar">

<h2>
<i class="bi bi-bag-fill"></i>
TechCart
</h2>

<a href="index.php">
<i class="bi bi-speedometer2"></i>
Dashboard
</a>

<a href="products.php">
<i class="bi bi-box-seam"></i>
Products
</a>

<a href="orders.php">
<i class="bi bi-receipt"></i>
Orders
</a>

<a href="users.php">
<i class="bi bi-people"></i>
Users
</a>

<a href="logout.php">
<i class="bi bi-box-arrow-right"></i>
Logout
</a>

</div>

<div class="main">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="mb-0">
Add New Product
</h3>

</div>

<div class="card-body">

<?php if ($error !== ""): ?>

<div class="alert alert-danger">

<?php echo e($error); ?>

</div>

<?php endif; ?>

<form
method="POST"
enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">
Product Name
</label>

<input
type="text"
name="product_name"
maxlength="150"
class="form-control"
value="<?php echo e($old['product_name']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">
Description
</label>

<textarea
name="description"
rows="4"
class="form-control"
required><?php echo e($old['description']); ?></textarea>

</div>

<div class="row">

<div class="col-md-4">

<label class="form-label">
Price
</label>

<input
type="number"
step="0.01"
min="0"
name="price"
class="form-control"
value="<?php echo e($old['price']); ?>"
required>

</div>

<div class="col-md-4">

<label class="form-label">
Discount Price (optional)
</label>

<input
type="number"
step="0.01"
min="0"
name="discount_price"
class="form-control"
value="<?php echo e($old['discount_price']); ?>">

</div>

<div class="col-md-4">

<label class="form-label">
Stock
</label>

<input
type="number"
min="0"
name="stock"
class="form-control"
value="<?php echo e($old['stock']); ?>"
required>

</div>

</div>

<div class="mt-3">

<label class="form-label">
Category
</label>

<select
name="category_id"
class="form-select"
required>

<option value="">
Select Category
</option>

<?php while($category = mysqli_fetch_assoc($categories)){ ?>

<option
value="<?php echo (int)$category['id']; ?>"
<?php if ((string)$category['id'] === $old['category_id']) echo "selected"; ?>>

<?php echo e($category['category_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="mt-3">

<label class="form-label">
Product Image (JPG, PNG or WebP, max 2 MB)
</label>

<input
type="file"
name="image"
class="form-control"
accept="image/jpeg,image/png,image/webp"
required>

</div>

<div class="mt-4">

<button
type="submit"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Save Product

</button>

<a
href="products.php"
class="btn btn-secondary">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

</body>

</html>
