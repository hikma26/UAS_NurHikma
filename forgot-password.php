<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';
$step = 1; // Step 1: Verifikasi identitas, Step 2: Reset password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['step']) && $_POST['step'] == '1') {
        // Step 1: Verifikasi identitas
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        
        if (empty($email) || empty($full_name) || empty($username)) {
            $error = "Semua field harus diisi.";
        } else {
            // Cek apakah data cocok dengan database
            $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND full_name='$full_name' AND username='$username'");
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                $_SESSION['reset_user_id'] = $user['id'];
                $_SESSION['reset_email'] = $email;
                $step = 2;
            } else {
                $error = "Data yang Anda masukkan tidak cocok dengan akun yang terdaftar.";
            }
        }
    } elseif (isset($_POST['step']) && $_POST['step'] == '2') {
        // Step 2: Reset password
        if (!isset($_SESSION['reset_user_id'])) {
            $error = "Sesi expired. Silakan mulai dari awal.";
            $step = 1;
        } else {
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            if (empty($new_password) || empty($confirm_password)) {
                $error = "Password baru dan konfirmasi harus diisi.";
                $step = 2;
            } elseif ($new_password !== $confirm_password) {
                $error = "Password dan konfirmasi password tidak cocok.";
                $step = 2;
            } elseif (strlen($new_password) < 6) {
                $error = "Password minimal 6 karakter.";
                $step = 2;
            } else {
                // Update password
                $user_id = $_SESSION['reset_user_id'];
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                $update_result = mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id='$user_id'");
                
                if ($update_result) {
                    unset($_SESSION['reset_user_id']);
                    unset($_SESSION['reset_email']);
                    $success = "Password berhasil direset. Silakan login dengan password baru.";
                    $step = 3; // Success step
                } else {
                    $error = "Terjadi kesalahan saat mereset password.";
                    $step = 2;
                }
            }
        }
    }
}

// Check if user is in step 2
if (isset($_SESSION['reset_user_id']) && $step == 1) {
    $step = 2;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - Sistem Donor Darah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
        .forgot-box {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            max-width: 420px;
            width: 100%;
        }
        .forgot-box h4 {
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
        .success-message {
            color: #2e7d32;
            font-size: 0.95rem;
            margin-bottom: 15px;
            text-align: center;
        }
        .alert {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid;
        }
        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
        .forgot-box {
            max-width: 500px;
        }
    </style>
</head>
<body>

<div class="forgot-box">
    <div class="logo-top">
        <img src="assets/donordarah.png" alt="Logo Donor Darah">
    </div>
    <h4>Lupa Password</h4>

    <?php if (!empty($error)) : ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
        
        <?php if ($step == 3) : ?>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-danger">Login Sekarang</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($step == 1) : ?>
        <!-- Step 1: Verifikasi Identitas -->
        <div class="alert alert-info">
            <small><strong>Verifikasi Identitas:</strong> Masukkan data pribadi Anda untuk memverifikasi kepemilikan akun.</small>
        </div>
        
        <form method="post" autocomplete="off">
            <input type="hidden" name="step" value="1">
            
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" required autofocus value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="full_name">Nama Lengkap</label>
                <input id="full_name" type="text" name="full_name" class="form-control" required value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input id="username" type="text" name="username" class="form-control" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>
            
            <button type="submit" class="btn btn-danger w-100">Verifikasi Data</button>
        </form>
    <?php elseif ($step == 2) : ?>
        <!-- Step 2: Reset Password -->
        <div class="alert alert-info">
            <small><strong>Reset Password:</strong> Data terverifikasi! Masukkan password baru Anda.</small>
        </div>
        
        <form method="post" autocomplete="off">
            <input type="hidden" name="step" value="2">
            
            <div class="mb-3">
                <label class="form-label" for="new_password">Password Baru</label>
                <input id="new_password" type="password" name="new_password" class="form-control" required minlength="6" autofocus>
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="confirm_password">Konfirmasi Password Baru</label>
                <input id="confirm_password" type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-danger w-100">Reset Password</button>
        </form>
    <?php endif; ?>

    <a href="login.php" class="back-link text-decoration-none text-danger">← Kembali ke Login</a>
</div>

</body>
</html>
