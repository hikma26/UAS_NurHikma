<?php
include 'auth_check.php';

$id = $_GET['id'];
$stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_stock WHERE id='$id'"));

if (!$stok) {
  echo "<script>alert('Stok tidak ditemukan!'); window.location.href='welcome.php';</script>";
  exit;
}

$redirect = 'welcome.php'; // arahkan ke halaman welcome

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $quantity = (int) $_POST['quantity'];
  $min_stock = (int) $_POST['min_stock'];
  
  $query = "UPDATE blood_stock SET quantity = ?, min_stock = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'iii', $quantity, $min_stock, $id);
  
  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Stok berhasil diperbarui!'); window.location.href='$redirect';</script>";
  } else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
  mysqli_stmt_close($stmt);
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Stok Darah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
  <div class="container">
    <h3>Edit Stok Darah</h3>
    <div class="card">
      <div class="card-body">
        <div class="alert alert-info">
          <strong>Golongan Darah:</strong> <?= htmlspecialchars($stok['blood_type'] . $stok['rhesus']) ?>
        </div>
        <form method="post">
          <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah Kantong</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="<?= $stok['quantity'] ?>" min="0" required>
          </div>
          <div class="mb-3">
            <label for="min_stock" class="form-label">Stok Minimum</label>
            <input type="number" name="min_stock" id="min_stock" class="form-control" value="<?= $stok['min_stock'] ?>" min="0" required>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
            <a href="<?= $redirect ?>" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

