<?php
session_start();
require_once "../koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

$jadwal = mysqli_query($conn, "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC, start_time ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Donor Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #8B0000;
      color: #fff;
      padding: 20px;
      text-align: center;
      font-size: 1.5rem;
      font-weight: 600;
    }

    .container {
      flex: 1;
      padding: 40px 20px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      padding: 30px;
    }

    .card h4 {
      color: #8B0000;
      font-weight: 600;
      margin-bottom: 25px;
      text-align: center;
    }

    table.table {
      border: 1px solid #dee2e6;
    }

    table.table th {
      background-color: #a71d2a;
      color: #fff;
      border: 1px solid #dee2e6;
    }

        table.table td {
      background-color: #fff;
      color: #333;
      border: 1px solid #dee2e6;
    }

    .footer-main {
      background-color: #f3e8e8;
      color: #8B0000;
      padding: 16px 10px;
      text-align: center;
      font-size: 0.9rem;
      border-top: 1px solid #ddd;
    }

    @media (max-width: 768px) {
      .card {
        padding: 20px;
      }

      table.table th, table.table td {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<header>📅 Jadwal Donor Darah</header>

<main class="container">
  <div class="card">
    <h4><i class="fas fa-calendar-alt me-2"></i>Jadwal Kegiatan Donor di Mamuju Tengah</h4>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Tempat</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          if ($jadwal && mysqli_num_rows($jadwal) > 0) {
            while ($row = mysqli_fetch_assoc($jadwal)) {
              echo "<tr>
                      <td>{$no}</td>
                      <td>" . date('d-m-Y', strtotime($row['event_date'])) . "</td>
                      <td>" . date('H:i', strtotime($row['start_time'])) . " - " . date('H:i', strtotime($row['end_time'])) . " WITA</td>
                      <td>{$row['location']}</td>
                      <td>" . htmlspecialchars($row['description'] ?? $row['title']) . "</td>
                    </tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='5' class='text-muted'>Belum ada jadwal donor</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class="mt-4">
      <a href="dashboard.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
      </a>
    </div>
  </div>
</main>

<footer class="footer-main">
  &copy; <?= date('Y') ?> Sistem Donor Darah • Kabupaten Mamuju Tengah
</footer>

</body>
</html>


