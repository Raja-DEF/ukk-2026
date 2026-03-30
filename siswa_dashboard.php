<?php
session_start();
include "koneksi.php";

// nilai default
$nis = "";
$total = 0;
$menunggu = 0;
$proses = 0;
$selesai = 0;
$message = "";

// pagination untuk dropdown NIS
$nisPage = isset($_GET['nis_page']) ? max(1, intval($_GET['nis_page'])) : 1;
if (isset($_POST['nis_page'])) {
    $nisPage = max(1, intval($_POST['nis_page']));
}
$nisPerPage = 10;
$nisTotalRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_siswa"))['total'];
$nisPages = max(1, ceil($nisTotalRow / $nisPerPage));
$nisPage = min($nisPage, $nisPages);
$nisOffset = ($nisPage - 1) * $nisPerPage;

// ambil daftar NIS untuk dropdown (paginasi)
$nisList = mysqli_query($conn, "SELECT nis FROM tb_siswa ORDER BY nis LIMIT $nisPerPage OFFSET $nisOffset");

// reset pilihan NIS bila diminta
if (isset($_GET['reset'])) {
    unset($_SESSION['nis']);
    header('Location: siswa_dashboard.php');
    exit;
}
// jika nis dikirim melalui form, simpan di session
if (isset($_POST['cek'])) {
    $nis = trim($_POST['nis']);
    $_SESSION['nis'] = $nis;
}

// tampilkan 10 history terakhir secara global
$history = mysqli_query($conn, "
    SELECT a.*, k.ket_kategori
    FROM input_aspirasi a
    JOIN tb_kategori k ON a.id_kategori = k.id_kategori
    ORDER BY tanggal_input DESC
    LIMIT 10
");
if (!$history) {
    $message = "<div class='alert alert-danger'>Gagal mengambil histori: " . mysqli_error($conn) . "</div>";
}

// gunakan nis dari session jika ada
if (!empty($_SESSION['nis'])) {
    $nis = $_SESSION['nis'];
    // escape untuk keamanan sederhana
    $nis_esc = mysqli_real_escape_string($conn, $nis);

    // hitung aspirasi untuk nis tersebut
    $q = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE nis='$nis_esc'");
    if ($q) { $total = mysqli_fetch_assoc($q)['jml']; }

    $q = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE nis='$nis_esc' AND status='menunggu'");
    if ($q) { $menunggu = mysqli_fetch_assoc($q)['jml']; }

    $q = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE nis='$nis_esc' AND status='proses'");
    if ($q) { $proses = mysqli_fetch_assoc($q)['jml']; }

    $q = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM input_aspirasi WHERE nis='$nis_esc' AND status='selesai'");
    if ($q) { $selesai = mysqli_fetch_assoc($q)['jml']; }

    // ambil 10 histori terakhir khusus NIS ini
    $history = mysqli_query($conn, "
        SELECT a.*, k.ket_kategori
        FROM input_aspirasi a
        JOIN tb_kategori k ON a.id_kategori = k.id_kategori
        WHERE nis='$nis_esc'
        ORDER BY tanggal_input DESC
        LIMIT 10
    ");
    if (!$history) {
        $message = "<div class='alert alert-danger'>Gagal mengambil histori: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Aspirasi Siswa</span>
        <div>
            <a href="siswa_form.php" class="btn btn-primary">Kirim Aspirasi</a>
            <a href="siswa_histori.php" class="btn btn-secondary ms-2">Histori</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Dashboard Aspirasi Siswa</h3>

    <?= $message ?>

    <form method="POST" class="row gy-2 gx-3 align-items-center mb-4">
        <div class="col-auto">
            <label class="visually-hidden" for="nisInput">NIS</label>
            <select class="form-control" id="nisInput" name="nis" required>
                <option value="">-- Pilih NIS --</option>
                <?php while ($n = mysqli_fetch_assoc($nisList)) {
                    $sel = ($n['nis'] === $nis) ? 'selected' : '';
                ?>
                    <option value="<?= htmlspecialchars($n['nis']) ?>" <?= $sel ?>><?= htmlspecialchars($n['nis']) ?></option>
                <?php } ?>
            </select>
            <input type="hidden" name="nis_page" value="<?= $nisPage ?>">
        </div>
        <div class="col-auto">
            <button type="submit" name="cek" class="btn btn-primary">Tampilkan</button>
        </div>
        <div class="col-auto">
            <a href="siswa_dashboard.php?reset=1" class="btn btn-danger">Reset NIS</a>
        </div>
    </form>

    <?php if ($nisPages > 1) { ?>
        <nav aria-label="Pagination NIS">
            <ul class="pagination">
                <li class="page-item <?= ($nisPage <= 1 ? 'disabled' : '') ?>">
                    <a class="page-link" href="siswa_dashboard.php?nis_page=<?= max(1, $nisPage - 1) ?>">« Sebelumnya</a>
                </li>
                <li class="page-item disabled"><span class="page-link">Halaman <?= $nisPage ?> dari <?= $nisPages ?></span></li>
                <li class="page-item <?= ($nisPage >= $nisPages ? 'disabled' : '') ?>">
                    <a class="page-link" href="siswa_dashboard.php?nis_page=<?= min($nisPages, $nisPage + 1) ?>">Berikutnya »</a>
                </li>
            </ul>
        </nav>
    <?php } ?>

    <?php if ($nis !== "") { ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        Total Aspirasi<br><h3><?= $total ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning mb-3">
                    <div class="card-body">
                        Menunggu<br><h3><?= $menunggu ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info mb-3">
                    <div class="card-body">
                        Proses<br><h3><?= $proses ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        Selesai<br><h3><?= $selesai ?></h3>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($history) && mysqli_num_rows($history) > 0) { ?>
        <h4 class="mt-4">10 Histori Terakhir</h4>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($h = mysqli_fetch_assoc($history)) { ?>
                <tr>
                    <td><?= $h['id_pelaporan'] ?></td>
                    <td><?= date('d-m-Y', strtotime($h['tanggal_input'])) ?></td>
                    <td><?= $h['ket_kategori'] ?></td>
                    <td><?= $h['lokasi'] ?></td>
                    <td><?= $h['ket'] ?></td>
                    <td><?= $h['status'] ?></td>
                    <td><a href="siswa_status.php?id=<?= $h['id_pelaporan'] ?>" class="btn btn-info btn-sm">Detail</a></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

</div>

</body>
</html>
