<?php
// File untuk mengecek otentikasi admin
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // If this is an iframe request, show a message instead of redirect
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'admin/index.php') !== false) {
        echo '<script>top.location.href = "../login.php";</script>';
        exit;
    }
    header("Location: ../login.php");
    exit;
}

// Cek apakah user adalah admin atau petugas
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas') {
    // If this is an iframe request, show a message instead of redirect
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'admin/index.php') !== false) {
        echo '<script>top.location.href = "../login.php";</script>';
        exit;
    }
    header("Location: ../login.php");
    exit;
}

// Include koneksi database
include '../koneksi.php';

// Fungsi untuk mengecek apakah user adalah admin
function isAdmin() {
    return $_SESSION['role'] == 'admin';
}

// Fungsi untuk mengecek apakah user adalah petugas
function isPetugas() {
    return $_SESSION['role'] == 'petugas';
}

// Fungsi untuk mengecek apakah user adalah admin atau petugas
function isAdminOrPetugas() {
    return ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'petugas');
}

// Fungsi untuk mendapatkan nama user saat ini
function getCurrentUserName() {
    return $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown';
}

// Fungsi untuk mendapatkan role user saat ini
function getCurrentUserRole() {
    return $_SESSION['role'] ?? 'unknown';
}
?>

