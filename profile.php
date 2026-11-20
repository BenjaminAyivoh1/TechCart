<?php
session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id = $user_id"
);

$user = mysqli_fetch_assoc($query);

$pageTitle = 'My Profile';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow border-0 rounded-4">

<div class="card-body p-5">

<h1 class="fw-bold mb-4">

<i class="bi bi-person-circle text-primary"></i>

My Profile

</h1>

<form action="update_profile.php" method="POST">

<div class="mb-3">

<label for="f-profile-full-name" class="form-label">

Full Name

</label>

<input autocomplete="name" id="f-profile-full-name"
type="text"
name="full_name"
class="form-control form-control-lg"
value="<?php echo htmlspecialchars($user['full_name']); ?>"
required>

</div>

<div class="mb-3">

<label for="f-profile-field" class="form-label">

Email

</label>

<input id="f-profile-field"
type="email"
class="form-control form-control-lg"
value="<?php echo htmlspecialchars($user['email']); ?>"
readonly>

</div>

<div class="mb-3">

<label for="f-profile-phone" class="form-label">

Phone

</label>

<input autocomplete="tel" id="f-profile-phone"
type="tel" inputmode="tel"
name="phone"
class="form-control form-control-lg"
value="<?php echo htmlspecialchars($user['phone']); ?>">

</div>

<div class="mb-4">

<label for="f-profile-address" class="form-label">

Address

</label>

<textarea autocomplete="street-address" id="f-profile-address"
name="address"
rows="4"
class="form-control"><?php echo htmlspecialchars($user['address']); ?></textarea>

</div>

<button class="btn btn-primary btn-lg">

<i class="bi bi-save"></i>

Save Changes

</button>

</form>

<hr class="my-5">

<h3>

Change Password

</h3>

<form action="change_password.php" method="POST">

<div class="mb-3">

<label for="f-profile-current-password">

Current Password

</label>

<input autocomplete="current-password" id="f-profile-current-password"
type="password"
name="current_password"
class="form-control">

</div>

<div class="mb-3">

<label for="f-profile-new-password">

New Password

</label>

<input autocomplete="new-password" minlength="8" id="f-profile-new-password"
type="password"
name="new_password"
class="form-control">

</div>

<div class="mb-4">

<label for="f-profile-confirm-password">

Confirm Password

</label>

<input autocomplete="new-password" minlength="8" id="f-profile-confirm-password"
type="password"
name="confirm_password"
class="form-control">

</div>

<button class="btn btn-success">

Update Password

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include 'includes/footer.php'; ?>