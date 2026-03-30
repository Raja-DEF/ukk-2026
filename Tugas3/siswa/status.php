<?php
include "../config/koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('ID aspirasi tidak valid.'); window.location='histori.php';</script>";
    exit();
}

$q = mysqli_query($conn, "
    SELECT a.*, k.ket_kategori, s.kelas 
    FROM input_aspirasi a
    JOIN tb_kategori k ON a.id_kategori = k.id_kategori
    JOIN tb_siswa s ON a.nis = s.nis
    WHERE a.id_pelaporan = $id
");

$data = $q ? mysqli_fetch_assoc($q) : null;

if (!$data) {
    echo "<script>alert('Data aspirasi tidak ditemukan.'); window.location='histori.php';</script>";
    exit();
}

$feedbackQuery = mysqli_query($conn, "SELECT * FROM tb_aspirasi WHERE id_pelaporan = $id");
$feedback = $feedbackQuery ? mysqli_fetch_assoc($feedbackQuery) : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Aspirasi</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Siswa Panel</a>
        <div>
            <a href="form.php" class="btn btn-primary">Kirim Aspirasi</a>
            <a href="histori.php" class="btn btn-secondary ms-2">Histori</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Status Aspirasi</h3>
    <a href="histori.php" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered">
        <tr>
            <th>ID Pelaporan</th><td><?= $data['id_pelaporan'] ?></td>
        </tr>
        <tr>
            <th>NIS</th><td><?= $data['nis'] ?></td>
        </tr>
        <tr>
            <th>Kelas</th><td><?= $data['kelas'] ?></td>
        </tr>
        <tr>
            <th>Kategori</th><td><?= $data['ket_kategori'] ?></td>
        </tr>
        <tr>
            <th>Lokasi</th><td><?= $data['lokasi'] ?></td>
        </tr>
        <tr>
            <th>Keterangan</th><td><?= $data['ket'] ?></td>
        </tr>
        <tr>
            <th>Status</th><td><?= $data['status'] ?></td>
        </tr>
        <tr>
            <th>Feedback</th><td><?= htmlspecialchars($feedback['feedback'] ?? '-') ?></td>
        </tr>
    </table>

</div>

</body>
</html>