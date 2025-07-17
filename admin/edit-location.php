<?php
include '../session.php';
include '../koneksi.php';
if ($_SESSION['role_id'] != 1) header("Location: ../login-admin.php");
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM locations WHERE id=$id"));
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $province = $_POST['province'];
  $city = $_POST['city'];
  mysqli_query($conn, "UPDATE locations SET province='$province', city='$city' WHERE id=$id");
  header("Location: locations.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Lokasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/style.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h4>Edit Lokasi</h4>
    <form method="post">
      <div class="mb-3">
        <label>Provinsi</label>
        <input type="text" name="province" class="form-control" value="<?= $data['province'] ?>" required>
      </div>
      <div class="mb-3">
        <label>Kota</label>
        <input type="text" name="city" class="form-control" value="<?= $data['city'] ?>" required>
      </div>
      <button class="btn btn-primary">Update</button>
      <a href="locations.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>

