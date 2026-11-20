<?php
session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($current, $user['password'])) {

    die("Current password is incorrect.");

}

if (strlen($new) < 8) {

    die("New password must be at least 8 characters.");

}

if ($new !== $confirm) {

    die("New passwords do not match.");

}

$hashed = password_hash($new, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->bind_param("si", $hashed, $user_id);
$update->execute();

header("Location: profile.php");

exit;
