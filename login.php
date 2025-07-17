<?php
session_start();
include 'koneksi.php'; // koneksi ke database

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $pass = trim($_POST['password']);

    // Ambil user berdasarkan email (semua role)
    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND is_active=1");

    if ($q && mysqli_num_rows($q) > 0) {
        $user = mysqli_fetch_assoc($q);

        // Cek password dengan hash verification
        if (password_verify($pass, $user['password'])) {
            // Simpan session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];

            // Redirect berdasarkan role
            if ($user['role'] == 'admin' || $user['role'] == 'petugas') {
                // Admin atau Petugas
                header("Location: admin/index.php");
            } else {
                // User/Pendonor
                header("Location: user/dashboard.php");
            }
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login - Sistem Donor Darah</title>
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
    }
    .login-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
      max-width: 420px;
      width: 100%;
    }
    .login-box h4 {
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
    }
  </style>
</head>
<body>

  <div class="login-box">
    <div class="logo-top">
      <img src="assets/donordarah.png" alt="Logo Donor Darah">
    </div>
    <h4>Login Sistem</h4>

    <?php if (!empty($error)) : ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input id="email" type="email" name="email" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input id="password" type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger w-100">Masuk</button>
    </form>

    <a href="index.php" class="back-link text-decoration-none text-danger">← Kembali ke Beranda</a>
    
    <div class="text-center mt-3">
      <p class="mb-2">Belum punya akun? <a href="register.php" class="text-decoration-none text-danger fw-bold">Daftar di sini</a></p>
      <p class="mb-0"><a href="forgot-password.php" class="text-decoration-none text-muted">Lupa password?</a></p>
    </div>
    
  </div>

</body>
</html>
