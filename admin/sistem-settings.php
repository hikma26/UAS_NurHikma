<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM system_settings");
if (!$result) {
    die("<div class='alert alert-danger'>Query gagal: " . mysqli_error($conn) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Pengaturan Sistem</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 20px;
    }
    h3 {
      margin-bottom: 20px;
      color: #8B0000;
      text-align: center;
      font-weight: 600;
    }
    .btn-add {
      background-color: #0d6efd;
      color: #fff;
      font-weight: 600;
      margin-bottom: 20px;
    }
    .btn-add:hover {
      background-color: #0b5ed7;
    }
    .btn-edit {
      background-color: #ffc107;
      color: #000;
    }
    .btn-edit:hover {
      background-color: #e0a800;
    }
    .btn-delete {
      background-color: #dc3545;
      color: #fff;
    }
    .btn-delete:hover {
      background-color: #c82333;
    }
    .table th {
      background-color: #8B0000;
      color: white;
      text-align: center;
    }
    .table td {
      text-align: center;
      vertical-align: middle;
    }
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 5px;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>

<div class="container">
  <h3><i class="fas fa-cogs me-2"></i>Pengaturan Sistem</h3>

  <a href="sistem-tambah.php" class="btn btn-add">
    <i class="fas fa-plus me-1"></i> Tambah Pengaturan
  </a>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>Nama Sistem</th>
          <th>Versi</th>
          <th>Pengembang</th>
          <th width="20%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['versi']) ?></td>
            <td><?= htmlspecialchars($row['pengembang']) ?></td>
            <td>
              <div class="action-buttons">
                <a href="sistem-edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-edit">
                  <i class="fas fa-pen"></i> Edit
                </a>
                <a href="sistem-hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus pengaturan ini?')">
                  <i class="fas fa-trash-alt"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center">Belum ada data pengaturan sistem.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>

