<?php
session_start();
include 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id'])) {
    header("Location: dashboard_customer.php");
    exit();
}

$error = '';
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Gunakan prepared statement untuk keamanan (opsional, tapi disarankan)
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        $data = mysqli_fetch_assoc($query);

        if ($data) {
            // Verifikasi password
            if (password_verify($password, $data['password'])) {
                $_SESSION['id'] = $data['id'];
                $_SESSION['nama'] = $data['nama'];
                $_SESSION['role'] = $data['role'];
                header("Location: dashboard_customer.php");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    } else {
        $error = "Username dan password harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container login-container">
        <h2>Masuk</h2>
        <p class="subtitle">Silakan masuk ke akun Anda</p>

        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="login" class="btn-primary">Masuk</button>
        </form>

        <p class="register-link" style="margin-bottom: 10px;">
            Lupa password? <a href="reset_password.php">Reset di sini</a>
        </p>
        <p class="register-link" style="margin-top: 0;">
            Belum punya akun? <a href="register.php">Daftar akun baru</a>
        </p>
    </div>
</body>

</html>
