<?php
include 'auth_check.php';

// Ambil data users
$sql = "SELECT id, username, full_name, email, role, is_active, created_at FROM users ORDER BY id ASC";

$data = mysqli_query($conn, $sql);
if (!$data) die("Query gagal: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Manajemen Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fdfdfd;
    }
    .navbar {
      background-color: #b71c1c;
    }
    .navbar-brand, .nav-link, .navbar-text {
      color: #fff !important;
      font-weight: 600;
    }
    h4 {
      color: #b71c1c;
      font-weight: 600;
      margin-top: 30px;
      margin-bottom: 25px;
      text-align: center;
    }
    .table thead {
      background-color: #b71c1c;
      color: #fff;
    }
    .btn-group-top {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 10px;
    }
    .btn-add {
      background-color: #0d6efd;
      color: #fff;
      font-weight: 600;
    }
    .btn-add:hover {
      background-color: #0b5ed7;
    }
    .btn-back {
      background-color: #6c757d;
      color: #fff;
      font-weight: 600;
    }
    .btn-back:hover {
      background-color: #5a6268;
    }
    .btn-edit {
      background-color: #ffc107;
      color: #000;
    }
    .btn-edit:hover {
      background-color: #e0a800;
      color: #000;
    }
    .btn-delete {
      background-color: #dc3545;
      color: #fff;
    }
    .btn-delete:hover {
      background-color: #c82333;
      color: #fff;
    }
    @media (max-width: 576px) {
      .btn-group-top {
        flex-direction: column-reverse;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="fas fa-droplet me-2"></i>Admin Donor Darah</a>
    <span class="navbar-text">Panel Manajemen</span>
  </div>
</nav>

<!-- Konten -->
<div class="container">
  <h4><i class="fas fa-users me-2"></i>Manajemen Pengguna</h4>

  <div class="btn-group-top">
    <a href="tambah-user.php" class="btn btn-add">
      <i class="fas fa-user-plus me-1"></i>Tambah Pengguna
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead>
        <tr>
          <th style="width:5%;">No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th style="width:20%;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars(ucfirst($row['role'])) ?></td>
          <td>
            <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-edit">
              <i class="fas fa-pen"></i> Edit
            </a>
            <a href="hapus-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
              <i class="fas fa-trash"></i> Hapus
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>

