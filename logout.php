<?php
session_start();
session_unset();
session_destroy();

// Redirect ke unified login
header("Location: login.php");
exit;
?>
