<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];

$newUsers = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
        FROM users
        WHERE MONTH(created_at)=MONTH(CURRENT_DATE())
        AND YEAR(created_at)=YEAR(CURRENT_DATE())"
    )
)['total'];

$buyers = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(DISTINCT user_id) AS total
        FROM orders"
    )
)['total'];

$totalOrders = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
        FROM orders"
    )
)['total'];

/*
|--------------------------------------------------------------------------
| Users Query
|--------------------------------------------------------------------------
*/

$query = "
SELECT
    users.*,
    COUNT(orders.id) AS total_orders
FROM users
LEFT JOIN orders
ON users.id = orders.user_id
WHERE
    users.full_name LIKE '%$search%'
    OR users.email LIKE '%$search%'
    OR users.phone LIKE '%$search%'
GROUP BY users.id
ORDER BY users.created_at DESC
";

$users = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Users</title>

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

.badge{
    font-size:14px;
}

.table img{
    border-radius:50%;
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

<a href="users.php" class="active">

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

<h2 class="fw-bold mb-1">

Users

</h2>

<p class="text-muted">

Manage registered customers

</p>

</div>

</div>

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="card bg-primary text-white shadow-sm">

<div class="card-body">

<i class="bi bi-people fs-1"></i>

<h6 class="mt-3">Total Users</h6>

<h2><?php echo $totalUsers; ?></h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-success text-white shadow-sm">

<div class="card-body">

<i class="bi bi-person-check fs-1"></i>

<h6 class="mt-3">Customers</h6>

<h2><?php echo $buyers; ?></h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-warning text-dark shadow-sm">

<div class="card-body">

<i class="bi bi-calendar-event fs-1"></i>

<h6 class="mt-3">New This Month</h6>

<h2><?php echo $newUsers; ?></h2>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-dark text-white shadow-sm">

<div class="card-body">

<i class="bi bi-bag-check fs-1"></i>

<h6 class="mt-3">Total Orders</h6>

<h2><?php echo $totalOrders; ?></h2>

</div>

</div>

</div>

</div>

<div class="card mb-4 shadow-sm">

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search by name, email or phone..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-2 d-grid">

<button class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

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

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Orders</th>
<th>Status</th>
<th>Joined</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php if(mysqli_num_rows($users)>0){ ?>

<?php while($user=mysqli_fetch_assoc($users)){ ?>

<tr>

<td>

#<?php echo $user['id']; ?>

</td>

<td>
<div class="d-flex align-items-center">

<div
class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3"
style="width:45px;height:45px;">

<i class="bi bi-person-fill"></i>

</div>

<div>

<strong>

<?php echo htmlspecialchars($user['full_name']); ?>

</strong>

</div>

</div>
</td>

<td>

<?php echo htmlspecialchars($user['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($user['phone']); ?>

</td>

<td>

<span class="badge bg-primary">

<?php echo $user['total_orders']; ?>

</span>

</td>

<td>

<?php

if($user['total_orders']==0){

echo "<span class='badge bg-secondary'>New</span>";

}elseif($user['total_orders']<5){

echo "<span class='badge bg-info text-dark'>Customer</span>";

}else{

echo "<span class='badge bg-success'>VIP</span>";

}

?>

</td>

<td>

<?php echo date("M d, Y",strtotime($user['created_at'])); ?>

</td>

<td>

<a
href="view_user.php?id=<?php echo $user['id']; ?>"
class="btn btn-outline-primary btn-sm">

<i class="bi bi-eye"></i>

View Profile

</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-people fs-1 text-muted"></i>

<h5 class="mt-3">

No customers matched your search.

Try another name or email.

</h5>

<p class="text-muted">

Try a different search term.

</p>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>