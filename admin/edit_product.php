<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

// Check if an ID was provided
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int) $_GET['id'];

// Get product
$productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

if (mysqli_num_rows($productQuery) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($productQuery);

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");

// ==========================
// UPDATE PRODUCT
// ==========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $price        = mysqli_real_escape_string($conn, $_POST['price']);
    $stock        = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id  = (int) $_POST['category_id'];

    // Keep the current image unless a new one is uploaded
    $image = $product['image'];

    if (!empty($_FILES['image']['name'])) {

        $image = time() . "_" . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/images/" . $image
        );
    }

    $updateQuery = "
        UPDATE products
        SET
            category_id = '$category_id',
            product_name = '$product_name',
            description = '$description',
            price = '$price',
            stock = '$stock',
            image = '$image'
        WHERE id = $id
    ";

    if (mysqli_query($conn, $updateQuery)) {

        header("Location: products.php");
        exit();

    } else {

        die("Update failed: " . mysqli_error($conn));

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

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label>Product Name</label>

<input
type="text"
name="product_name"
class="form-control"
value="<?php echo $product['product_name']; ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="5"
required><?php echo $product['description']; ?></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label>Price</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
value="<?php echo $product['price']; ?>"
required>

</div>

<div class="col-md-6">

<label>Stock</label>

<input
type="number"
name="stock"
class="form-control"
value="<?php echo $product['stock']; ?>"
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
value="<?php echo $cat['id']; ?>"

<?php
if($cat['id']==$product['category_id']){
echo "selected";
}
?>

>

<?php echo $cat['category_name']; ?>

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
src="../assets/images/<?php echo $product['image']; ?>"
width="150"
class="rounded shadow">

</div>

<div class="mt-3">

<label>

Upload New Image (optional)

</label>

<input
type="file"
name="image"
class="form-control">

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