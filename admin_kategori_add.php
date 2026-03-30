<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if (isset($_POST['simpan'])) {
    $ket = trim($_POST['ket_kategori']);

    if ($ket === "") {
        $message = "Nama kategori tidak boleh kosong.";
    } else {
        // cek apakah sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM tb_kategori WHERE ket_kategori='$ket'");
        if (mysqli_num_rows($cek) > 0) {
            $message = "Kategori sudah terdaftar.";
        } else {
            mysqli_query($conn, "INSERT INTO tb_kategori (ket_kategori) VALUES ('$ket')");
            $message = "Kategori berhasil ditambahkan.";
            $_POST = array();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
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
    <h3>Tambah Kategori</h3>
    <?php if ($message !== "") { ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nama Kategori</label>
            <input name="ket_kategori" class="form-control" value="<?php echo isset($_POST['ket_kategori']) ? htmlspecialchars($_POST['ket_kategori']) : ''; ?>" required>
        </div>
        <button name="simpan" class="btn btn-primary">Simpan</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
