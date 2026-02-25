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
if (isset($_POST['daftar'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi sederhana
    if (!empty($nama) && !empty($email) && !empty($username) && !empty($password)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username/email sudah ada (optional)
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            $query = "INSERT INTO users (nama, email, username, password, role) 
                      VALUES ('$nama', '$email', '$username', '$hashed_password', 'customer')";
            if (mysqli_query($conn, $query)) {
                $success = "Akun berhasil dibuat! Silakan login.";
                // Kosongkan form
                $nama = $email = $username = '';
            } else {
                $error = "Gagal mendaftar, coba lagi. Error: " . mysqli_error($conn);
            }
        }
    } else {
        $error = "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container register-container">
        <h2>Daftar Akun Baru</h2>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan Nama" value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="daftar" class="btn-primary">Daftar</button>
        </form>

        <p class="login-link">
            Sudah punya akun? <a href="login.php">Masuk</a>
        </p>
    </div>
</body>
</html>