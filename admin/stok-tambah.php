<?php
include 'auth_check.php';

$redirect = 'welcome.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $blood_type = $_POST['blood_type'];
  $rhesus = $_POST['rhesus'];
  $quantity = (int) $_POST['quantity'];
  $min_stock = (int) $_POST['min_stock'];

  // Cek apakah data sudah ada
  $cek = mysqli_query($conn, "SELECT * FROM blood_stock WHERE blood_type = '$blood_type' AND rhesus = '$rhesus'");
  if (mysqli_num_rows($cek) > 0) {
    // Update
    mysqli_query($conn, "
      UPDATE blood_stock 
      SET quantity = quantity + $quantity, min_stock = '$min_stock' 
      WHERE blood_type = '$blood_type' AND rhesus = '$rhesus'
    ");
    $message = "Stok berhasil diperbarui!";
  } else {
    // Tambah baru
    mysqli_query($conn, "
      INSERT INTO blood_stock (blood_type, rhesus, quantity, min_stock) 
      VALUES ('$blood_type', '$rhesus', '$quantity', '$min_stock')
    ");
    $message = "Stok baru berhasil ditambahkan!";
  }

  if (mysqli_error($conn)) {
    echo "<script>alert('Gagal input/update: " . mysqli_error($conn) . "'); window.location.href='$redirect';</script>";
    exit;
  }

  echo "<script>alert('$message'); window.location.href='$redirect';</script>";
  exit;
}

// Blood types array
$blood_types = ['A', 'B', 'AB', 'O'];
$rhesus_types = ['+', '-'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Stok Darah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Tambah / Perbarui Stok Darah</h3>
    <form method="post" class="border rounded p-4 shadow-sm bg-light">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="blood_type" class="form-label">Golongan Darah</label>
            <select name="blood_type" id="blood_type" class="form-control" required>
              <option value="" disabled selected>-- Pilih Golongan Darah --</option>
              <?php foreach($blood_types as $type): ?>
              <option value="<?= $type ?>"><?= $type ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="rhesus" class="form-label">Rhesus</label>
            <select name="rhesus" id="rhesus" class="form-control" required>
              <option value="" disabled selected>-- Pilih Rhesus --</option>
              <?php foreach($rhesus_types as $rhesus): ?>
              <option value="<?= $rhesus ?>"><?= $rhesus ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="quantity" class="form-label">Jumlah Kantong</label>
        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
      </div>
      <div class="mb-3">
        <label for="min_stock" class="form-label">Stok Minimum</label>
        <input type="number" name="min_stock" id="min_stock" class="form-control" min="0" value="5" required>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
        <a href="<?= $redirect ?>" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>

