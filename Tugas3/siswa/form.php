<?php
include "../config/koneksi.php";

$kategori = mysqli_query($conn, "SELECT * FROM tb_kategori");
// ambil daftar siswa untuk dropdown nis
$siswa_list = mysqli_query($conn, "SELECT nis FROM tb_siswa ORDER BY nis");

// proses submit
$message = "";

if (isset($_POST['submit'])) {

    $nis     = $_POST['nis'];
    $id_kat  = $_POST['kategori'];
    $lokasi  = $_POST['lokasi'];
    $ket     = $_POST['ket'];

    // sertakan tanggal_input agar catat waktu pengiriman
    // --- ensure we don't try to insert duplicate primary key (some installs lack AUTO_INCREMENT) ---
    // check column definition for AUTO_INCREMENT
    $nextId = null;
    $colInfo = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM input_aspirasi LIKE 'id_pelaporan'"));
    if ($colInfo && stripos($colInfo['Extra'], 'auto_increment') === false) {
        // table has no auto‑increment, compute next id manually
        $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(id_pelaporan),0)+1 AS next FROM input_aspirasi"));
        $nextId = $row['next'];
    }

    // build insert statement; explicitly include id_pelaporan only when we generated one
    if ($nextId !== null) {
        $insert = mysqli_query($conn, "
            INSERT INTO input_aspirasi (id_pelaporan, nis, id_kategori, lokasi, ket, tanggal_input)
            VALUES ('$nextId', '$nis', '$id_kat', '$lokasi', '$ket', NOW())
        ");
    } else {
        $insert = mysqli_query($conn, "
            INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket, tanggal_input)
            VALUES ('$nis', '$id_kat', '$lokasi', '$ket', NOW())
        ");
    }

    if ($insert) {
        // ambil id yang dibuat (jika ada)
        $lastId = mysqli_insert_id($conn);
        if ($lastId > 0) {
            // hanya masukkan ke tb_aspirasi jika id valid; biarkan MySQL yang menghasilkan id_aspirasi
            mysqli_query($conn, "
                INSERT INTO tb_aspirasi (status, id_pelaporan, feedback)
                VALUES ('menunggu', '$lastId', '')
            ");
        }
        $message = "<div class='alert alert-success'>Aspirasi berhasil dikirim!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Terjadi kesalahan saat mengirim aspirasi.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Aspirasi Siswa</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Aspirasi Siswa</a>
        <div>
            <a href="dashboard.php" class="btn btn-light">Dashboard</a>
            <a href="histori.php" class="btn btn-secondary ms-2">Histori</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Form Aspirasi Siswa</h3>

    <?= $message ?>

    <form method="POST">

        <div class="mb-3">
            <label>NIS</label>
            <select name="nis" class="form-control" required>
                <option value="">-- Pilih NIS --</option>
                <?php while ($s = mysqli_fetch_assoc($siswa_list)) { 
                    $selected = (isset($_POST['nis']) && $_POST['nis'] == $s['nis']) ? 'selected' : ''; ?>
                <option value="<?= $s['nis'] ?>" <?= $selected ?>><?= $s['nis'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Kategori Aspirasi</label>
            <select name="kategori" class="form-control" required>
                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                <option value="<?= $k['id_kategori'] ?>"><?= $k['ket_kategori'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Lokasi Permasalahan</label>
            <input name="lokasi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Isi Aspirasi</label>
            <textarea name="ket" class="form-control" rows="3" required></textarea>
        </div>

        <button name="submit" class="btn btn-primary">Kirim</button>
    </form>

    <a href="histori.php" class="btn btn-secondary mt-3">Lihat Histori</a>

</div>

</body>
</html>