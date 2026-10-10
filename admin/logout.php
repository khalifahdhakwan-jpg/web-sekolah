<?php
session_start();

// Hancurkan semua session
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>