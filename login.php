<?php
session_start();

require_once 'config/database.php';

$error = "";

// If already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email' LIMIT 1"
    );

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: index.php");
            exit;

        } else {

            $error = "Incorrect password.";

        }

    } else {

        $error = "No account found with that email.";

    }

}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <i class="bi bi-person-circle display-3 text-primary"></i>

                        <h1 class="fw-bold mt-3">

                            Welcome Back

                        </h1>

                        <p class="text-muted">

                            Login to continue shopping.

                        </p>

                    </div>

                    <?php

                    if(isset($_SESSION['success'])){

                    ?>

                        <div class="alert alert-success">

                            <?php

                            echo $_SESSION['success'];
                            unset($_SESSION['success']);

                            ?>

                        </div>

                    <?php } ?>

                    <?php if($error != ""){ ?>

                        <div class="alert alert-danger">

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

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

                        <div class="mb-4">

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

                        <button
                            class="btn btn-primary btn-lg w-100">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Login

                        </button>

                    </form>

                    <hr>

                    <div class="text-center">

                        Don't have an account?

                        <a href="register.php">

                            Register Here

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