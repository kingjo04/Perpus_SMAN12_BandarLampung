<?php
session_start();

// Hapus semua session
session_destroy();

// Redirect ke halaman login
header("location: ../admin/login.php");
exit();
?>