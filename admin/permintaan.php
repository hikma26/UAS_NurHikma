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
            $query = "SELECT " . implode(', ', $select_columns) . " FROM blood_requests ORDER BY 
                      CASE WHEN urgency = 'urgent' THEN 1 ELSE 2 END, id DESC";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  
  <style>
    :root {
      --primary-color: #dc2626;
      --secondary-color: #1f2937;
      --success-color: #059669;
      --warning-color: #d97706;
      --danger-color: #dc2626;
      --info-color: #0284c7;
      --light-color: #f8fafc;
      --dark-color: #111827;
      --border-radius: 12px;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      color: var(--dark-color);
      min-height: 100vh;
    }

    .header-main {
      background: linear-gradient(135deg, var(--primary-color) 0%, #b91c1c 100%);
      color: white;
      padding: 20px 0;
      text-align: center;
      font-weight: 600;
      font-size: 1.25rem;
      box-shadow: var(--shadow-md);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .main-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }

    .content-card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      border: 1px solid #e5e7eb;
    }

    .card-header {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      padding: 1.5rem 2rem;
      border-bottom: 2px solid #e5e7eb;
      display: flex;
      justify-content: between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-modern {
      border-radius: 8px;
      font-weight: 500;
      padding: 0.5rem 1rem;
      transition: all 0.2s ease;
      border: none;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary-modern {
      background: linear-gradient(135deg, var(--primary-color) 0%, #b91c1c 100%);
      color: white;
      box-shadow: var(--shadow-sm);
    }

    .btn-primary-modern:hover {
      background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
      box-shadow: var(--shadow-md);
      transform: translateY(-1px);
      color: white;
    }

    .btn-sm-modern {
      padding: 0.25rem 0.75rem;
      font-size: 0.875rem;
      border-radius: 6px;
    }

    .table-modern {
      margin: 0;
      background: white;
    }

    .table-modern thead {
      background: linear-gradient(135deg, var(--secondary-color) 0%, #374151 100%);
      color: white;
    }

    .table-modern thead th {
      font-weight: 600;
      padding: 1rem 0.75rem;
      border: none;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
    }

    .table-modern tbody td {
      padding: 1rem 0.75rem;
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
    }

    .table-modern tbody tr:hover {
      background-color: #f8fafc;
    }

    .badge-modern {
      padding: 0.375rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .badge-urgent {
      background-color: #fee2e2;
      color: #dc2626;
    }

    .badge-normal {
      background-color: #fef3c7;
      color: #d97706;
    }

    .badge-pending {
      background-color: #fef3c7;
      color: #d97706;
    }

    .badge-approved {
      background-color: #d1fae5;
      color: #059669;
    }

    .badge-rejected {
      background-color: #fee2e2;
      color: #dc2626;
    }

    .badge-completed {
      background-color: #dbeafe;
      color: #0284c7;
    }

    .form-select-modern {
      border: 2px solid #e5e7eb;
      border-radius: 6px;
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .form-select-modern:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
      outline: none;
    }

    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      min-width: 200px;
    }

    .status-update-form {
      background: #f8fafc;
      padding: 0.75rem;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
    }

    .btn-success-modern {
      background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%);
      color: white;
    }

    .btn-warning-modern {
      background: linear-gradient(135deg, var(--warning-color) 0%, #c2410c 100%);
      color: white;
    }

    .btn-danger-modern {
      background: linear-gradient(135deg, var(--danger-color) 0%, #b91c1c 100%);
      color: white;
    }

    .footer-modern {
      background: var(--secondary-color);
      color: #9ca3af;
      text-align: center;
      padding: 1.5rem;
      font-size: 0.875rem;
      margin-top: 3rem;
    }

    @media (max-width: 768px) {
      .main-container {
        padding: 1rem 0.5rem;
      }
      
      .card-header {
        flex-direction: column;
        align-items: stretch;
      }
      
      .action-buttons {
        min-width: auto;
      }
    }
  </style>
</head>
<body>

<header class="header-main">
  <i class="fas fa-hand-holding-medical me-2"></i>
  Kelola Permintaan Darah
</header>

<div class="main-container">
  <div class="content-card">
    <div class="card-header">
      <h1 class="card-title">
        <i class="fas fa-list-alt"></i>
        Data Permintaan Darah
      </h1>
      <a href="tambah-permintaan.php" class="btn-modern btn-primary-modern">
        <i class="fas fa-plus"></i>
        Tambah Permintaan
      </a>
    </div>
    
    <div class="table-responsive">
      <table class="table table-modern">
        <thead>
          <tr>
            <th>#</th>
            <th>Pasien</th>
            <th>Golongan</th>
            <th>Jumlah</th>
            <th>Kontak</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if($data && mysqli_num_rows($data) > 0): ?>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($data)) : ?>
            <tr>
              <td><strong><?= $no++ ?></strong></td>
              <td>
                <div class="fw-semibold"><?= htmlspecialchars($r['patient_name'] ?? 'N/A') ?></div>
                <small class="text-muted"><?= htmlspecialchars($r['hospital'] ?? 'N/A') ?></small>
              </td>
              <td>
                <span class="badge badge-modern" style="background: #dc2626; color: white;">
                  <?= htmlspecialchars(($r['blood_type'] ?? '') . ($r['rhesus'] ?? '')) ?>
                </span>
              </td>
              <td>
                <strong><?= (int)($r['quantity_needed'] ?? 0) ?></strong> kantong
              </td>
              <td>
                <i class="fas fa-phone text-muted me-1"></i>
                <?= htmlspecialchars($r['contact_phone'] ?? 'N/A') ?>
              </td>
              <td>
                <?php
                $urgency = $r['urgency'] ?? 'normal';
                $status = $r['status'] ?? 'pending';
                ?>
                <div class="d-flex flex-column gap-1">
                  <span class="badge-modern badge-<?= $urgency ?>"><?= ucfirst($urgency) ?></span>
                  <span class="badge-modern badge-<?= $status ?>"><?= ucfirst($status) ?></span>
                </div>
              </td>
              <td>
                <i class="fas fa-calendar text-muted me-1"></i>
                <?= date('d/m/Y', strtotime($r['request_date'] ?? 'now')) ?>
              </td>
              <td>
                <div class="action-buttons">
                  <!-- Status Update Form -->
                  <form action="update-status-permintaan.php" method="post" class="status-update-form">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                    <div class="d-flex flex-column gap-2">
                      <select name="status" class="form-select form-select-sm form-select-modern">
                        <option value="pending" <?= ($r['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($r['status'] ?? 'pending') == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($r['status'] ?? 'pending') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="completed" <?= ($r['status'] ?? 'pending') == 'completed' ? 'selected' : '' ?>>Completed</option>
                      </select>
                      <select name="urgency" class="form-select form-select-sm form-select-modern">
                        <option value="normal" <?= ($r['urgency'] ?? 'normal') == 'normal' ? 'selected' : '' ?>>Normal</option>
                        <option value="urgent" <?= ($r['urgency'] ?? 'normal') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                      </select>
                      <button type="submit" class="btn-modern btn-sm-modern btn-success-modern">
                        <i class="fas fa-check"></i>
                        Update
                      </button>
                    </div>
                  </form>
                  
                  <!-- Action Buttons -->
                  <div class="d-flex gap-1 mt-2">
                    <a href="edit-permintaan.php?id=<?= $r['id'] ?>" class="btn-modern btn-sm-modern btn-warning-modern" title="Edit Detail">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="hapus-permintaan.php" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus permintaan ini?');">
                      <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                      <button type="submit" class="btn-modern btn-sm-modern btn-danger-modern" title="Hapus">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
            <?php endwhile ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="text-muted">
                  <i class="fas fa-inbox fa-3x mb-3"></i>
                  <br>
                  Belum ada data permintaan darah.
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<footer class="footer-modern">
  <i class="fas fa-copyright me-1"></i>
  <?= date('Y') ?> Sistem Donor Darah – Kabupaten Mamuju Tengah
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
