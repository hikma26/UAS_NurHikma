<?php
session_start();
require_once "../koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$nama_pasien = htmlspecialchars($_POST['nama_pasien']);
$blood_type_selected = $_POST['blood_type_id']; // format: A+, B-, etc
$jumlah = intval($_POST['jumlah']);
$lokasi = htmlspecialchars($_POST['lokasi']);
$kontak = htmlspecialchars($_POST['kontak']);
$keterangan = htmlspecialchars($_POST['keterangan']);
$tanggal = date('Y-m-d');
$user_id = $_SESSION['user_id'];

// Parse blood type and rhesus
$blood_type = substr($blood_type_selected, 0, -1); // A, B, AB, O
$rhesus = substr($blood_type_selected, -1); // +, -

$query = "INSERT INTO blood_requests (patient_name, hospital, blood_type, rhesus, quantity_needed, urgency, request_date, needed_date, contact_person, contact_phone, notes, status) VALUES (?, ?, ?, ?, ?, 'normal', ?, DATE_ADD(?, INTERVAL 1 DAY), ?, ?, ?, 'pending')";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssisssss", $nama_pasien, $lokasi, $blood_type, $rhesus, $jumlah, $tanggal, $tanggal, $nama_pasien, $kontak, $keterangan);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Permintaan darah berhasil didaftarkan!'); window.location = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data.'); window.history.back();</script>";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

