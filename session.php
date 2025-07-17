<?php
session_start();

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fungsi untuk cek apakah user adalah admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

// Fungsi untuk cek apakah user adalah petugas
function isPetugas() {
    return isLoggedIn() && $_SESSION['role'] === 'petugas';
}

// Fungsi untuk cek apakah user adalah user biasa
function isUser() {
    return isLoggedIn() && $_SESSION['role'] === 'user';
}

// Fungsi untuk redirect jika tidak login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

// Fungsi untuk redirect jika bukan admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: ../index.php");
        exit();
    }
}

// Fungsi untuk logout
function logout() {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
