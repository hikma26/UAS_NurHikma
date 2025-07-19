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
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      min-height: 100vh;
      padding-top: 1.5rem;
      margin: 0;
      color: #2c3e50;
    }
    
    .main-container {
      margin-left: 100px; /* Space for admin toggle button */
      margin-right: 2rem;
      margin-top: 2rem;
    }
    
    @media (max-width: 768px) {
      .main-container {
        margin-left: 1rem;
        margin-right: 1rem;
        margin-top: 1rem;
      }
    }
    
    .page-title {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
      font-size: 2.5rem;
      margin-bottom: 0;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .institution {
      font-size: 1.1rem;
      color: #6c757d;
      margin-bottom: 2rem;
      font-weight: 500;
    }

    .card-custom {
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      padding: 2rem;
      margin-bottom: 2.5rem;
    }
    
    .card-custom:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card-custom h5 {
      font-weight: 600;
      color: #dc2626;
      margin-bottom: 1.5rem;
      font-size: 1.3rem;
    }
    
    .table {
      border-radius: 12px;
      overflow: hidden;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
    }

    .table th {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      color: white;
      vertical-align: middle;
      border: none;
      font-weight: 600;
      padding: 1rem;
      text-align: center;
    }
    
    .table td {
      vertical-align: middle;
      padding: 0.875rem;
      border-color: rgba(220, 38, 38, 0.1);
    }
    
    .table tbody tr {
      transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
      background: rgba(220, 38, 38, 0.05);
      transform: scale(1.01);
    }

    .btn-sm i {
      margin-right: 0.25rem;
    }

    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
      color: white;
    }
    
    .btn-danger:hover {
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
      color: white;
    }

    .btn-primary {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
      color: white;
    }
    
    .btn-primary:hover {
      background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
      color: white;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 2rem;
      }
      
      .institution {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="container-fluid py-2">
    <div class="row">
      <div class="col-12">
        <!-- HEADER -->
        <div class="mb-5">
          <h1 class="page-title">
            <i class="fas fa-chart-pie me-3" style="color: #dc2626;"></i>
            Dashboard Admin
          </h1>
          <div class="institution">Sistem Donor Darah | Kabupaten Mamuju Tengah</div>
        </div>
        
        <!-- Status Messages -->
        <?php if (isset($_GET['status'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php if ($_GET['status'] == 'deleted'): ?>
              Data berhasil dihapus!
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php if ($_GET['error'] == 'delete_failed'): ?>
              Gagal menghapus data!
            <?php elseif ($_GET['error'] == 'no_id'): ?>
              ID tidak valid!
            <?php else: ?>
              Terjadi kesalahan!
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

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
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

