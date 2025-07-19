<?php
session_start();
require_once "../koneksi.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
  header("Location: ../login.php");
  exit;
}

$stok = mysqli_query($conn, "
  SELECT CONCAT(blood_type, rhesus) AS type, 
         quantity AS total_stok
  FROM blood_stock
  ORDER BY blood_type ASC, rhesus ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Stok Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #333;
    }

    header {
      background: linear-gradient(90deg, #a50000, #7c0000);
      color: white;
      padding: 24px 32px;
      font-size: 1.5rem;
      font-weight: 600;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    main {
      flex: 1;
      padding: 40px 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      background: #fff;
      border-radius: 18px;
      padding: 40px 32px;
      max-width: 800px;
      width: 100%;
      box-shadow: 0 8px 28px rgba(0,0,0,0.12);
      border-top: 5px solid #8B0000;
    }

    .card h4 {
      color: #8B0000;
      font-weight: 600;
      font-size: 1.4rem;
      text-align: center;
      margin-bottom: 30px;
    }

    .table thead {
      background-color: #f8d7da;
      color: #8B0000;
      font-size: 1rem;
    }

    .table td, .table th {
      vertical-align: middle;
      font-size: 0.96rem;
      padding: 14px;
    }

    .btn-back {
      margin-top: 30px;
    }

    .btn-back a {
      font-size: 0.9rem;
    }

    footer {
      background-color: #1a1a1a;
      color: #fff;
      text-align: center;
      padding: 16px;
      font-size: 0.9rem;
      margin-top: auto;
    }

    @media (max-width: 576px) {
      .card {
        padding: 30px 20px;
      }

      .card h4 {
        font-size: 1.2rem;
      }

      header {
        font-size: 1.25rem;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <i class="fas fa-tint me-2"></i>Data Stok Darah Terkini
</header>

<main>
  <div class="card">
    <h4><i class="fas fa-burn me-2"></i>Jumlah Kantong Darah Tersedia</h4>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th>Golongan Darah</th>
            <th>Jumlah Kantong</th>
          </tr>
        </thead>
        <tbody>
          <?php while($s = mysqli_fetch_assoc($stok)): ?>
          <tr>
            <td><strong><?= $s['type'] ?></strong></td>
            <td><?= $s['total_stok'] ?> kantong</td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="text-start btn-back">
      <a href="dashboard.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
      </a>
    </div>
  </div>
</main>


</body>
</html>

