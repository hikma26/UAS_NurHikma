<?php
include 'auth_check.php';

// Cek jika bukan admin
if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #fdfdfd;
      font-family: 'Poppins', sans-serif;
    }

    .container {
      max-width: 500px;
      margin-top: 50px;
    }

    .btn-primary {
      background-color: #b71c1c;
      border: none;
    }

    .btn-primary:hover {
      background-color: #880e0e;
    }
  </style>
</head>
<body>
  <div class="container">
    <h4 class="mb-4">Tambah Pengguna</h4>
    <form action="proses-tambah-user.php" method="post">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
          <option value="">-- Pilih Role --</option>
          <option value="admin">Admin</option>
          <option value="petugas">Petugas</option>
          <option value="user">User</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Simpan</button>
    </form>
  </div>
</body>
</html>

