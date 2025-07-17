<?php
// /admin/hapus-user.php
include 'auth_check.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id=$id");
header("Location: users.php");
exit;

