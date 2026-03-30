<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('ID aspirasi tidak valid.'); window.location='admin_aspirasi_list.php';</script>";
    exit();
}

$aspirasiQuery = mysqli_query($conn, "
    SELECT a.*, k.ket_kategori, s.kelas 
    FROM input_aspirasi a
    JOIN tb_kategori k ON a.id_kategori = k.id_kategori
    JOIN tb_siswa s ON a.nis = s.nis
    WHERE a.id_pelaporan = $id
");
$aspirasi = $aspirasiQuery ? mysqli_fetch_assoc($aspirasiQuery) : null;

$feedbackQuery = mysqli_query($conn, "SELECT * FROM tb_aspirasi WHERE id_pelaporan = $id");
$feedback = $feedbackQuery ? mysqli_fetch_assoc($feedbackQuery) : null;

if (!$aspirasi) {
    echo "<script>alert('Data aspirasi tidak ditemukan.'); window.location='admin_aspirasi_list.php';</script>";
    exit();
}

// proses update
if (isset($_POST['update'])) {

    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $fb = mysqli_real_escape_string($conn, $_POST['feedback']);

    mysqli_query($conn, "
        UPDATE input_aspirasi SET status='$status' WHERE id_pelaporan=$id
    ");

    if ($feedback) {
        mysqli_query($conn, "
            UPDATE tb_aspirasi SET status='$status', feedback='$fb' WHERE id_pelaporan=$id
        ");
    } else {
        // fallback jika belum ada record di tb_aspirasi
        $tbInfo = mysqli_query($conn, "SHOW COLUMNS FROM tb_aspirasi");
        $hasIdAsp = false;
        $idAspAuto = false;
        while ($col = mysqli_fetch_assoc($tbInfo)) {
            if ($col['Field'] === 'id_aspirasi') {
                $hasIdAsp = true;
                if (stripos($col['Extra'], 'auto_increment') !== false) {
                    $idAspAuto = true;
                }
                break;
            }
        }

        if ($hasIdAsp && !$idAspAuto) {
            $nextIdRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(id_aspirasi),0)+1 AS next FROM tb_aspirasi"));
            $nextId = intval($nextIdRow['next']);
            mysqli_query($conn, "
                INSERT INTO tb_aspirasi (id_aspirasi, id_pelaporan, status, feedback)
                VALUES ($nextId, $id, '$status', '$fb')
            ");
        } else {
            mysqli_query($conn, "
                INSERT INTO tb_aspirasi (id_pelaporan, status, feedback)
                VALUES ($id, '$status', '$fb')
            ");
        }
    }

    echo "<script>alert('Data updated'); window.location='admin_aspirasi_list.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Aspirasi</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Admin Panel</span>
        <a href="admin_logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Edit Aspirasi</h3>
    <a href="admin_aspirasi_list.php" class="btn btn-secondary mb-3">Kembali</a>

    <form method="POST">
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea class="form-control" readonly><?= htmlspecialchars($aspirasi['ket'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select class="form-control" name="status">
                <option value="menunggu" <?= (isset($aspirasi['status']) && $aspirasi['status'] == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                <option value="proses" <?= (isset($aspirasi['status']) && $aspirasi['status'] == 'proses') ? 'selected' : '' ?>>Proses</option>
                <option value="selesai" <?= (isset($aspirasi['status']) && $aspirasi['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Umpan Balik</label>
            <textarea name="feedback" class="form-control"><?= htmlspecialchars($feedback['feedback'] ?? '') ?></textarea>
        </div>

        <button name="update" class="btn btn-primary">Simpan Perubahan</button>

    </form>
</div>

</body>
</html>
