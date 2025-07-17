<?php session_start(); ?> 
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sistem Donor Darah - Mamuju Tengah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at top left, #fff4f4, #ffeaea);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #b71c1c;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      height: auto;
      max-height: 70px;
      width: auto;
      margin-right: 15px;
    }

    .logo span {
      font-size: 1.6rem;
      font-weight: 600;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 60px 20px;
      position: relative;
      overflow: hidden;
    }

    main::before {
      content: "\f043";
      font-family: "Font Awesome 5 Free";
      font-weight: 900;
      font-size: 200px;
      color: rgba(220, 20, 60, 0.04);
      position: absolute;
      bottom: -30px;
      right: -20px;
      z-index: 0;
      transform: rotate(-15deg);
    }

    .content {
      position: relative;
      z-index: 1;
      max-width: 800px;
      padding: 45px;
      background-color: #ffffffcc;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
    }

    .content img.illustration {
      width: 130px;
      margin-bottom: 25px;
    }

    .content h1 {
      font-size: 2.6rem;
      font-weight: 700;
      color: #b71c1c;
      margin-bottom: 20px;
    }

    .content p {
      font-size: 1.1rem;
      color: #444;
      margin-bottom: 35px;
      line-height: 1.8;
    }

    .btn-main {
      font-size: 1.1rem;
      padding: 14px 30px;
      border-radius: 10px;
      font-weight: 600;
      background-color: #d32f2f;
      color: white;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .btn-main:hover {
      background-color: #b71c1c;
      box-shadow: 0 8px 16px rgba(200, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    footer {
      background-color: #fff0f0;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
      color: #a00;
      border-top: 1px solid #fdd;
    }

    @media (max-width: 576px) {
      .content h1 {
        font-size: 2rem;
      }

      .btn-main {
        width: 100%;
      }

      header .logo span {
        font-size: 1.3rem;
      }

      .content img.illustration {
        width: 100px;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">
    <img src="assets/logo-mateng.png" alt="Logo Mamuju Tengah">
    <span>Sistem Donor Darah</span>
  </div>
</header>

<main>
  <div class="content">
    <img src="assets/donordarah.png" class="illustration" alt="Ilustrasi Donor Darah">
    <h1><i class="fas fa-tint me-2 text-danger"></i>Setetes Darah, Sejuta Harapan</h1>
    <p>
      Selamat datang di <strong>Sistem Donor Darah Kabupaten Mamuju Tengah</strong>.  
      Kami hadir untuk memfasilitasi pendonor dan penerima darah dengan proses yang <strong>mudah</strong>,  
      <strong>aman</strong>, dan <strong>terverifikasi</strong>.  
      Bergabunglah bersama kami untuk menyelamatkan lebih banyak nyawa hari ini.
    </p>
    <a href="login.php" class="btn-main"><i class="fas fa-sign-in-alt me-2"></i>Masuk ke Sistem</a>
  </div>
</main>

<footer>
  &copy; 2025 Sistem Donor Darah • Kabupaten Mamuju Tengah
</footer>

</body>
</html>
