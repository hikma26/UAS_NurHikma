<?php
include 'auth_check.php';

$id = $_GET['id'];
$redirect = $_GET['redirect'] ?? 'welcome.php';

// Cek apakah data ada
$check = mysqli_query($conn, "SELECT * FROM blood_stock WHERE id='$id'");
if (mysqli_num_rows($check) == 0) {
  echo "<script>alert('Stok tidak ditemukan!'); window.location.href='$redirect';</script>";
  exit;
}

// Eksekusi hapus data
$query = "DELETE FROM blood_stock WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
  echo "<script>alert('Stok berhasil dihapus!'); window.location.href='$redirect';</script>";
} else {
  echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='$redirect';</script>";
}

mysqli_stmt_close($stmt);
exit;

