<?php
include 'auth_check.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: permintaan.php");
    exit;
}

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_requests WHERE id = $id"));
if (!$data) {
    echo "<script>alert('Data tidak ditemukan');location.href='permintaan.php';</script>";
    exit;
}

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
    
    // Parse blood type and rhesus
    $blood_type = substr($blood_type_selected, 0, -1); // A, B, AB, O
    $rhesus = substr($blood_type_selected, -1); // +, -

    $query = "UPDATE blood_requests SET patient_name=?, hospital=?, blood_type=?, rhesus=?, quantity_needed=?, contact_person=?, contact_phone=?, notes=?, urgency=?, status=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssisssssi", $patient_name, $hospital, $blood_type, $rhesus, $quantity_needed, $contact_person, $contact_phone, $notes, $urgency, $status, $id);
        $success = mysqli_stmt_execute($stmt);
        
        if ($success) {
            echo "<script>alert('Data berhasil diupdate!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
        } else {
            echo "<script>alert('Gagal mengupdate data!'); window.history.back();</script>";
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
  <title>Edit Permintaan Darah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      padding: 25px;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }
    .btn-success {
      background-color: #8B0000;
      border-color: #8B0000;
    }
    .btn-success:hover {
      background-color: #a30000;
      border-color: #a30000;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h4 class="text-center text-danger mb-4">Edit Permintaan Darah</h4>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama Pasien</label>
        <input type="text" name="patient_name" class="form-control" value="<?= htmlspecialchars($data['patient_name']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Rumah Sakit/Hospital</label>
        <input type="text" name="hospital" class="form-control" value="<?= htmlspecialchars($data['hospital']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Golongan Darah</label>
        <select name="blood_type_id" class="form-select" required>
          <option value="">Pilih Golongan Darah</option>
          <?php 
          $current_blood = $data['blood_type'] . $data['rhesus'];
          foreach($blood_types as $key => $value): 
          ?>
            <option value="<?= $key ?>" <?= $key == $current_blood ? 'selected' : '' ?>>
              <?= htmlspecialchars($value) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Jumlah Dibutuhkan (kantong)</label>
        <input type="number" name="quantity_needed" class="form-control" value="<?= $data['quantity_needed'] ?>" required min="1">
      </div>

      <div class="mb-3">
        <label class="form-label">Kontak Person</label>
        <input type="text" name="contact_person" class="form-control" value="<?= htmlspecialchars($data['contact_person']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="contact_phone" class="form-control" value="<?= htmlspecialchars($data['contact_phone']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Urgency</label>
        <select name="urgency" class="form-select" required>
          <option value="normal" <?= $data['urgency'] == 'normal' ? 'selected' : '' ?>>Normal</option>
          <option value="urgent" <?= $data['urgency'] == 'urgent' ? 'selected' : '' ?>>Urgent</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="pending" <?= $data['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="approved" <?= $data['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
          <option value="rejected" <?= $data['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
          <option value="completed" <?= $data['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($data['notes']) ?></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <a href="permintaan.php" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>

