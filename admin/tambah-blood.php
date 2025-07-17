<?php
include '../session.php';
include '../koneksi.php';
if ($_SESSION['role_id'] != 1) header("Location: ../login-admin.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST['type'];
  mysqli_query($conn, "INSERT INTO blood_types (type) VALUES ('$type')");
  header("Location: blood-types.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Golongan Darah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/style.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h4>Tambah Golongan Darah</h4>
    <form method="post">
      <div class="mb-3">
        <label>Golongan Darah</label>
        <input type="text" name="type" class="form-control" required>
      </div>
      <button class="btn btn-success">Simpan</button>
      <a href="blood-types.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>

