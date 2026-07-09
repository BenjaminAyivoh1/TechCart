<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Search & Filters
|--------------------------------------------------------------------------
*/

$search = "";
$categoryFilter = "";
$statusFilter = "";
$sort = "newest";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

if(isset($_GET['category'])){
    $categoryFilter = mysqli_real_escape_string(
        $conn,
        $_GET['category']
    );
}

if(isset($_GET['status'])){
    $statusFilter = mysqli_real_escape_string(
        $conn,
        $_GET['status']
    );
}

if(isset($_GET['sort'])){
    $sort = $_GET['sort'];
}

/*
|--------------------------------------------------------------------------
| Product Query
|--------------------------------------------------------------------------
*/

$query = "
SELECT
    products.*,
    categories.category_name
FROM products
LEFT JOIN categories
ON products.category_id = categories.id
WHERE
(
    products.product_name LIKE '%$search%'
    OR
    categories.category_name LIKE '%$search%'
    OR
    products.description LIKE '%$search%'
    OR
    products.price LIKE '%$search%'
)
";

if($categoryFilter != ""){

    $query .= "
    AND categories.category_name='$categoryFilter'
    ";

}

if($statusFilter=="instock"){

    $query .= "
    AND products.stock > 5
    ";

}

elseif($statusFilter=="low"){

    $query .= "
    AND products.stock BETWEEN 1 AND 5
    ";

}

elseif($statusFilter=="out"){

    $query .= "
    AND products.stock = 0
    ";

}

switch($sort){

    case "oldest":

        $query .= " ORDER BY products.id ASC";

    break;

    case "price_high":

        $query .= " ORDER BY products.price DESC";

    break;

    case "price_low":

        $query .= " ORDER BY products.price ASC";

    break;

    case "stock":

        $query .= " ORDER BY products.stock DESC";

    break;

    default:

        $query .= " ORDER BY products.id DESC";

}

$products = mysqli_query($conn, $query);

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$productCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM products"
    )
)['total'];

$lowStock = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM products
        WHERE stock < 5
        AND stock > 0
        "
    )
)['total'];

$outStock = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM products
        WHERE stock = 0
        "
    )
)['total'];

/*
|--------------------------------------------------------------------------
| Total Inventory Value
|--------------------------------------------------------------------------
*/

$totalValue = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT SUM(price * stock) AS total
        FROM products
        "
    )
)['total'];

if (!$totalValue) {
    $totalValue = 0;
}

/*
|--------------------------------------------------------------------------
| Total Categories
|--------------------------------------------------------------------------
*/

$totalCategories = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM categories
        "
    )
)['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Manage Products</title>

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
    padding:12px 14px;
    margin-bottom:6px;
    border-radius:10px;
    transition:.25s;
    font-size:17px;
}

.sidebar a:hover{
    background:#2563eb;
    color:white;
}

.sidebar .active{
    background:#2563eb;
    color:white;
}

.main{
    margin-left:260px;
    padding:40px;
}

.card{
    border:none;
    border-radius:18px;
    transition:.25s;
}

.card:hover{
    transform:translateY(-3px);
}

.table img{
    border-radius:12px;
    object-fit:cover;
}

.table td{
    vertical-align:middle;
}

.stat-icon{
    font-size:34px;
    opacity:.85;
}

.page-title{
    font-weight:700;
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

<a href="products.php" class="active">

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

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="page-title">

Products

</h2>

<p class="text-muted mb-0">

Manage inventory, pricing and product availability.

</p>

</div>

<a
href="add_product.php"
class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Product

</a>

</div>

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="card bg-primary text-white shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>Total Products</h6>

<h2>

<?php echo $productCount; ?>

</h2>

</div>

<i class="bi bi-box-seam stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-warning text-dark shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>Low Stock</h6>

<h2>

<?php echo $lowStock; ?>

</h2>

</div>

<i class="bi bi-exclamation-triangle stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-danger text-white shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>Out of Stock</h6>

<h2>

<?php echo $outStock; ?>

</h2>

</div>

<i class="bi bi-x-circle stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-success text-white shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>Inventory Value</h6>

<h5>

$<?php echo number_format($totalValue,2); ?>

</h5>

</div>

<i class="bi bi-cash-stack stat-icon"></i>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-dark text-white shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>Categories</h6>

<h2>

<?php echo $totalCategories; ?>

</h2>

</div>

<i class="bi bi-tags stat-icon"></i>

</div>

</div>

</div>

</div>

</div>

<div class="card shadow-sm mb-4">

<div class="card-body">

<form method="GET">

<div class="row g-3">

<div class="col-lg-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search products..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-lg-2">

<select
name="category"
class="form-select">

<option value="">

All Categories

</option>

<?php

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name"
);

while($category = mysqli_fetch_assoc($categories)){

?>

<option
value="<?php echo $category['category_name']; ?>"
<?php if($categoryFilter == $category['category_name']) echo "selected"; ?>>

<?php echo $category['category_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-lg-2">

<select
name="status"
class="form-select">

<option value="">

All Stock

</option>

<option
value="instock"
<?php if($statusFilter=="instock") echo "selected"; ?>>

In Stock

</option>

<option
value="low"
<?php if($statusFilter=="low") echo "selected"; ?>>

Low Stock

</option>

<option
value="out"
<?php if($statusFilter=="out") echo "selected"; ?>>

Out Of Stock

</option>

</select>

</div>

<div class="col-lg-2">

<select
name="sort"
class="form-select">

<option
value="newest"
<?php if($sort=="newest") echo "selected"; ?>>

Newest

</option>

<option
value="oldest"
<?php if($sort=="oldest") echo "selected"; ?>>

Oldest

</option>

<option
value="price_high"
<?php if($sort=="price_high") echo "selected"; ?>>

Highest Price

</option>

<option
value="price_low"
<?php if($sort=="price_low") echo "selected"; ?>>

Lowest Price

</option>

<option
value="stock"
<?php if($sort=="stock") echo "selected"; ?>>

Highest Stock

</option>

</select>

</div>

<div class="col-lg-2 d-grid">

<button
class="btn btn-primary">

<i class="bi bi-funnel-fill"></i>

Filter

</button>

</div>

</div>

<div class="mt-3">

<a
href="products.php"
class="btn btn-outline-secondary">

<i class="bi bi-arrow-clockwise"></i>

Reset Filters

</a>

</div>

</form>

</div>

</div>

<div class="card shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Product ID</th>
<th>Image</th>
<th>Product</th>
<th>Category</th>
<th>Price</th>
<th>Stock</th>
<th>Status</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($products) > 0){

while($product = mysqli_fetch_assoc($products)){

?>

<tr>

<td>

<strong>

PRD-<?php echo str_pad($product['id'],4,"0",STR_PAD_LEFT); ?>

</strong>

</td>

<td width="100">

<?php

$image="../assets/images/".$product['image'];

if(
!empty($product['image'])
&& file_exists($image)
){

?>

<img
src="<?php echo $image; ?>"
width="80"
height="80">

<?php }else{ ?>

<div
class="bg-light border rounded d-flex justify-content-center align-items-center"
style="width:80px;height:80px;">

<i class="bi bi-image text-secondary fs-3"></i>

</div>

<?php } ?>

</td>

<td>

<strong>

<?php echo htmlspecialchars($product['product_name']); ?>

</strong>

<br>

<small class="text-muted">

<?php

echo substr(
htmlspecialchars($product['description']),
0,
60
);

?>

...

</small>

</td>

<td>

<span class="badge bg-info">

<?php echo htmlspecialchars($product['category_name']); ?>

</span>

</td>

<td>

<strong>

$<?php echo number_format($product['price'],2); ?>

</strong>

</td>

<td>

<?php echo $product['stock']; ?>

</td>

<td>

<?php

if($product['stock']==0){

echo "<span class='badge bg-danger'><i class='bi bi-x-circle'></i> Out of Stock</span>";

}elseif($product['stock']<5){

echo "<span class='badge bg-warning text-dark'><i class='bi bi-exclamation-circle'></i> Low Stock</span>";

}else{

echo "<span class='badge bg-success'><i class='bi bi-check-circle'></i> In Stock</span>";

}

?>

</td>

<td>

<div class="btn-group">

<a
href="edit_product.php?id=<?php echo $product['id']; ?>"
class="btn btn-outline-warning btn-sm"
title="Edit">

<i class="bi bi-pencil"></i>

</a>

<a
href="delete_product.php?id=<?php echo $product['id']; ?>"
class="btn btn-outline-danger btn-sm"
onclick="return confirm('Delete this product permanently?')"
title="Delete">

<i class="bi bi-trash"></i>

</a>

</div>

</td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-box-seam display-4 text-muted"></i>

<h4 class="mt-3">

No Products Found

</h4>

<p class="text-muted">

No products match your current filters.

</p>

<div class="mt-3">

<a
href="products.php"
class="btn btn-outline-secondary me-2">

<i class="bi bi-arrow-clockwise"></i>

Reset Filters

</a>

<a
href="add_product.php"
class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Product

</a>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>

</html>