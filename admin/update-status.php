<?php
session_start();
include '../koneksi.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

// Ambil data dari POST
$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null; // 'pendonor' atau 'permintaan'
$status_id = $_POST['status_id'] ?? null;

// Validasi input
if ($id && $type && $status_id) {
    if ($type === 'pendonor') {
        $table = 'donors';
    } elseif ($type === 'permintaan') {
        $table = 'requests';
    } else {
        die("Tipe data tidak valid.");
    }

    // Update status
    $stmt = mysqli_prepare($conn, "UPDATE $table SET status_id = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $status_id, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die("Gagal menyiapkan perintah update.");
    }
} else {
    die("Data yang dikirim tidak lengkap.");
}

// Redirect kembali ke halaman laporan
header("Location: laporan.php");
exit;

