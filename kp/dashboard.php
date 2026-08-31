<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['role'] == 'user') {
    header("Location: user.php");
} elseif ($_SESSION['role'] == 'admin') {
    header("Location: admin.php");
}
?>
