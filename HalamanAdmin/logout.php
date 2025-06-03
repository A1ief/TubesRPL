<?php

session_start();
session_unset(); // Menghapus semua session
session_destroy(); // Mengakhiri session

// Redirect ke halaman login
header("Location: ../../index.php");
exit;
?>