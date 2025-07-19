<?php
session_start();
require_once "../koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
  header("Location: ../login.php");
  exit;
}

// Blood types array
$blood_types = [
    'A+' => 'A+', 'A-' => 'A-',
    'B+' => 'B+', 'B-' => 'B-', 
    'AB+' => 'AB+', 'AB-' => 'AB-',
    'O+' => 'O+', 'O-' => 'O-'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Pendaftaran Pendonor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at top left, #fff4f4, #ffeaea);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
    }
    .header-main {
      background-color: #8B0000;
      color: white;
      padding: 15px 30px;
      font-size: 1.3rem;
      font-weight: 600;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-box {
      background: white;
      padding: 35px 30px;
      border-radius: 16px;
      max-width: 680px;
      margin: 40px auto;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
  </style>
</head>
<body>

<div class="header-main">Formulir Pendaftaran Pendonor</div>

<div class="form-box">
  <form method="POST" action="simpan-daftar.php">
    <div class="mb-3">
      <label>Nama Lengkap</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Golongan Darah</label>
      <select name="blood_type_id" class="form-select" required>
        <option value="">-- Pilih --</option>
        <?php foreach ($blood_types as $key => $value): ?>
          <option value="<?= $key ?>"><?= $value ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Jenis Kelamin</label>
      <select name="gender" class="form-select" required>
        <option value="">-- Pilih --</option>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Tanggal Lahir</label>
      <input type="date" name="birth_date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>No. HP</label>
      <input type="text" name="phone" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Kecamatan</label>
      <select name="district" class="form-select" required>
        <option value="">-- Pilih Kecamatan --</option>
        <option>Topoyo</option>
        <option>Budong-Budong</option>
        <option>Pangale</option>
        <option>Tobadak</option>
        <option>Karossa</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Desa/Kelurahan</label>
      <input type="text" name="village" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Berat Badan (kg)</label>
      <input type="number" name="weight" class="form-control" min="30" max="200" required>
    </div>

    <div class="mb-3">
      <label>Riwayat Penyakit</label>
      <textarea name="medical_history" class="form-control" rows="3"></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-danger">Daftar</button>
      <a href="dashboard.php" class="btn btn-secondary ms-2">Batal</a>
    </div>
  </form>
</div>

</body>
</html>

