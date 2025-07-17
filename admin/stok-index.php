<?php
include '../session.php';
include '../koneksi.php';

if ($_SESSION['role_id'] != 1) {
  header("Location: ../login-admin.php");
  exit;
}

$data = mysqli_query($conn, "
  SELECT s.id, b.type AS blood_type, s.jumlah
  FROM stock s
  JOIN blood_types b ON s.blood_type_id = b.id
  ORDER BY b.type ASC
");
?>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead>
      <tr>
        <th>No</th>
        <th>Golongan Darah</th>
        <th>Jumlah Kantong</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while($row = mysqli_fetch_assoc($data)): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['blood_type']) ?></td>
        <td><?= (int)$row['jumlah'] ?> Kantong</td>
        <td>
          <a href="stok-edit.php?id=<?= $row['id'] ?>" class="btn btn-aksi btn-edit"><i class="fas fa-edit"></i></a>
          <a href="stok-hapus.php?id=<?= $row['id'] ?>&redirect=beranda-admin.php" class="btn btn-aksi btn-delete" onclick="return confirm('Yakin hapus stok ini?')"><i class="fas fa-trash-alt"></i></a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

