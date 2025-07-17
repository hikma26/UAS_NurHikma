<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

$query = "
  SELECT logs.id, users.name, logs.action AS activity, logs.timestamp AS created_at
  FROM logs
  JOIN users ON logs.user_id = users.id
  ORDER BY logs.timestamp DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
  die("Query gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Monitoring Aktivitas Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 20px;
    }
    h3 {
      color: #8B0000;
      margin-bottom: 20px;
    }
    table th {
      background-color: #8B0000;
      color: white;
      text-align: center;
    }
    table td {
      text-align: center;
      vertical-align: middle;
    }
    .btn i {
      margin-right: 4px;
    }
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 6px;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-user-shield me-2"></i>Monitoring Aktivitas Pengguna</h3>
    <a href="logscreate.php" class="btn btn-success btn-sm">
      <i class="fas fa-plus-circle"></i> Tambah Log
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th><i class="fas fa-user"></i> User</th>
          <th><i class="fas fa-tasks"></i> Aktivitas</th>
          <th><i class="fas fa-clock"></i> Waktu</th>
          <th><i class="fas fa-gear"></i> Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(mysqli_num_rows($result) > 0): $no = 1; ?>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['activity']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
            <td>
              <div class="action-buttons">
                <a href="logsedit.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="logsdelete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus log ini?')" title="Hapus">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">Belum ada data log.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>

