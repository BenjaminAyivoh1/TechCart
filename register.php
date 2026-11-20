<?php
session_start();

require_once 'config/database.php';
require_once 'includes/functions.php';

$error = "";
$success = "";

$full_name = "";
$email = "";
$phone = "";
$address = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($full_name === '' || mb_strlen($full_name) > 100) {

        $error = "Please enter your name (100 characters maximum).";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {

        $error = "Please enter a valid email address.";

    } elseif (mb_strlen($phone) > 20) {

        $error = "Phone number is too long (20 characters maximum).";

    } elseif (strlen($password) < 8) {

        $error = "Password must be at least 8 characters.";

    } elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } else {

        try {

            $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();

            if ($check->get_result()->fetch_assoc()) {

                $error = "An account with this email already exists.";

            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insert = $conn->prepare(
                    "INSERT INTO users (full_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)"
                );
                $insert->bind_param("sssss", $full_name, $email, $hashedPassword, $phone, $address);
                $insert->execute();

                $_SESSION['success'] = "Registration successful! Please log in.";

                header("Location: login.php");
                exit;
            }

        } catch (Throwable $e) {

            error_log("TechCart register failed: " . $e->getMessage());
            $error = "Registration failed. Please try again.";
        }
    }
}

$pageTitle = 'Create Account';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <i class="bi bi-person-plus-fill display-3 text-primary"></i>

                        <h1 class="fw-bold mt-3">
                            Create Account
                        </h1>

                        <p class="text-muted">
                            Join TechCart and enjoy faster checkout and order tracking.
                        </p>

                    </div>

                    <?php if($error != ""){ ?>

                        <div class="alert alert-danger">

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label for="f-register-full-name" class="form-label">
                                Full Name
                            </label>

                            <input value="<?php echo e($full_name); ?>" autocomplete="name" id="f-register-full-name"
                                type="text"
                                name="full_name"
                                class="form-control form-control-lg"
                                required>

                        </div>

                        <div class="mb-3">

                            <label for="f-register-email" class="form-label">
                                Email Address
                            </label>

                            <input value="<?php echo e($email); ?>" autocomplete="email" id="f-register-email"
                                type="email"
                                name="email"
                                class="form-control form-control-lg"
                                required>

                        </div>

                        <div class="mb-3">

                            <label for="f-register-phone" class="form-label">
                                Phone Number
                            </label>

                            <input value="<?php echo e($phone); ?>" autocomplete="tel" id="f-register-phone"
                                type="tel" inputmode="tel"
                                name="phone"
                                class="form-control form-control-lg">

                        </div>

                        <div class="mb-3">

                            <label for="f-register-address" class="form-label">
                                Address
                            </label>

                            <textarea autocomplete="street-address" id="f-register-address"
                                name="address"
                                rows="4"
                                class="form-control"><?php echo e($address); ?></textarea>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label for="password" class="form-label">
                                    Password
                                </label>

                                <div class="input-group">

                                <input autocomplete="new-password" minlength="8"
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('password', this)"
                                    aria-label="Show or hide password">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label for="confirm_password" class="form-label">
                                    Confirm Password
                                </label>

                                <div class="input-group">

                                <input autocomplete="new-password" minlength="8"
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="form-control form-control-lg"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('confirm_password', this)"
                                    aria-label="Show or hide password">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                            </div>

                        </div>

                        <button
                            class="btn btn-primary btn-lg w-100 mt-3">

                            <i class="bi bi-person-check-fill"></i>

                            Create Account

                        </button>

                    </form>

                    <hr>

                    <div class="text-center">

                        Already have an account?

                        <a href="login.php">

                            Login Here

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
function togglePassword(id, button){

    const input = document.getElementById(id);
    const icon = button.querySelector("i");

    if(input.type === "password"){

        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");

    }else{

        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");

    }

}
</script>

<?php include 'includes/footer.php'; ?>