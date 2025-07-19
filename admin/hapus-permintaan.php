<?php
include 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = (int)$_POST['id'];
        
        $query = "DELETE FROM blood_requests WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            $success = mysqli_stmt_execute($stmt);
            
            if ($success) {
                echo "<script>alert('Permintaan berhasil dihapus!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
            } else {
                echo "<script>alert('Gagal menghapus permintaan!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Error dalam query!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
        }
    } else {
        echo "<script>alert('ID tidak valid!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
    }
} else {
    header("Location: permintaan.php");
}
exit;

