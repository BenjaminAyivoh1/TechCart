<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category_id = (int)$_POST['category_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $imageName = time() . "_" . basename($image);

    move_uploaded_file(
        $tmp,
        "../assets/images/" . $imageName
    );

    $sql = "
        INSERT INTO products
        (category_id, product_name, description, price, stock, image)
        VALUES
        (
            '$category_id',
            '$product_name',
            '$description',
            '$price',
            '$stock',
            '$imageName'
        )
    ";

    mysqli_query($conn, $sql);

    header("Location: products.php");
    exit;
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
class="form-control"
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
required></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label class="form-label">
Price
</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label">
Stock
</label>

<input
type="number"
name="stock"
class="form-control"
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

<option value="<?php echo $category['id']; ?>">

<?php echo $category['category_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mt-3">

<label class="form-label">
Product Image
</label>

<input
type="file"
name="image"
class="form-control"
accept="image/*"
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