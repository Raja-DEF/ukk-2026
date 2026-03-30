<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$q = mysqli_query($conn, "
    SELECT a.*, k.ket_kategori, s.kelas 
    FROM input_aspirasi a
    JOIN tb_kategori k ON a.id_kategori = k.id_kategori
    JOIN tb_siswa s ON a.nis = s.nis
    ORDER BY a.tanggal_input DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Aspirasi</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">    <link rel="stylesheet" href="../assets/style.css"></head>
<body>

<div class="container mt-4">

    <h3>Data Aspirasi</h3>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($d = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td><?= $d['id_pelaporan'] ?></td>
                <td><?= $d['nis'] ?></td>
                <td><?= $d['kelas'] ?></td>
                <td><?= $d['ket_kategori'] ?></td>
                <td><?= $d['lokasi'] ?></td>
                <td><?= $d['ket'] ?></td>
                <td><?= $d['status'] ?></td>
                <td>
                    <a href="aspirasi_edit.php?id=<?= $d['id_pelaporan'] ?>" class="btn btn-primary btn-sm">Detail/Edit</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>