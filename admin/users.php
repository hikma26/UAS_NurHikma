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
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      min-height: 100vh;
      padding-top: 1.5rem;
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
    
    .card {
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      color: white;
      border-bottom: none;
      border-radius: 16px 16px 0 0;
      padding: 1.5rem;
      position: relative;
      overflow: hidden;
    }
    
    .card-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      transform: translate(30%, -30%);
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
      text-align: center;
      border: none;
      font-weight: 600;
      padding: 1rem;
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
    
    .btn-add {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }
    
    .btn-add:hover {
      background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
      color: #fff;
    }
    
    .btn-edit {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-edit:hover {
      background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
      color: white;
    }
    
    .btn-delete {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-delete:hover {
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
      color: #fff;
    }
    
    .btn-group-top {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 15px;
    }
    
    @media (max-width: 576px) {
      .btn-group-top {
        flex-direction: column-reverse;
        align-items: stretch;
      }
      
      .page-title {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="container-fluid py-2">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-5">
          <h1 class="page-title">
            <i class="fas fa-users-cog me-3" style="color: #dc2626;"></i>
            Manajemen Pengguna
          </h1>
        </div>

        <div class="btn-group-top">
          <a href="tambah-user.php" class="btn btn-add">
            <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
          </a>
        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="fas fa-users me-2"></i>
              Daftar Pengguna
            </h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
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
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>

