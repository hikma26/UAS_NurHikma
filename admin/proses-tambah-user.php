<?php
include 'auth_check.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$name     = mysqli_real_escape_string($conn, $_POST['name']);
$email    = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];
$role     = mysqli_real_escape_string($conn, $_POST['role']);

// Untuk keamanan: gunakan password_hash()
$password = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$query = "INSERT INTO users (username, full_name, email, password, role, is_active) 
          VALUES ('$email', '$name', '$email', '$password', '$role', 1)";

$result = mysqli_query($conn, $query);

if ($result) {
    header("Location: users.php");
    exit;
} else {
    echo "Gagal menambahkan pengguna: " . mysqli_error($conn);
}
?>

