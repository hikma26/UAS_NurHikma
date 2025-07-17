<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

// Ambil data user untuk dropdown
$users = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");

$error = '';
$selected_user_id = '';
$activity_input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selected_user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
  $activity_input = trim(mysqli_real_escape_string($conn, $_POST['activity']));

  if (empty($selected_user_id) || empty($activity_input)) {
    $error = "Semua field harus diisi.";
  } else {
    $query = "INSERT INTO logs (user_id, action) VALUES ('$selected_user_id', '$activity_input')";
    if (mysqli_query($conn, $query)) {
      header("Location: logs.php");
      exit;
    } else {
      $error = "Gagal menambahkan log: " . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Log Aktivitas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Tambah Log Aktivitas</h3>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="user_id" class="form-label">Pengguna</label>
        <select name="user_id" id="user_id" class="form-select" required>
          <option value="" disabled <?= $selected_user_id == '' ? 'selected' : '' ?>>Pilih pengguna</option>
          <?php 
          // reset pointer supaya bisa diulang
          mysqli_data_seek($users, 0);
          while($user = mysqli_fetch_assoc($users)): ?>
            <option value="<?= $user['id'] ?>" <?= $user['id'] == $selected_user_id ? 'selected' : '' ?>>
              <?= htmlspecialchars($user['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="activity" class="form-label">Aktivitas</label>
        <input type="text" name="activity" id="activity" class="form-control" value="<?= htmlspecialchars($activity_input) ?>" required />
      </div>
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="logs.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</body>
</html>

