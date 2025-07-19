<?php
include 'auth_check.php';

// Query untuk mendapatkan data permintaan darah dengan handling error
$data = false;
try {
    // First try to get column names from table
    $columns_result = mysqli_query($conn, "SHOW COLUMNS FROM blood_requests");
    if ($columns_result) {
        $available_columns = [];
        while ($col = mysqli_fetch_assoc($columns_result)) {
            $available_columns[] = $col['Field'];
        }
        
        // Build query with available columns
        $select_columns = [];
        $required_columns = ['id', 'patient_name', 'hospital', 'blood_type', 'rhesus', 'quantity_needed', 
                            'contact_person', 'contact_phone', 'notes', 'urgency', 'status', 'request_date'];
        
        foreach ($required_columns as $col) {
            if (in_array($col, $available_columns)) {
                $select_columns[] = $col;
            }
        }
        
        if (!empty($select_columns)) {
            $query = "SELECT " . implode(', ', $select_columns) . " FROM blood_requests ORDER BY id DESC";
            $data = mysqli_query($conn, $query);
        }
    }
} catch (Exception $e) {
    // Fallback query
    $data = mysqli_query($conn, "SELECT * FROM blood_requests ORDER BY id DESC");
}

if (!$data) {
    echo "<div class='alert alert-danger'>Error loading data: " . mysqli_error($conn) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Data Permintaan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../../assets/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>
<body>

<header class="header-main">Kelola Permintaan Darah</header>

<div class="container my-5">
  <div class="card shadow p-4 rounded-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="text-danger mb-0"><i class="fas fa-hand-holding-medical me-2"></i>Permintaan Darah</h5>
      <a href="tambah-permintaan.php" class="btn btn-danger btn-sm"><i class="fas fa-plus"></i> Tambah Permintaan</a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-danger">
          <tr>
            <th>#</th>
            <th>Pasien</th>
            <th>Golongan</th>
            <th>Jumlah</th>
            <th>Kontak</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if($data && mysqli_num_rows($data) > 0): ?>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($data)) : ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($r['patient_name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars(($r['blood_type'] ?? '') . ($r['rhesus'] ?? '')) ?></td>
              <td><?= (int)($r['quantity_needed'] ?? 0) ?> kantong</td>
              <td><?= htmlspecialchars($r['contact_phone'] ?? 'N/A') ?></td>
              <td>
                <?php
                $urgency = $r['urgency'] ?? 'normal';
                $status = $r['status'] ?? 'pending';
                $urgency_class = '';
                $status_class = '';
                switch($urgency) {
                  case 'urgent': $urgency_class = 'bg-danger'; break;
                  case 'normal': $urgency_class = 'bg-warning'; break;
                  default: $urgency_class = 'bg-secondary';
                }
                switch($status) {
                  case 'pending': $status_class = 'bg-warning'; break;
                  case 'approved': $status_class = 'bg-success'; break;
                  case 'rejected': $status_class = 'bg-danger'; break;
                  case 'completed': $status_class = 'bg-primary'; break;
                  default: $status_class = 'bg-secondary';
                }
                ?>
                <span class="badge <?= $urgency_class ?>"><?= ucfirst($urgency) ?></span><br>
                <span class="badge <?= $status_class ?>"><?= ucfirst($status) ?></span>
              </td>
              <td><?= date('d/m/Y', strtotime($r['request_date'])) ?></td>
              <td>
                <!-- Form Update Status -->
                <form action="update-status-permintaan.php" method="post" class="d-inline mb-1">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                  <div class="d-flex flex-column gap-1">
                    <select name="status" class="form-select form-select-sm">
                      <option value="pending" <?= ($r['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="approved" <?= ($r['status'] ?? 'pending') == 'approved' ? 'selected' : '' ?>>Approved</option>
                      <option value="rejected" <?= ($r['status'] ?? 'pending') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                      <option value="completed" <?= ($r['status'] ?? 'pending') == 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                    <select name="urgency" class="form-select form-select-sm">
                      <option value="normal" <?= ($r['urgency'] ?? 'normal') == 'normal' ? 'selected' : '' ?>>Normal</option>
                      <option value="urgent" <?= ($r['urgency'] ?? 'normal') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                  </div>
                </form>
                
                <!-- Tombol Edit Detail -->
                <a href="edit-permintaan.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm mt-1"><i class="fas fa-edit"></i></a>
                
                <!-- Tombol Hapus -->
                <form action="hapus-permintaan.php" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus permintaan ini?');">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                  <button type="submit" class="btn btn-danger btn-sm mt-1"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endwhile ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center">Belum ada data permintaan darah.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <a href="index.php" class="btn btn-secondary mt-3">← Kembali</a>
  </div>
</div>

<footer class="footer-main">
  &copy; 2025 Sistem Donor Darah – Admin
</footer>

</body>
</html>

