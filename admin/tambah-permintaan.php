<?php
include 'auth_check.php';

// Blood types array
$blood_types = [
    'A+' => 'A+', 'A-' => 'A-',
    'B+' => 'B+', 'B-' => 'B-', 
    'AB+' => 'AB+', 'AB-' => 'AB-',
    'O+' => 'O+', 'O-' => 'O-'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
    $blood_type_selected = $_POST['blood_type_id'];
    $quantity_needed = (int)$_POST['quantity_needed'];
    $contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $urgency = mysqli_real_escape_string($conn, $_POST['urgency']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $request_date = date('Y-m-d');
    $needed_date = date('Y-m-d', strtotime('+1 day'));
    
    // Parse blood type and rhesus
    $blood_type = substr($blood_type_selected, 0, -1); // A, B, AB, O
    $rhesus = substr($blood_type_selected, -1); // +, -

    $query = "INSERT INTO blood_requests (patient_name, hospital, blood_type, rhesus, quantity_needed, urgency, request_date, needed_date, contact_person, contact_phone, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssississss", $patient_name, $hospital, $blood_type, $rhesus, $quantity_needed, $urgency, $request_date, $needed_date, $contact_person, $contact_phone, $notes, $status);
        $success = mysqli_stmt_execute($stmt);
        
        if ($success) {
            echo "<script>alert('Permintaan darah berhasil ditambahkan!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
        } else {
            echo "<script>alert('Gagal menambahkan permintaan darah!'); window.history.back();</script>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error dalam query!'); window.history.back();</script>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Permintaan Darah</title>
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

    .form-container {
      background: white;
      padding: 2rem;
      border-radius: var(--border-radius);
      max-width: 700px;
      margin: 2rem auto;
      box-shadow: var(--shadow-lg);
      border: 1px solid #e5e7eb;
    }

    .form-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .form-label {
      font-weight: 500;
      color: var(--secondary-color);
      margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 1rem;
      transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
      outline: none;
    }

    .btn-modern {
      border-radius: 8px;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      transition: all 0.2s ease;
      border: none;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1rem;
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

    .btn-secondary-modern {
      background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
      color: white;
      box-shadow: var(--shadow-sm);
    }

    .btn-secondary-modern:hover {
      background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
      box-shadow: var(--shadow-md);
      transform: translateY(-1px);
      color: white;
    }

    .form-actions {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      margin-top: 2rem;
    }

    @media (max-width: 768px) {
      .form-container {
        margin: 1rem;
        padding: 1.5rem;
      }

      .form-actions {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h1 class="form-title">
      <i class="fas fa-plus-circle"></i>
      Tambah Permintaan Darah
    </h1>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama Pasien</label>
        <input type="text" name="patient_name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Rumah Sakit/Hospital</label>
        <input type="text" name="hospital" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Golongan Darah</label>
        <select name="blood_type_id" class="form-select" required>
          <option value="">Pilih Golongan Darah</option>
          <?php foreach($blood_types as $key => $value): ?>
            <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Jumlah Dibutuhkan (kantong)</label>
        <input type="number" name="quantity_needed" class="form-control" required min="1" value="1">
      </div>

      <div class="mb-3">
        <label class="form-label">Kontak Person</label>
        <input type="text" name="contact_person" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="contact_phone" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Urgency</label>
        <select name="urgency" class="form-select" required>
          <option value="normal">Normal</option>
          <option value="urgent">Urgent</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="notes" class="form-control" rows="3"></textarea>
      </div>

      <div class="form-actions">
        <a href="permintaan.php" class="btn-modern btn-secondary-modern">
          <i class="fas fa-arrow-left"></i>
          Batal
        </a>
        <button type="submit" class="btn-modern btn-primary-modern">
          <i class="fas fa-save"></i>
          Tambah Permintaan
        </button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
