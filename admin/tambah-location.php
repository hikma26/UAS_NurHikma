<?php
include '../session.php';
include '../koneksi.php';
if ($_SESSION['role_id'] != 1) header("Location: ../login-admin.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $province = $_POST['province'];
  $city = $_POST['city'];
  mysqli_query($conn, "INSERT INTO locations (province, city) VALUES ('$province', '$city')");
  header("Location: locations.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Lokasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/style.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h4>Tambah Lokasi</h4>
    <form method="post">
      <div class="mb-3">
        <label>Provinsi</label>
        <input type="text" name="province" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Kota</label>
        <input type="text" name="city" class="form-control" required>
      </div>
      <button class="btn btn-success">Simpan</button>
      <a href="locations.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>

