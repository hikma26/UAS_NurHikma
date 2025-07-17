<?php
session_start();
require_once \"../config/koneksi.php\";

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login-admin.php");
    exit;
}

$id = intval($_POST['id']);
$name = $_POST['name'];
$blood_type_id = $_POST['blood_type_id'];
$gender = $_POST['gender'];
$birth_date = $_POST['birth_date'];
$phone = $_POST['phone'];
$district = $_POST['district'];
$village = $_POST['village'];
$weight = $_POST['weight'];
$medical_history = $_POST['medical_history'];
$status_id = $_POST['status_id'];

$stmt = $conn->prepare("UPDATE donors SET name=?, blood_type_id=?, gender=?, birth_date=?, phone=?, district=?, village=?, weight=?, medical_history=?, status_id=? WHERE id=?");
$stmt->bind_param("sisssssdisi", $name, $blood_type_id, $gender, $birth_date, $phone, $district, $village, $weight, $medical_history, $status_id, $id);
$stmt->execute();
$stmt->close();

header("Location: laporan.php");
exit;

