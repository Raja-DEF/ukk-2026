<?php
session_start();

// Redirect ke admin login jika belum login
if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
} else {
    header("Location: admin_login.php");
}
exit();
?>
