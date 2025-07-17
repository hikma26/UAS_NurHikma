<?php
// ubah-password.php
include '../session.php';
include '../koneksi.php';
$uid = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lama = $_POST['old'];
    $baru = password_hash($_POST['new'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE id=$uid");
    $user = mysqli_fetch_assoc($cek);
    
    // Check if user exists
    if (!$user) {
        $pesan = "Error: User tidak ditemukan!";
    } else if (password_verify($lama, $user['password'])) {
        mysqli_query($conn, "UPDATE users SET password='$baru' WHERE id=$uid");
        $pesan = "Password berhasil diubah!";
    } else {
        $pesan = "Password lama salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ubah Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Ubah Password</h3>
  <?php if (isset($pesan)) echo "<div class='alert alert-info'>$pesan</div>"; ?>
  <form method="post">
    <div class="mb-3"><label>Password Lama</label><input type="password" name="old" class="form-control" required></div>
    <div class="mb-3"><label>Password Baru</label><input type="password" name="new" class="form-control" required></div>
    <button type="submit" class="btn btn-danger">Simpan</button>
  </form>
</div>
</body>
</html>

