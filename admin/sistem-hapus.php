<?php
include '../koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM system_settings WHERE id=$id");
header("Location: sistem-settings.php");

