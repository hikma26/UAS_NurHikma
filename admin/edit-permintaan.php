<?php
session_start();
require_once \"../config/koneksi.php\";

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: laporan.php");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM requests WHERE id = $id"));
if (!$data) {
    echo "<script>alert('Data tidak ditemukan');location.href='laporan.php';</script>";
    exit;
}

$blood_types = mysqli_query($conn, "SELECT * FROM blood_types ORDER BY type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pasien = $_POST['nama_pasien'];
    $blood_type_id = $_POST['blood_type_id'];
    $jumlah = $_POST['jumlah'];
    $kontak = $_POST['kontak'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("UPDATE requests SET nama_pasien=?, blood_type_id=?, jumlah=?, kontak=?, keterangan=? WHERE id=?");
    $stmt->bind_param("siissi", $nama_pasien, $blood_type_id, $jumlah, $kontak, $keterangan, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: laporan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Permintaan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 25px;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }
    .btn-success {
      background-color: #8B0000;
      border-color: #8B0000;
    }
    .btn-success:hover {
      background-color: #a30000;
      border-color: #a30000;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h4 class="text-center text-danger mb-4">Edit Permintaan Darah</h4>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama Pasien</label>
        <input type="text" name="nama_pasien" class="form-control" value="<?= htmlspecialchars($data['nama_pasien']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Golongan Darah</label>
        <select name="blood_type_id" class="form-select" required>
          <option value="">Pilih Golongan Darah</option>
          <?php while($bt = mysqli_fetch_assoc($blood_types)): ?>
            <option value="<?= $bt['id'] ?>" <?= $bt['id'] == $data['blood_type_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($bt['type']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Jumlah (kantong)</label>
        <input type="number" name="jumlah" class="form-control" value="<?= $data['jumlah'] ?>" required min="1">
      </div>

      <div class="mb-3">
        <label class="form-label">Kontak</label>
        <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($data['kontak']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control"><?= htmlspecialchars($data['keterangan']) ?></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <a href="laporan.php" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>

