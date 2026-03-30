<?php
$host = "localhost";
$user = "root";     
$pass = "071207";         
$db   = "db_dataaspirasi";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
