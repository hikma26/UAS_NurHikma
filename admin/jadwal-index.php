<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

$jadwal = mysqli_query($conn, "SELECT * FROM jadwal_donor ORDER BY tanggal ASC");
if (!$jadwal) {
    die("Query jadwal error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Jadwal Donor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Kelola Jadwal Donor Darah</h3>
    <a href="jadwal-tambah.php" class="btn btn-success mb-3"><i class="fas fa-plus me-1"></i> Tambah Jadwal</a>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Tempat</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($jadwal)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
            <td><?= htmlspecialchars($row['waktu']) ?></td>
            <td><?= htmlspecialchars($row['tempat']) ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td>
              <a href="jadwal-edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
              <a href="jadwal-hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus jadwal ini?')"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

