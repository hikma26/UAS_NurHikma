<?php
session_start();
require_once \"../config/koneksi.php\";

// Cek akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

// Ambil data dari tabel donors
$data = mysqli_query($conn, "
  SELECT d.*, d.name AS nama_pendonor, b.type AS golongan, s.nama_status
  FROM donors d
  JOIN users u ON d.user_id = u.id
  JOIN blood_types b ON d.blood_type_id = b.id
  JOIN statuses s ON d.status_id = s.id
  ORDER BY d.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Data Pendonor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 20px;
    }
    header.header-main {
      background-color: #8B0000;
      color: white;
      padding: 15px 30px;
      font-weight: 600;
      font-size: 1.4rem;
      text-align: center;
      margin-bottom: 25px;
      border-radius: 6px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    table th {
      background-color: #8B0000;
      color: white;
      text-align: center;
    }
    table td {
      text-align: center;
    }
    .badge-status {
      font-size: 0.85rem;
      padding: 5px 8px;
    }
  </style>
</head>
<body>

<header class="header-main">
  Kelola Data Pendonor
</header>

<div class="container">
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Lengkap</th>
          <th>Gol. Darah</th>
          <th>Jenis Kelamin</th>
          <th>HP</th>
          <th>Kecamatan / Desa</th>
          <th>Berat Badan</th>
          <th>Riwayat Penyakit</th>
          <th>Status</th>
          <th>Waktu Daftar</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($d = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($d['nama_pendonor']) ?></td>
          <td><?= htmlspecialchars($d['golongan']) ?></td>
          <td><?= htmlspecialchars($d['gender']) ?></td>
          <td><?= htmlspecialchars($d['phone']) ?></td>
          <td><?= htmlspecialchars($d['district']) ?> / <?= htmlspecialchars($d['village']) ?></td>
          <td><?= (int)$d['weight'] ?> kg</td>
          <td><?= nl2br(htmlspecialchars($d['medical_history'])) ?></td>
          <td><span class="badge bg-secondary badge-status"><?= htmlspecialchars($d['nama_status']) ?></span></td>
          <td><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>

