<?php
session_start();

require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$full_name = trim($_POST['full_name'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$address   = trim($_POST['address'] ?? '');

// A blank name or an over-long value is ignored rather than saved
if ($full_name !== '' && mb_strlen($full_name) <= 100 && mb_strlen($phone) <= 20) {

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $full_name, $phone, $address, $user_id);
    $stmt->execute();

    // The plain name, not an SQL-escaped copy (which would show stray backslashes)
    $_SESSION['user_name'] = $full_name;
}

header("Location: profile.php");
exit;
