<?php
include 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $event_date = $_POST['event_date'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];
  $location = $_POST['location'];
  $description = $_POST['description'];

  $query = "INSERT INTO events (event_date, start_time, end_time, location, description) VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'sssss', $event_date, $start_time, $end_time, $location, $description);
  
  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Jadwal berhasil ditambahkan!'); window.location.href='welcome.php';</script>";
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
  <title>Tambah Jadwal Donor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">Tambah Jadwal Donor</h3>
    <form method="post">
      <div class="mb-3">
        <label for="event_date" class="form-label">Tanggal Event</label>
        <input type="date" name="event_date" id="event_date" class="form-control" required>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="start_time" class="form-label">Waktu Mulai</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="end_time" class="form-label">Waktu Selesai</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="location" class="form-label">Lokasi</label>
        <input type="text" name="location" id="location" class="form-control" required placeholder="Contoh: PMI Mamuju Tengah">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Deskripsi tambahan tentang event donor darah"></textarea>
      </div>
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="welcome.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</body>
</html>

