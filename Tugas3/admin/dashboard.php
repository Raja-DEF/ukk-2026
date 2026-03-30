<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// handle deletion of siswa
if (isset($_GET['del_nis'])) {
    $del_nis = mysqli_real_escape_string($conn, $_GET['del_nis']);
    mysqli_query($conn, "DELETE FROM tb_siswa WHERE nis='$del_nis'");
    header("Location: dashboard.php");
    exit();
}

// handle deletion of kategori
if (isset($_GET['del_cat'])) {
    $del_cat = mysqli_real_escape_string($conn, $_GET['del_cat']);
    mysqli_query($conn, "DELETE FROM tb_kategori WHERE id_kategori='$del_cat'");
    header("Location: dashboard.php");
    exit();
}

// Hitung data
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi"))['jml'];
$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE status='menunggu'"))['jml'];
$proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE status='proses'"))['jml'];
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE status='selesai'"))['jml'];

// list siswa
$students = mysqli_query($conn, "SELECT * FROM tb_siswa ORDER BY nis");
// list kategori
$categories = mysqli_query($conn, "SELECT * FROM tb_kategori ORDER BY ket_kategori");
// list 10 history aspirasi terakhir
$aspirasi_history = mysqli_query($conn, "
    SELECT a.*, k.ket_kategori, s.kelas
    FROM input_aspirasi a
    JOIN tb_kategori k ON a.id_kategori = k.id_kategori
    JOIN tb_siswa s ON a.nis = s.nis
    ORDER BY a.tanggal_input DESC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Admin Panel</span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard</h3>
    <div class="row mt-4">

        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body">
                    Total Aspirasi: <h3><?= $total ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    Menunggu: <h3><?= $menunggu ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    Proses: <h3><?= $proses ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    Selesai: <h3><?= $selesai ?></h3>
                </div>
            </div>
        </div>

    </div>

    <a href="aspirasi_list.php" class="btn btn-primary mt-4">Lihat Semua Aspirasi</a>
    <a href="siswa_add.php" class="btn btn-success mt-4 ms-2">Tambah Data Siswa</a>
    <a href="kategori_add.php" class="btn btn-warning mt-4 ms-2">Tambah Kategori</a>

    <h4 class="mt-5">10 History Aspirasi Terakhir</h4>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Tanggal/Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($h = mysqli_fetch_assoc($aspirasi_history)) { ?>
            <tr>
                <td><?= $h['id_pelaporan'] ?></td>
                <td><?= $h['nis'] ?></td>
                <td><?= $h['kelas'] ?></td>
                <td><?= $h['ket_kategori'] ?></td>
                <td><?= $h['lokasi'] ?></td>
                <td><?= $h['ket'] ?></td>
                <td><?= $h['status'] ?></td>
                <td><?= date('d-m-Y H:i:s', strtotime($h['tanggal_input'])) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <h4 class="mt-5"></h4>Data Siswa</h4>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($students)) { ?>
            <tr>
                <td><?= $row['nis'] ?></td>
                <td><?= $row['kelas'] ?></td>
                <td>
                    <a href="dashboard.php?del_nis=<?= $row['nis'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus siswa <?= $row['nis'] ?>?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <h4 class="mt-5">Data Kategori</h4>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
            <tr>
                <td><?= $cat['id_kategori'] ?></td>
                <td><?= $cat['ket_kategori'] ?></td>
                <td>
                    <a href="dashboard.php?del_cat=<?= $cat['id_kategori'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>