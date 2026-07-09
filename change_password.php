<?php
session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

$result = mysqli_query(
    $conn,
    "SELECT password FROM users WHERE id=$user_id"
);

$user = mysqli_fetch_assoc($result);

if(!password_verify($current,$user['password'])){

    die("Current password is incorrect.");

}

if($new != $confirm){

    die("New passwords do not match.");

}

$hashed = password_hash($new,PASSWORD_DEFAULT);

mysqli_query(
    $conn,
    "UPDATE users
    SET password='$hashed'
    WHERE id=$user_id"
);

header("Location: profile.php");

exit;
?>