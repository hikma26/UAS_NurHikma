<?php
// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$database = "sistem_donor_darah";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset untuk menghindari masalah encoding
$conn->set_charset("utf8");

// Fungsi untuk escape string
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Fungsi untuk query dengan hasil
function query($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }
    return $result;
}

// Fungsi untuk mengambil data dalam bentuk array
function fetch_array($result) {
    return mysqli_fetch_array($result);
}

// Fungsi untuk mengambil data dalam bentuk associative array
function fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk menghitung jumlah rows
function num_rows($result) {
    return mysqli_num_rows($result);
}

// Fungsi untuk mendapatkan ID terakhir yang diinsert
function last_insert_id() {
    global $conn;
    return mysqli_insert_id($conn);
}

// Fungsi untuk close connection
function close_connection() {
    global $conn;
    mysqli_close($conn);
}
?>
