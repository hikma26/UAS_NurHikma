<?php
session_start();
require_once \"../config/koneksi.php\";

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

$blood_types = mysqli_query($conn, "SELECT * FROM blood_types ORDER BY type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $blood_type_id = $_POST['blood_type_id'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $phone = $_POST['phone'];
    $district = $_POST['district'];
    $village = $_POST['village'];
    $weight = $_POST['weight'];
    $medical_history = $_POST['medical_history'];
    $status_id = 1;

    $stmt = $conn->prepare("INSERT INTO donors (name, blood_type_id, gender, birth_date, phone, district, village, weight, medical_history, status_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sisssssdis", $name, $blood_type_id, $gender, $birth_date, $phone, $district, $village, $weight, $medical_history, $status_id);
    $stmt->execute();
    $stmt->close();

    header("Location: laporan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pendonor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
  <div class="container">
    <h3 class="mb-4 text-danger">Tambah Pendonor</h3>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Golongan Darah</label>
        <select name="blood_type_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php while ($bt = mysqli_fetch_assoc($blood_types)): ?>
            <option value="<?= $bt['id'] ?>"><?= htmlspecialchars($bt['type']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="gender" class="form-select" required>
          <option value="">-- Pilih --</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="birth_date" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">No. HP</label>
        <input type="text" name="phone" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Kecamatan</label>
        <select name="district" class="form-select" required>
          <option value="">-- Pilih Kecamatan --</option>
          <option value="Topoyo">Topoyo</option>
          <option value="Budong-Budong">Budong-Budong</option>
          <option value="Pangale">Pangale</option>
          <option value="Tobadak">Tobadak</option>
          <option value="Karossa">Karossa</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Desa/Kelurahan</label>
        <input type="text" name="village" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Berat Badan (kg)</label>
        <input type="number" name="weight" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Riwayat Penyakit</label>
        <textarea name="medical_history" class="form-control" rows="3"></textarea>
      </div>

      <button type="submit" class="btn btn-danger">Simpan</button>
      <a href="laporan.php" class="btn btn-secondary ms-2">Batal</a>
    </form>
  </div>
</body>
</html>

