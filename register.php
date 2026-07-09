<?php
session_start();

require_once 'config/database.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password != $confirm_password) {

        $error = "Passwords do not match.";

    } else {

        // Check if email already exists
        $check = mysqli_query(
            $conn,
            "SELECT id FROM users WHERE email='$email'"
        );

        if (mysqli_num_rows($check) > 0) {

            $error = "An account with this email already exists.";

        } else {

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $query = "
                INSERT INTO users
                (
                    full_name,
                    email,
                    password,
                    phone,
                    address
                )
                VALUES
                (
                    '$full_name',
                    '$email',
                    '$hashedPassword',
                    '$phone',
                    '$address'
                )
            ";

            if (mysqli_query($conn, $query)) {

                $_SESSION['success'] = "Registration successful! Please log in.";

                header("Location: login.php");
                exit;

            } else {

                $error = "Registration failed: " . mysqli_error($conn);

            }

        }

    }

}

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

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                class="form-control form-control-lg"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control form-control-lg"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control form-control-lg">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <div class="input-group">

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('password', this)">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Confirm Password
                                </label>

                                <div class="input-group">

                                <input
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="form-control form-control-lg"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('confirm_password', this)">

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