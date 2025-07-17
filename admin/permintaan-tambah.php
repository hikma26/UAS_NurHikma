<?php
session_start();
require_once \"../config/koneksi.php\";

// Validasi: hanya admin login yang bisa akses
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

// Ambil user_id dari session (pastikan sudah ada saat login)
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User tidak valid!");
}

// Ambil data golongan darah
$blood_types = mysqli_query($conn, "SELECT * FROM blood_types ORDER BY type");

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pasien = $_POST['nama_pasien'];
    $blood_type_id = $_POST['blood_type_id'];
    $jumlah = $_POST['jumlah'];
    $kontak = $_POST['kontak'];
    $keterangan = $_POST['keterangan'];
    $status_id = 1; // Status default (misal: "menunggu")

    $stmt = $conn->prepare("INSERT INTO requests 
        (nama_pasien, blood_type_id, jumlah, kontak, keterangan, status_id, user_id, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        die("Gagal prepare: " . $conn->error);
    }

    $stmt->bind_param("siissii", $nama_pasien, $blood_type_id, $jumlah, $kontak, $keterangan, $status_id, $user_id);

    if ($stmt->execute()) {
        header("Location: laporan.php");
        exit;
    } else {
        die("Gagal menambah data: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Permintaan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4 text-danger">Tambah Permintaan Darah</h3>
    <form method="post">
      <div class="mb-3">
        <label for="nama_pasien" class="form-label">Nama Pasien</label>
        <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="blood_type_id" class="form-label">Golongan Darah</label>
        <select name="blood_type_id" id="blood_type_id" class="form-select" required>
          <option value="">Pilih Golongan Darah</option>
          <?php while($bt = mysqli_fetch_assoc($blood_types)): ?>
            <option value="<?= $bt['id'] ?>"><?= htmlspecialchars($bt['type']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="jumlah" class="form-label">Jumlah (kantong)</label>
        <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
      </div>
      <div class="mb-3">
        <label for="kontak" class="form-label">Kontak</label>
        <input type="text" name="kontak" id="kontak" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
      </div>
      <button type="submit" class="btn btn-danger">Simpan</button>
      <a href="laporan.php" class="btn btn-secondary ms-2">Batal</a>
    </form>
  </div>
</body>
</html>

