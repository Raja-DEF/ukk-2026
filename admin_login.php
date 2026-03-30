<?php
session_start();
include "koneksi.php";

if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // sesuai hash di database

    $query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $message = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 400px;">
    <div class="card">
        <div class="card-header text-center">
            <h4>Login Admin</h4>
        </div>
        <div class="card-body">
            <?php if ($message != "") { ?>
                <div class="alert alert-danger"><?= $message ?></div>
            <?php } ?>

            <form method="post">
                <div class="mb-3">
                    <label>Username</label>
                    <input name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <button name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
