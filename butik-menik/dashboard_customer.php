<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container dashboard">
        <h2>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?> 👋</h2>
        <p class="welcome-message">Selamat datang di sistem pemesanan butik.</p>

        <div class="dashboard-menu">
            <a href="buat_pesanan.php" class="menu-card">
                <div class="menu-icon">📝</div>
                <h3>Buat Pesanan</h3>
                <p>Mulai pesan baju custom</p>
            </a>
            <a href="status_pesanan.php" class="menu-card">
                <div class="menu-icon">📦</div>
                <h3>Status Pesanan</h3>
                <p>Lihat progres pesananmu</p>
            </a>
            <a href="pembayaran_dp.php" class="menu-card">
                <div class="menu-icon">💳</div>
                <h3>Pembayaran DP</h3>
                <p>Upload bukti transfer</p>
            </a>
        </div>

        <div class="dashboard-footer">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>