<?php
// Include authentication check
include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      width: 250px;
      background-color: #8B0000;
      color: white;
      display: flex;
      flex-direction: column;
      z-index: 2000;
      transition: left 0.3s ease;
    }

    .sidebar h4 {
      padding: 20px;
      background-color: #700000;
      margin: 0;
      font-size: 1.3rem;
    }

    .nav-links {
      flex: 1;
      padding: 10px 0;
      overflow-y: auto;
    }

    .nav-links a {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      color: white;
      text-decoration: none;
      border-radius: 0 25px 25px 0;
      font-size: 0.95rem;
      transition: background 0.2s;
      cursor: pointer;
    }

    .nav-links a:hover {
      background-color: #a30000;
    }

    .nav-links i {
      margin-right: 10px;
    }

    .logout {
      padding: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logout a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .logout i {
      margin-right: 10px;
    }

    .content {
      margin-left: 250px;
      padding: 0;
      width: calc(100% - 250px);
      transition: margin-left 0.3s ease;
    }

    iframe {
      width: 100%;
      height: 100vh;
      border: none;
      display: block;
    }

    .toggle-btn {
      display: none;
    }

    @media (max-width: 768px) {
      .sidebar {
        left: -250px;
      }

      .sidebar.active {
        left: 0;
      }

      .toggle-btn {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #8B0000;
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 4px;
        font-size: 1.2rem;
        z-index: 2100;
        cursor: pointer;
      }

      .content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Tombol garis tiga -->
<button class="toggle-btn" onclick="toggleSidebar()">
  <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h4><i class="fas fa-tachometer-alt me-2"></i>Admin Panel</h4>
  <div class="nav-links">
    <a onclick="loadPage('welcome.php')"><i class="fas fa-home"></i> Beranda</a>
    <a onclick="loadPage('users.php')"><i class="fas fa-users"></i> Pengguna</a>
                <a onclick="loadPage('laporan.php')"><i class="fas fa-chart-bar"></i> Laporan</a>
                <a onclick="loadPage('manage_notifications.php')"><i class="fas fa-bullhorn"></i> Notifikasi Marquee</a>
                <a onclick="loadPage('settings.php')"><i class="fas fa-cogs"></i> Pengaturan Sistem</a>
                <a onclick="loadPage('activity.php')"><i class="fas fa-clock"></i> Aktivitas</a>
  </div>
  <div class="logout">
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
  </div>
</div>

<!-- Konten -->
<div class="content">
  <iframe id="mainFrame" src="welcome.php" title="Konten Admin"></iframe>
</div>

<!-- Script -->
<script>
  function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
  }

  function loadPage(page) {
    document.getElementById("mainFrame").src = page;
    if (window.innerWidth <= 768) {
      document.getElementById("sidebar").classList.remove("active");
    }
  }
</script>

</body>
</html>

