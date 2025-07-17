<?php
// /admin/edit-user.php
include 'auth_check.php';
if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $role = mysqli_real_escape_string($conn, $_POST['role']);
  
  // Update password hanya jika diisi
  if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET full_name='$name', email='$email', password='$password', role='$role' WHERE id=$id");
  } else {
    mysqli_query($conn, "UPDATE users SET full_name='$name', email='$email', role='$role' WHERE id=$id");
  }
  
  header("Location: users.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h4>Edit Pengguna</h4>
    <form method="post">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['full_name']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
          <option value="">-- Pilih Role --</option>
          <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="petugas" <?= $data['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
          <option value="user" <?= $data['role'] == 'user' ? 'selected' : '' ?>>User</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Password (kosongkan jika tidak ingin mengubah)</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
      </div>
      <button class="btn btn-primary">Update</button>
      <a href="users.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>

