<?php
include '../koneksi.php';
include '../session.php';

$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM system_settings WHERE id = '$id'");
$data = mysqli_fetch_assoc($q);

if (isset($_POST['update'])) {
  $nama = $_POST['nama'];
  $versi = $_POST['versi'];
  $pengembang = $_POST['pengembang'];

  $update = mysqli_query($conn, "UPDATE system_settings SET 
    nama='$nama', 
    versi='$versi', 
    pengembang='$pengembang' 
    WHERE id='$id'
  ");

  if ($update) {
    header("Location: sistem-settings.php");
    exit;
  } else {
    echo "<div class='alert alert-danger'>Gagal memperbarui data.</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengaturan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .card-header {
      background-color: #8B0000;
      color: white;
    }
    .btn-primary {
      background-color: #8B0000;
      border-color: #8B0000;
    }
    .btn-primary:hover {
      background-color: #a30000;
      border-color: #a30000;
    }
    .btn-secondary {
      background-color: #6c757d;
    }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <div class="card shadow">
      <div class="card-header">
        <h4><i class="fas fa-edit me-2"></i>Edit Pengaturan Sistem</h4>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label>Nama Sistem</label>
            <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
          </div>
          <div class="mb-3">
            <label>Versi</label>
            <input type="text" name="versi" class="form-control" value="<?= $data['versi'] ?>" required>
          </div>
          <div class="mb-3">
            <label>Pengembang</label>
            <input type="text" name="pengembang" class="form-control" value="<?= $data['pengembang'] ?>" required>
          </div>
          <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
          <a href="sistem-settings.php" class="btn btn-secondary ms-2">Kembali</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

