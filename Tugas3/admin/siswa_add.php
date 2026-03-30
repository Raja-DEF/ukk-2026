<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['simpan'])) {
    $nis = trim($_POST['nis']);
    $kelas = trim($_POST['kelas']);

    if ($nis === "" || $kelas === "") {
        $message = "Semua field wajib diisi.";
    } else {
        // cek apakah nis sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM tb_siswa WHERE nis='$nis'");
        if (mysqli_num_rows($cek) > 0) {
            $message = "NIS sudah terdaftar.";
        } else {
            mysqli_query($conn, "INSERT INTO tb_siswa (nis, kelas) VALUES ('$nis', '$kelas')");
            $message = "Data siswa berhasil ditambahkan.";
            // kosongkan agar form kembali kosong
            $_POST = array();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Siswa</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">    <link rel="stylesheet" href="../assets/style.css"></head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Admin Panel</span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Tambah Data Siswa</h3>
    <?php if ($message !== "") { ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST">
        <div class="mb-3">
            <label>NIS</label>
            <input name="nis" class="form-control" value="<?php echo isset($_POST['nis']) ? htmlspecialchars($_POST['nis']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Kelas</label>
            <input name="kelas" class="form-control" value="<?php echo isset($_POST['kelas']) ? htmlspecialchars($_POST['kelas']) : ''; ?>" required>
        </div>
        <button name="simpan" class="btn btn-primary">Simpan</button>
        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>