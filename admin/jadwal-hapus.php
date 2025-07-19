<?php
include 'auth_check.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']); // Sanitize input
  
  // Use prepared statement for security
  $stmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  
  if (mysqli_stmt_execute($stmt)) {
    // Success - redirect back to welcome
    header("Location: welcome.php?status=deleted");
    exit;
  } else {
    // Error - redirect with error message
    header("Location: welcome.php?error=delete_failed");
    exit;
  }
} else {
  // No ID provided
  header("Location: welcome.php?error=no_id");
  exit;
}
?>

