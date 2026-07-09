<?php
session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
$phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
$address = mysqli_real_escape_string($conn, trim($_POST['address']));

$query = "
UPDATE users
SET
full_name='$full_name',
phone='$phone',
address='$address'
WHERE id=$user_id
";

if(mysqli_query($conn,$query)){

    $_SESSION['user_name'] = $full_name;

}

header("Location: profile.php");

exit;
?>