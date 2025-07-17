<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin_id'])) {
  header("Location: login-admin.php");
  exit;
}

// Perbaiki kolom 'nama_status' jadi 'name' karena itu nama asli di tabel statuses
$data = mysqli_query($conn, "
  SELECT r.*, u.name AS nama_user, s.name AS nama_status
  FROM requests r
  JOIN users u ON r.user_id = u.id
  JOIN statuses s ON r.status_id = s.id
  ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Data Permintaan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../../assets/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>
<body>

<header class="header-main">Kelola Permintaan Darah</header>

<div class="container my-5">
  <div class="card shadow p-4 rounded-4">
    <h5 class="mb-4 text-danger"><i class="fas fa-hand-holding-medical me-2"></i>Permintaan Darah</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-danger">
          <tr>
            <th>#</th>
            <th>Pasien</th>
            <th>Golongan</th>
            <th>Jumlah</th>
            <th>Kontak</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) > 0): ?>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($data)) : ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($r['nama_pasien']) ?></td>
              <td><?= htmlspecialchars($r['golongan_darah']) ?></td>
              <td><?= (int)$r['jumlah'] ?></td>
              <td><?= htmlspecialchars($r['kontak']) ?></td>
              <td><span class="badge text-bg-secondary"><?= htmlspecialchars($r['nama_status']) ?></span></td>
              <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
              <td>
                <!-- Dropdown Status -->
                <form action="update-status.php" method="post" class="d-inline">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                  <input type="hidden" name="type" value="permintaan" />
                  <select name="status_id" onchange="this.form.submit()" class="form-select form-select-sm" style="width:auto; display:inline-block;">
                    <?php
                    $statusList = mysqli_query($conn, "SELECT * FROM statuses ORDER BY id");
                    while ($status = mysqli_fetch_assoc($statusList)):
                    ?>
                      <option value="<?= $status['id'] ?>" <?= $status['id'] == $r['status_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($status['name']) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </form>

                <!-- Tombol Hapus -->
                <form action="hapus-permintaan.php" method="post" class="d-inline ms-1" onsubmit="return confirm('Yakin ingin menghapus permintaan ini?');">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
            <?php endwhile ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center">Belum ada data permintaan darah.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <a href="index.php" class="btn btn-secondary mt-3">← Kembali</a>
  </div>
</div>

<footer class="footer-main">
  &copy; 2025 Sistem Donor Darah – Admin
</footer>

</body>
</html>

