<?php
session_start();
include 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id'])) {
    header("Location: dashboard_customer.php");
    exit();
}

$success = '';
$error = '';

if (isset($_POST['reset'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];

    if (!empty($username) && !empty($email) && !empty($new_password)) {
        // Cek apakah username dan email cocok di database
        $query = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' AND email='$email'");
        $data = mysqli_fetch_assoc($query);

        if ($data) {
            $user_id = $data['id'];
            // Hash password baru
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password di database
            $update = mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id='$user_id'");
            
            if ($update) {
                $success = "Password berhasil direset! Silakan login dengan password baru.";
            } else {
                $error = "Gagal mereset password. Terjadi kesalahan pada server.";
            }
        } else {
            $error = "Data tidak ditemukan! Pastikan Username dan Email sudah benar.";
        }
    } else {
        $error = "Semua kolom harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container login-container">
        <h2>Reset Password</h2>
        <p class="subtitle">Masukkan Username dan Email yang terdaftar untuk membuat password baru</p>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username Anda" required>
            </div>
            <div class="form-group">
                <label>Email Terdaftar</label>
                <input type="email" name="email" placeholder="Masukkan email Anda" required>
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="new_password" placeholder="Masukkan password baru" required>
            </div>

            <button type="submit" name="reset" class="btn-primary">Ubah Password</button>
        </form>

        <p class="register-link" style="margin-top: 20px;">
            Ingat password? <a href="login.php">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>