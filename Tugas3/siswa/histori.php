<?php
include "../config/koneksi.php";

$message = "";

// pagination untuk dropdown NIS
$nisPage = isset($_GET['nis_page']) ? max(1, intval($_GET['nis_page'])) : 1;
$nisPerPage = 10;
$nisTotalRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_siswa"))['total'];
$nisPages = max(1, ceil($nisTotalRow / $nisPerPage));
$nisPage = min($nisPage, $nisPages);
$nisOffset = ($nisPage - 1) * $nisPerPage;

// ambil daftar NIS untuk dropdown (paginasi)
$nisList = mysqli_query($conn, "SELECT nis FROM tb_siswa ORDER BY nis LIMIT $nisPerPage OFFSET $nisOffset");

// default query shows all aspirasi
$query = mysqli_query($conn, "
    SELECT a.*, k.ket_kategori 
    FROM input_aspirasi a
    JOIN tb_kategori k ON a.id_kategori = k.id_kategori
    ORDER BY tanggal_input DESC
");

// if search form submitted, apply nis filter
if (isset($_POST['cek'])) {
    $nis = $_POST['nis'];
    $query = mysqli_query($conn, "
        SELECT a.*, k.ket_kategori 
        FROM input_aspirasi a
        JOIN tb_kategori k ON a.id_kategori = k.id_kategori
        WHERE nis='$nis'
        ORDER BY tanggal_input DESC
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Histori Aspirasi</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Siswa Panel</a>
        <div>
            <a href="form.php" class="btn btn-primary">Kirim Aspirasi</a>
            <a href="dashboard.php" class="btn btn-light ms-2">Dashboard</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Histori Aspirasi Siswa</h3>

    <form method="POST" class="row gy-2 gx-3 align-items-center">
        <div class="col-auto" style="min-width:260px;">
            <label class="visually-hidden" for="nisInput">Masukkan NIS Anda</label>
            <select id="nisInput" name="nis" class="form-control" required>
                <option value="">-- Pilih NIS --</option>
                <?php while ($n = mysqli_fetch_assoc($nisList)) {
                    $sel = (isset($nis) && $nis === $n['nis']) ? 'selected' : '';
                ?>
                    <option value="<?= htmlspecialchars($n['nis']) ?>" <?= $sel ?>><?= htmlspecialchars($n['nis']) ?></option>
                <?php } ?>
            </select>
            <input type="hidden" name="nis_page" value="<?= $nisPage ?>">
        </div>

        <div class="col-auto">
            <button name="cek" class="btn btn-primary">Cari</button>
        </div>
    </form>

    <?php if ($nisPages > 1) { ?>
        <nav aria-label="Pagination NIS">
            <ul class="pagination">
                <li class="page-item <?= ($nisPage <= 1 ? 'disabled' : '') ?>">
                    <a class="page-link" href="histori.php?nis_page=<?= max(1, $nisPage - 1) ?>">« Sebelumnya</a>
                </li>
                <li class="page-item disabled"><span class="page-link">Halaman <?= $nisPage ?> dari <?= $nisPages ?></span></li>
                <li class="page-item <?= ($nisPage >= $nisPages ? 'disabled' : '') ?>">
                    <a class="page-link" href="histori.php?nis_page=<?= min($nisPages, $nisPage + 1) ?>">Berikutnya »</a>
                </li>
            </ul>
        </nav>
    <?php } ?>

    <?php if (isset($query)) { ?>
        <table class="table table-bordered mt-4">
            <tr>
                <th>Waktu Kirim</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= date('H:i, d-m-Y', strtotime($d['tanggal_input'])) ?></td>
                <td><?= $d['ket_kategori'] ?></td>
                <td><?= $d['lokasi'] ?></td>
                <td><?= $d['ket'] ?></td>
                <td><?= $d['status'] ?></td>
                <td>
                    <a href="status.php?id=<?= $d['id_pelaporan'] ?>" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            <?php } ?>

        </table>
    <?php } ?>

</div>

</body>
</html>