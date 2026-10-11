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

// Check if an ID was provided
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int) $_GET['id'];

// Get product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");

$error = "";

// Values shown in the form: the saved product, or what was just submitted
$old = [
    'product_name'   => (string)$product['product_name'],
    'description'    => (string)$product['description'],
    'price'          => (string)$product['price'],
    'discount_price' => (string)($product['discount_price'] ?? ''),
    'stock'          => (string)$product['stock'],
    'category_id'    => (string)($product['category_id'] ?? ''),
];

// ==========================
// UPDATE PRODUCT
// ==========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($old as $field => $unused) {
        $old[$field] = trim((string)($_POST[$field] ?? ''));
    }

    $price    = is_numeric($old['price']) ? (float)$old['price'] : -1;
    $stock    = filter_var($old['stock'], FILTER_VALIDATE_INT);
    $category = filter_var($old['category_id'], FILTER_VALIDATE_INT);

    // Discount is optional: blank removes the discount
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

        $check = $conn->prepare("SELECT id FROM categories WHERE id = ?");
        $check->bind_param("i", $category);
        $check->execute();

        if (!$check->get_result()->fetch_assoc()) {
            $error = "Choose a valid category.";
        }
    }

    // Keep the current image unless a new one is uploaded
    $image    = $product['image'];
    $newImage = null;

    $uploaded = ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

    if ($error === "" && $uploaded) {

        [$newImage, $imageError] = store_product_image($_FILES['image'], $imageDir);

        if ($imageError !== null) {
            $error = $imageError;
        } else {
            $image = $newImage;
        }
    }

    if ($error === "") {

        try {

            $update = $conn->prepare(
                "UPDATE products
                 SET category_id = ?, product_name = ?, description = ?,
                     price = ?, discount_price = ?, stock = ?, image = ?
                 WHERE id = ?"
            );

            $update->bind_param(
                "issddisi",
                $category,
                $old['product_name'],
                $old['description'],
                $price,
                $discount,
                $stock,
                $image,
                $id
            );

            $update->execute();

            // The old image is only removed once the new one is safely saved
            if ($newImage !== null) {
                delete_product_image($product['image'], $imageDir);
            }

            header("Location: products.php");
            exit;

        } catch (mysqli_sql_exception $e) {

            // Not saved: don't keep the new upload
            if ($newImage !== null) {
                delete_product_image($newImage, $imageDir);
            }

            error_log("TechCart edit_product failed: " . $e->getMessage());
            $error = "Could not update the product. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Product</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:#f5f7fb;
    font-family:Poppins,sans-serif;
}

.sidebar{
    position:fixed;
    left:0;
    top:0;
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
    text-decoration:none;
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

<h2><i class="bi bi-bag-fill"></i> TechCart</h2>

<a href="index.php">Dashboard</a>
<a href="products.php">Products</a>
<a href="orders.php">Orders</a>
<a href="users.php">Users</a>
<a href="logout.php">Logout</a>

</div>

<div class="main">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>Edit Product</h3>

</div>

<div class="card-body">

<?php if ($error !== ""): ?>

<div class="alert alert-danger">

<?php echo e($error); ?>

</div>

<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label>Product Name</label>

<input
type="text"
name="product_name"
maxlength="150"
class="form-control"
value="<?php echo e($old['product_name']); ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="5"
required><?php echo e($old['description']); ?></textarea>

</div>

<div class="row">

<div class="col-md-4">

<label>Price</label>

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

<label>Discount Price (optional)</label>

<input
type="number"
step="0.01"
min="0"
name="discount_price"
class="form-control"
value="<?php echo e($old['discount_price']); ?>">

</div>

<div class="col-md-4">

<label>Stock</label>

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

<label>Category</label>

<select
name="category_id"
class="form-select">

<?php while($cat=mysqli_fetch_assoc($categories)){ ?>

<option
value="<?php echo (int)$cat['id']; ?>"
<?php if ((string)$cat['id'] === $old['category_id']) echo "selected"; ?>>

<?php echo e($cat['category_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="mt-4">

<label>

Current Image

</label>

<br>

<img
src="../assets/images/<?php echo e($product['image']); ?>"
width="150"
class="rounded shadow"
alt="<?php echo e($product['product_name']); ?>">

</div>

<div class="mt-3">

<label>

Upload New Image (optional, JPG, PNG or WebP, max 2 MB)

</label>

<input
type="file"
name="image"
class="form-control"
accept="image/jpeg,image/png,image/webp">

</div>

<div class="mt-4">

<button
class="btn btn-success">

Update Product

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
