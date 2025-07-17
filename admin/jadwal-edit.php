<?php
include 'auth_check.php';

$id = $_GET['id'];
$redirect = 'welcome.php'; // kembali ke beranda admin setelah update

$result = mysqli_query($conn, "SELECT * FROM events WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);

if (!$data) {
  echo "<script>alert('Jadwal tidak ditemukan!'); window.location.href='welcome.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $event_date = $_POST['event_date'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];
  $location = $_POST['location'];
  $description = $_POST['description'];

  $query = "UPDATE events SET event_date = ?, start_time = ?, end_time = ?, location = ?, description = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'sssssi', $event_date, $start_time, $end_time, $location, $description, $id);
  
  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Jadwal berhasil diperbarui!'); window.location.href='$redirect';</script>";
  } else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Jadwal Donor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Edit Jadwal Donor</h3>
    <form method="post">
      <div class="mb-3">
        <label for="event_date" class="form-label">Tanggal</label>
        <input type="date" name="event_date" id="event_date" class="form-control" value="<?= isset($data['event_date']) ? $data['event_date'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label for="start_time" class="form-label">Waktu Mulai</label>
        <input type="time" name="start_time" id="start_time" class="form-control" value="<?= isset($data['start_time']) ? $data['start_time'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label for="end_time" class="form-label">Waktu Selesai</label>
        <input type="time" name="end_time" id="end_time" class="form-control" value="<?= isset($data['end_time']) ? $data['end_time'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label for="location" class="form-label">Tempat</label>
        <input type="text" name="location" id="location" class="form-control" value="<?= isset($data['location']) ? $data['location'] : '' ?>" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Keterangan</label>
        <textarea name="description" id="description" class="form-control" rows="3"><?= isset($data['description']) ? $data['description'] : '' ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="<?= $redirect ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</body>
</html>

