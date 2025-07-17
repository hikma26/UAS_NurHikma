<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$blood_type_selected = $_POST['blood_type_id']; // format: A+, B-, etc
$gender = $_POST['gender'] === 'Laki-laki' ? 'L' : 'P';
$birth_date = $_POST['birth_date'];
$phone = $_POST['phone'];
$district = $_POST['district'];
$village = $_POST['village'];
$weight = $_POST['weight'];
$medical_history = $_POST['medical_history'] ?? '';
$address = $village . ', ' . $district . ', Mamuju Tengah';

// Parse blood type and rhesus
$blood_type = substr($blood_type_selected, 0, -1); // A, B, AB, O
$rhesus = substr($blood_type_selected, -1); // +, -

$query = "INSERT INTO donors (
    user_id, name, phone, address, birth_date, gender, blood_type, rhesus, weight, medical_history
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "isssssssds",
        $user_id, $name, $phone, $address, $birth_date, $gender, $blood_type, $rhesus, $weight, $medical_history
    );
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo "<script>alert('Pendaftaran berhasil! Data Anda akan diverifikasi oleh admin.'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
} else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
}
?>

