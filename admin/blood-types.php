<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM blood_types");
if (!$data) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Manajemen Golongan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #b71c1c;
      color: white;
      padding: 15px 30px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      width: 100vw;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 9999;
      font-weight: 600;
      font-size: 1.3rem;
      user-select: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .container-main {
      max-width: 960px;
      margin: 90px auto 40px;
      padding: 0 15px;
      width: 100%;
      flex-grow: 1;
      background-color: #fff;
    }

    h4 {
      color: #b71c1c;
      font-weight: 700;
      margin-bottom: 25px;
      text-align: center;
      font-size: 1.8rem;
    }

    /* Tombol sederhana */
    .btn-soft {
      font-weight: 600;
      border-radius: 6px;
      padding: 7px 18px;
      font-size: 1rem;
      border: 1.5px solid transparent;
      transition: background-color 0.25s ease, border-color 0.25s ease;
      box-shadow: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      user-select: none;
    }
    .btn-soft-add {
      background-color: #e3f2fd;
      color: #0d6efd;
      border-color: #0d6efd;
    }
    .btn-soft-add:hover {
      background-color: #bbdefb;
      color: #084298;
      border-color: #084298;
      text-decoration: none;
    }

    .btn-soft-back {
      background-color: #f8d7da;
      color: #b71c1c;
      border-color: #b71c1c;
    }
    .btn-soft-back:hover {
      background-color: #f5c2c7;
      color: #7f1d1d;
      border-color: #7f1d1d;
      text-decoration: none;
    }

    /* Tabel formal, rapi, elegan */
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border: 1.5px solid #ccc;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      font-size: 1rem;
    }

    thead {
      background-color: #343a40; /* abu gelap */
      color: white;
      font-weight: 700;
    }

    thead th {
      padding: 14px 20px;
      text-align: left;
      border-bottom: 2px solid #495057;
    }

    tbody tr {
      border-bottom: 1px solid #ddd;
      transition: background-color 0.15s ease;
    }
    tbody tr:hover {
      background-color: #f9f9f9;
    }

    tbody td {
      padding: 14px 20px;
      color: #212529;
      vertical-align: middle;
      border-bottom: 1px solid #ddd;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .container-main {
        margin: 100px 15px 40px;
        padding: 0 10px;
      }
      thead th, tbody td {
        padding: 12px 15px;
        font-size: 0.95rem;
      }
      .btn-soft {
        width: 100%;
        justify-content: center;
        font-size: 1.1rem;
        padding: 10px 0;
      }
      .btn-soft + .btn-soft {
        margin-top: 10px;
      }
    }

    @media (max-width: 480px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead tr {
        display: none;
      }

      tbody tr {
        margin-bottom: 18px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 0 12px rgba(0,0,0,0.05);
        padding: 12px;
      }

      tbody td {
        padding-left: 50%;
        position: relative;
        text-align: right;
        font-weight: 600;
        color: #b71c1c;
        border-bottom: 1px solid #f5dede;
        font-size: 1rem;
      }

      tbody td:last-child {
        border-bottom: 0;
      }

      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 14px;
        width: 45%;
        padding-left: 0;
        font-weight: 600;
        text-align: left;
        color: #444;
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<header>
  <i class="fas fa-tint"></i> Manajemen Golongan Darah
</header>

<div class="container-main">

  <div class="d-flex flex-wrap justify-content-between mb-3 gap-2">
    <a href="index.php" class="btn btn-soft btn-soft-back">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="tambah-blood.php" class="btn btn-soft btn-soft-add">
      <i class="fas fa-plus"></i> Tambah Golongan Darah
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 6%;">No</th>
        <th>Tipe Golongan Darah</th>
        <th style="width: 22%;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; while($b = mysqli_fetch_assoc($data)): ?>
      <tr>
        <td data-label="No"><?= $no++ ?></td>
        <td data-label="Tipe Golongan Darah"><?= htmlspecialchars($b['type']) ?></td>
        <td data-label="Aksi">
          <a href="edit-blood.php?id=<?= $b['id'] ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-pen"></i> Edit
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

</body>
</html>

