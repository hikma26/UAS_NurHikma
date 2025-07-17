<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

// Pastikan request pakai POST dan ada id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $query = "DELETE FROM donors WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        // Berhasil hapus
        header("Location: laporan.php?msg=delete_success");
        exit;
    } else {
        // Gagal hapus, bisa log error
        header("Location: laporan.php?msg=delete_failed");
        exit;
    }
} else {
    // Kalau tidak POST / tanpa id langsung redirect
    header("Location: laporan.php");
    exit;
}

