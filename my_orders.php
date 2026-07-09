<?php
session_start();

require_once 'config/database.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT *
    FROM orders
    WHERE user_id = '$user_id'
    ORDER BY id DESC"
);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">

<i class="bi bi-bag-check-fill text-primary"></i>

My Orders

</h2>

</div>

<?php if(mysqli_num_rows($query) == 0){ ?>

<div class="alert alert-info">

You haven't placed any orders yet.

</div>

<?php } else { ?>

<div class="card shadow border-0">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-dark">

<tr>

<th>Order #</th>

<th>Date</th>

<th>Total</th>

<th>Status</th>

<th></th>

</tr>

</thead>

<tbody>

<?php while($order = mysqli_fetch_assoc($query)){ ?>

<tr>

<td>

#<?php echo $order['id']; ?>

</td>

<td>

<?php echo date("M d, Y", strtotime($order['created_at'])); ?>

</td>

<td>

$<?php echo number_format($order['total'],2); ?>

</td>

<td>

<?php echo htmlspecialchars($order['order_status']); ?>

</td>

<td>

<a href="order_details.php?id=<?php echo $order['id']; ?>"
class="btn btn-primary rounded-pill px-4">
    View
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php } ?>

</div>

<?php include 'includes/footer.php'; ?>