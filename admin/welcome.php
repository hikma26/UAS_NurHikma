<?php
include 'auth_check.php';

// Query untuk mendapatkan data stok darah
$stok = mysqli_query($conn, "
  SELECT id, blood_type, rhesus, quantity, min_stock
  FROM blood_stock
  ORDER BY blood_type ASC, rhesus ASC
") or die("Query stok darah error: " . mysqli_error($conn));

// Query untuk mendapatkan jadwal/event donor
$jadwal = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC") or die("Query jadwal error: " . mysqli_error($conn));

// Query untuk mendapatkan statistik
$total_donor = mysqli_query($conn, "SELECT COUNT(*) as total FROM donors WHERE is_active = 1");
$total_donor_count = mysqli_fetch_assoc($total_donor)['total'];

$total_donasi = mysqli_query($conn, "SELECT COUNT(*) as total FROM donations WHERE status = 'completed'");
$total_donasi_count = mysqli_fetch_assoc($total_donasi)['total'];

$total_permintaan = mysqli_query($conn, "SELECT COUNT(*) as total FROM blood_requests WHERE status = 'pending'");
$total_permintaan_count = mysqli_fetch_assoc($total_permintaan)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Sistem Donor Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 20px;
      color: #2c3e50;
    }

    .header-title {
      font-size: 24px;
      font-weight: 600;
      color: #dc3545;
      border-left: 5px solid #dc3545;
      padding-left: 15px;
      margin-bottom: 25px;
    }

    .institution {
      font-size: 18px;
      color: #6c757d;
      margin-bottom: 30px;
    }

    .card-custom {
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      padding: 25px;
      margin-bottom: 40px;
    }

    .card-custom h5 {
      font-weight: 600;
      color: #dc3545;
      margin-bottom: 20px;
    }

    .table th {
      background-color: #dc3545;
      color: white;
      vertical-align: middle;
    }

    .btn-sm i {
      margin-right: 5px;
    }

    .btn-danger {
      background-color: #dc3545;
      border: none;
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
    }

    .btn:hover {
      opacity: 0.9;
    }

    @media (max-width: 768px) {
      .header-title {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- HEADER -->
  <div class="mb-4">
    <div class="header-title">Dashboard Admin</div>
    <div class="institution">Sistem Donor Darah | Kabupaten Mamuju Tengah</div>
  </div>

  <!-- STOK DARAH -->
  <div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="fas fa-burn"></i> Data Stok Darah</h5>
      <a href="stok-tambah.php?redirect=welcome.php" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i> Tambah Stok</a>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Golongan Darah</th>
            <th>Jumlah</th>
            <th style="width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while($row = mysqli_fetch_assoc($stok)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['blood_type'] . $row['rhesus']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?> Kantong</td>
            <td>
              <a href="stok-edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
              <a href="stok-hapus.php?id=<?= $row['id'] ?>&redirect=welcome.php" class="btn btn-sm btn-danger" onclick="return confirm('Hapus stok ini?')"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- JADWAL DONOR -->
  <div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="fas fa-calendar-alt"></i> Jadwal Donor</h5>
      <a href="jadwal-tambah.php" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i> Tambah Jadwal</a>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Tempat</th>
            <th>Keterangan</th>
            <th style="width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while($j = mysqli_fetch_assoc($jadwal)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= date('d-m-Y', strtotime($j['event_date'])) ?></td>
            <td><?= htmlspecialchars($j['start_time']) ?> - <?= htmlspecialchars($j['end_time']) ?></td>
            <td><?= htmlspecialchars($j['location']) ?></td>
            <td><?= htmlspecialchars($j['description']) ?></td>
            <td>
              <a href="jadwal-edit.php?id=<?= $j['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
              <a href="jadwal-hapus.php?id=<?= $j['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal ini?')"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>

