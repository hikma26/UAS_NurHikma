<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
  mysqli_query($conn, "DELETE FROM logs WHERE id = '$id'");
}

header("Location: logs.php");
exit;
?>

