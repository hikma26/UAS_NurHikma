<?php
session_start();
require_once \"../config/koneksi.php\";

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT d.*, b.id AS blood_type_id, b.type AS blood_type, s.id AS status_id, s.nama_status
    FROM donors d
    JOIN blood_types b ON d.blood_type_id = b.id
    JOIN statuses s ON d.status_id = s.id
    WHERE d.id = $id
");

if (!$query || mysqli_num_rows($query) == 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($query);
$blood_types = mysqli_query($conn, "SELECT * FROM blood_types");
$statuses = mysqli_query($conn, "SELECT * FROM statuses");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pendonor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
    <h3 class="mb-4 text-danger">Edit Data Pendonor</h3>
    <form action="pendonor-update.php" method="post">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Golongan Darah</label>
            <select name="blood_type_id" class="form-select" required>
                <?php while ($bt = mysqli_fetch_assoc($blood_types)) : ?>
                    <option value="<?= $bt['id'] ?>" <?= $bt['id'] == $data['blood_type_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($bt['type']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="gender" class="form-select" required>
                <option value="Laki-laki" <?= $data['gender'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $data['gender'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" class="form-control" value="<?= $data['birth_date'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kecamatan</label>
            <select name="district" class="form-select" required>
                <?php 
                $districts = ['Topoyo', 'Budong-Budong', 'Pangale', 'Tobadak', 'Karossa'];
                foreach ($districts as $d) {
                    $selected = ($data['district'] == $d) ? 'selected' : '';
                    echo "<option value=\"$d\" $selected>$d</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Desa/Kelurahan</label>
            <input type="text" name="village" class="form-control" value="<?= htmlspecialchars($data['village']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Berat Badan</label>
            <input type="number" name="weight" class="form-control" value="<?= $data['weight'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Riwayat Penyakit</label>
            <textarea name="medical_history" class="form-control" rows="3"><?= htmlspecialchars($data['medical_history']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select" required>
                <?php while ($s = mysqli_fetch_assoc($statuses)) : ?>
                    <option value="<?= $s['id'] ?>" <?= $s['id'] == $data['status_id'] ? 'selected' : '' ?>>
                        <?= $s['nama_status'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="laporan.php" class="btn btn-secondary ms-2">Batal</a>
    </form>
</div>
</body>
</html>

