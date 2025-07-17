<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  mysqli_query($conn, "DELETE FROM jadwal_donor WHERE id = '$id'");
}

// Arahkan kembali ke beranda admin
header("Location: welcome.php");
exit;
?>

