<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

if (!$id) {
  header("Location: logs.php");
  exit;
}

// Ambil data log
$query = "
  SELECT logs.*, users.name 
  FROM logs 
  JOIN users ON logs.user_id = users.id
  WHERE logs.id = '$id'
";
$result = mysqli_query($conn, $query);
$log = mysqli_fetch_assoc($result);

if (!$log) {
  echo "Log tidak ditemukan!";
  exit;
}

// Ambil data user untuk dropdown
$users = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");

$error = '';

// Default nilai form dari database
$selected_user_id = $log['user_id'];
$action_value = $log['action']; // Kolom 'action' di tabel logs

// Kalau form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selected_user_id = $_POST['user_id'] ?? '';
  $action_value = $_POST['action'] ?? '';

  // Escape input user
  $selected_user_id = mysqli_real_escape_string($conn, $selected_user_id);
  $action_value = trim(mysqli_real_escape_string($conn, $action_value));

  if (empty($selected_user_id) || empty($action_value)) {
    $error = "Semua field harus diisi.";
  } else {
    $update_query = "UPDATE logs SET user_id='$selected_user_id', action='$action_value' WHERE id='$id'";
    if (mysqli_query($conn, $update_query)) {
      header("Location: logs.php");
      exit;
    } else {
      $error = "Gagal memperbarui log: " . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Log Aktivitas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Edit Log Aktivitas</h3>
    <?php if(!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="user_id" class="form-label">Pengguna</label>
        <select name="user_id" id="user_id" class="form-select" required>
          <?php while($user = mysqli_fetch_assoc($users)): ?>
            <option value="<?= $user['id'] ?>" <?= ($user['id'] == $selected_user_id) ? 'selected' : '' ?>>
              <?= htmlspecialchars($user['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="action" class="form-label">Aktivitas</label>
        <input type="text" name="action" id="action" class="form-control" value="<?= htmlspecialchars($action_value) ?>" required />
      </div>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="logs.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</body>
</html>

