<?php
session_start();

require_once "../config/database.php";

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin'] = $admin['username'];

            header("Location: index.php");
            exit;

        } else {

            $error = "Incorrect password.";

        }

    } else {

        $error = "Admin account not found.";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Login | TechCart</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:#0f172a;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Poppins,sans-serif;
}

.login-card{
    width:430px;
    border:none;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.3);
}

.card-header{
    background:#2563eb;
    color:white;
    text-align:center;
    padding:30px;
}

.card-header i{
    font-size:55px;
}

.card-body{
    padding:40px;
}

.btn-primary{
    width:100%;
    border-radius:50px;
}

</style>

</head>

<body>

<div class="card login-card">

<div class="card-header">

<i class="bi bi-shield-lock-fill"></i>

<h2 class="mt-3">Admin Login</h2>

<p class="mb-0">TechCart Administration</p>

</div>

<div class="card-body">

<?php if($error){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">Username</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-4">

<label class="form-label">Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button class="btn btn-primary">

<i class="bi bi-box-arrow-in-right"></i>

Login

</button>

</form>

</div>

</div>

</body>

</html>