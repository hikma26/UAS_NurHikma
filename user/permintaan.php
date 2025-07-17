<?php
session_start();
include "../koneksi.php"; // disesuaikan dengan lokasi file

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Permintaan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/style.css"> <!-- perbaikan path -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: radial-gradient(circle at top left, #fff4f4, #ffeaea);
      font-family: 'Poppins', sans-serif;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header.header-main {
      background-color: #8B0000;
      color: #fff;
      padding: 16px 30px;
      text-align: center;
      font-size: 1.3rem;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .form-box {
      background: white;
      padding: 35px 30px;
      border-radius: 16px;
      max-width: 760px;
      width: 100%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .form-box h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      color: #b71c1c;
    }

    label {
      font-weight: 500;
      margin-bottom: 6px;
    }

    .btn-danger {
      background-color: #c62828;
      border: none;
    }

    .btn-danger:hover {
      background-color: #b71c1c;
    }

    footer.footer-main {
      background-color: #fff0f0;
      text-align: center;
      padding: 18px 10px;
      font-size: 0.9rem;
      color: #a00;
      border-top: 1px solid #fdd;
    }

    @media (max-width: 768px) {
      .form-box {
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>

<header class="header-main">
  Form Permintaan Darah
</header>

<div class="form-wrapper">
  <div class="form-box">
    <h4><i class="fas fa-hand-holding-medical me-2 text-danger"></i>Ajukan Permintaan Darah</h4>
    <form method="post" action="simpan-permintaan.php">
      <div class="mb-3">
        <label>Nama pemohon</label>
        <input type="text" name="nama_pasien" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Golongan Darah yang Dibutuhkan</label>
        <select name="blood_type_id" class="form-select" required>
          <option value="">-- Pilih Golongan Darah --</option>
          <?php
          $blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
          foreach ($blood_types as $type) {
            echo "<option value='$type'>$type</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Jumlah Kantong</label>
        <input type="number" name="jumlah" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Rumah Sakit / Lokasi</label>
        <input type="text" name="lokasi" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Kontak Darurat</label>
        <input type="text" name="kontak" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Keterangan Tambahan</label>
        <textarea name="keterangan" class="form-control" rows="3"></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-danger px-4"><i class="fas fa-paper-plane me-1"></i>Kirim Permintaan</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div>

<footer class="footer-main">
  &copy; <?= date("Y") ?> Sistem Donor Darah • Kabupaten Mamuju Tengah
</footer>

</body>
</html>

