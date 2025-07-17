<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    
    // Validasi input
    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            // Cek apakah username sudah terdaftar
            $check_username = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
            if (mysqli_num_rows($check_username) > 0) {
                $error = "Username sudah terdaftar. Silakan gunakan username lain.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user baru
                $insert_query = "INSERT INTO users (full_name, username, email, password, phone, role, is_active, created_at) 
                                VALUES ('$full_name', '$username', '$email', '$hashed_password', '$phone', 'user', 1, NOW())";
                
                if (mysqli_query($conn, $insert_query)) {
                    $success = "Registrasi berhasil! Silakan login dengan akun Anda.";
                } else {
                    $error = "Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar - Sistem Donor Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at top left, #fff4f4, #ffeaea);
      min-height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px 0;
    }
    .register-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
      max-width: 500px;
      width: 100%;
    }
    .register-box h4 {
      color: #b71c1c;
      font-weight: 700;
      margin-bottom: 20px;
      text-align: center;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-danger {
      font-weight: 600;
      border-radius: 10px;
    }
    .back-link {
      margin-top: 15px;
      display: block;
      text-align: center;
    }
    .logo-top {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }
    .logo-top img {
      height: 60px;
    }
    .error-message {
      color: #d32f2f;
      font-size: 0.95rem;
      margin-bottom: 15px;
      text-align: center;
      background: #ffebee;
      padding: 10px;
      border-radius: 8px;
    }
    .success-message {
      color: #2e7d32;
      font-size: 0.95rem;
      margin-bottom: 15px;
      text-align: center;
      background: #e8f5e8;
      padding: 10px;
      border-radius: 8px;
    }
    .login-link {
      text-align: center;
      margin-top: 15px;
    }
  </style>
</head>
<body>

  <div class="register-box">
    <div class="logo-top">
      <img src="assets/donordarah.png" alt="Logo Donor Darah">
    </div>
    <h4>Daftar Akun Baru</h4>

    <?php if (!empty($error)) : ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
      <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label" for="full_name">Nama Lengkap</label>
          <input id="full_name" type="text" name="full_name" class="form-control" required autofocus value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label" for="username">Username</label>
          <input id="username" type="text" name="username" class="form-control" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label" for="email">Email</label>
          <input id="email" type="email" name="email" class="form-control" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label" for="phone">No. Telepon</label>
          <input id="phone" type="tel" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label" for="password">Password</label>
          <input id="password" type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label" for="confirm_password">Konfirmasi Password</label>
          <input id="confirm_password" type="password" name="confirm_password" class="form-control" required minlength="6">
        </div>
      </div>
      
      <button type="submit" class="btn btn-danger w-100">Daftar</button>
    </form>

    <div class="login-link">
      <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-decoration-none text-danger fw-bold">Login di sini</a></p>
    </div>

    <a href="index.php" class="back-link text-decoration-none text-danger">← Kembali ke Beranda</a>
  </div>

</body>
</html>
