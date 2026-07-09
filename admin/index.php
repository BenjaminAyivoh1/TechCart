<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

// Statistics
$productCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];

$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
$categoryCount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories")
)['total'];

$pendingOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Pending'")
)['total'];

$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];

$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS revenue FROM orders"))['revenue'];

// Products per Category
$categoryChart = mysqli_query($conn, "
SELECT categories.category_name,
COUNT(products.id) AS total
FROM categories
LEFT JOIN products
ON categories.id = products.category_id
GROUP BY categories.id
");

$labels = [];
$data = [];

while($row = mysqli_fetch_assoc($categoryChart)){
    $labels[] = $row['category_name'];
    $data[] = $row['total'];
}

if (!$revenue) {
    $revenue = 0;
}

$recentOrders = mysqli_query($conn,
"SELECT * FROM orders
ORDER BY id DESC
LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>TechCart Admin</title>

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

.stat-card{
    color:white;
}

</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<h1 class="mb-4">
Welcome,
<?php echo $_SESSION['admin']; ?> 👋
</h1>

<div class="row g-4">

    <div class="col-lg-4 col-md-6">
        <div class="card bg-primary text-white shadow-sm border-0">
            <div class="card-body">
                <i class="bi bi-box-seam fs-1"></i>
                <h5 class="mt-3">Products</h5>
                <h2><?php echo $productCount; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card bg-success text-white shadow-sm border-0">
            <div class="card-body">
                <i class="bi bi-receipt fs-1"></i>
                <h5 class="mt-3">Orders</h5>
                <h2><?php echo $orderCount; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card bg-warning text-dark shadow-sm border-0">
            <div class="card-body">
                <i class="bi bi-people fs-1"></i>
                <h5 class="mt-3">Users</h5>
                <h2><?php echo $userCount; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card bg-info text-white shadow-sm border-0">
            <div class="card-body">
                <i class="bi bi-tags fs-1"></i>
                <h5 class="mt-3">Categories</h5>
                <h2><?php echo $categoryCount; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card bg-danger text-white shadow-sm border-0">
            <div class="card-body">
                <i class="bi bi-hourglass-split fs-1"></i>
                <h5 class="mt-3">Pending Orders</h5>
                <h2><?php echo $pendingOrders; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card bg-dark text-white shadow-sm border-0">
            <div class="card-body">
                <i class="bi bi-currency-dollar fs-1"></i>
                <h5 class="mt-3">Revenue</h5>
                <h2>$<?php echo number_format($revenue, 2); ?></h2>
            </div>
        </div>
    </div>

</div>

<div class="card mt-5 shadow-sm border-0">

    <div class="card-header bg-white">

        <h4 class="mb-0">
            Recent Orders
        </h4>

    </div>
    <div class="row mt-4">

    <div class="col-lg-6 mb-4">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-pie-chart-fill"></i>
                    Products by Category
                </h5>

            </div>

            <div class="card-body">

                <canvas id="categoryChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-6 mb-4">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-fill"></i>
                    Store Overview
                </h5>

            </div>

            <div class="card-body">

                <canvas id="overviewChart"></canvas>

            </div>

        </div>

    </div>

</div>

    <div class="card-body">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Customer</th>

                    <th>Total</th>

                    <th>Status</th>

                    <th>Date</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

                <?php while($order = mysqli_fetch_assoc($recentOrders)){ ?>

                <tr>

                    <td>#<?php echo $order['id']; ?></td>

                    <td><?php echo $order['customer_name']; ?></td>

                    <td>$<?php echo number_format($order['total'],2); ?></td>

                    <td>

                        <?php

                        $status = $order['order_status'];

                        $badge = "secondary";

                        if ($status == "Pending") {
                            $badge = "warning text-dark";
                        } elseif ($status == "Processing") {
                            $badge = "primary";
                        } elseif ($status == "Shipped") {
                            $badge = "info";
                        } elseif ($status == "Delivered") {
                            $badge = "success";
                        } elseif ($status == "Cancelled") {
                            $badge = "danger";
                        }

                        ?>

                        <span class="badge bg-<?php echo $badge; ?>">
                            <?php echo $status; ?>
                        </span>

                    </td>

                    <td>
                        <?php echo date("M d, Y", strtotime($order['created_at'])); ?>
                    </td>

                    <td>

                        <a href="view_order.php?id=<?php echo $order['id']; ?>"
                        class="btn btn-outline-primary btn-sm">

                            <i class="bi bi-eye"></i> View

                        </a>

                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</div>
<script>

const categoryCtx = document.getElementById('categoryChart');

new Chart(categoryCtx, {

    type: 'doughnut',

    data: {

        labels: <?php echo json_encode($labels); ?>,

        datasets: [{

            data: <?php echo json_encode($data); ?>,

            backgroundColor: [

                '#0d6efd',
                '#198754',
                '#ffc107',
                '#dc3545',
                '#20c997',
                '#6f42c1',
                '#fd7e14',
                '#6610f2'

            ]

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                position: 'bottom'

            }

        }

    }

});

const overviewCtx = document.getElementById('overviewChart');

new Chart(overviewCtx, {

    type: 'bar',

    data: {

        labels: [

            'Products',
            'Orders',
            'Users',
            'Categories',
            'Pending'

        ],

        datasets: [{

            label: 'System Statistics',

            data: [

                <?php echo $productCount; ?>,
                <?php echo $orderCount; ?>,
                <?php echo $userCount; ?>,
                <?php echo $categoryCount; ?>,
                <?php echo $pendingOrders; ?>

            ]

        }]

    },

    options: {

        responsive: true,

        scales: {

            y: {

                beginAtZero: true

            }

        }

    }

});

</script>
</body>

</html>