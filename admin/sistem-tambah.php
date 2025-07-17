<?php
include '../koneksi.php';
include '../session.php';

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $versi = $_POST['versi'];
  $pengembang = $_POST['pengembang'];

  $insert = mysqli_query($conn, "INSERT INTO system_settings (nama, versi, pengembang) 
             VALUES ('$nama', '$versi', '$pengembang')");

  if ($insert) {
    header("Location: sistem-settings.php");
    exit;
  } else {
    echo "<div class='alert alert-danger p-3'>Gagal menyimpan data: " . mysqli_error($conn) . "</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pengaturan Sistem</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Font -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 30px;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .card-header {
      border-radius: 16px 16px 0 0;
    }
    .form-label {
      font-weight: 500;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card shadow">
    <div class="card-header bg-danger text-white">
      <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Pengaturan Sistem</h4>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nama Sistem</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Versi</label>
          <input type="text" name="versi" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Pengembang</label>
          <input type="text" name="pengembang" class="form-control" required>
        </div>
        <div class="d-flex justify-content-start">
          <button type="submit" name="simpan" class="btn btn-success"><i class="fas fa-save me-1"></i>Simpan</button>
          <a href="sistem-settings.php" class="btn btn-secondary ms-2">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>

