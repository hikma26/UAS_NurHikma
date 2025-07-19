<?php
include 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $urgency = mysqli_real_escape_string($conn, $_POST['urgency']);
    
    // Validasi status
    $valid_status = ['pending', 'approved', 'rejected', 'completed'];
    $valid_urgency = ['normal', 'urgent'];
    
    if (!in_array($status, $valid_status) || !in_array($urgency, $valid_urgency)) {
        echo "<script>alert('Status atau urgency tidak valid!'); window.history.back();</script>";
        exit;
    }
    
    $query = "UPDATE blood_requests SET status = ?, urgency = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $status, $urgency, $id);
        $success = mysqli_stmt_execute($stmt);
        
        if ($success) {
            echo "<script>alert('Status berhasil diupdate!'); parent.location = 'index.php'; parent.loadPage('permintaan.php');</script>";
        } else {
            echo "<script>alert('Gagal mengupdate status!'); window.history.back();</script>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error dalam query!'); window.history.back();</script>";
    }
} else {
    header("Location: permintaan.php");
    exit;
}
?>
