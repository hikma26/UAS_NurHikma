<?php
include '../session.php';
include '../koneksi.php';
if ($_SESSION['role_id'] != 1) header("Location: ../login-admin.php");
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_types WHERE id=$id"));
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST['type'];
  mysqli_query($conn, "UPDATE blood_types SET type='$type' WHERE id=$id");
  header("Location: blood-types.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Golongan Darah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/style.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h4>Edit Golongan Darah</h4>
    <form method="post">
      <div class="mb-3">
        <label>Golongan Darah</label>
        <input type="text" name="type" class="form-control" value="<?= $data['type'] ?>" required>
      </div>
      <button class="btn btn-primary">Update</button>
      <a href="blood-types.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>

