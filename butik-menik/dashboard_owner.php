<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Pastikan yang masuk adalah Owner
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

// Ambil semua pesanan dan gabungkan dengan nama customer dari tabel users
$query = mysqli_query($conn, "SELECT p.*, u.nama AS nama_customer FROM pesanan p JOIN users u ON p.id_user = u.id ORDER BY p.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Owner - Butik Menik</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">Butik Menik Modeste (Panel Owner)</div>
    
    <div class="container">
        <h2>Daftar Pesanan Masuk</h2>
        <p class="subtitle">Kelola penerimaan, status, dan jadwal pengambilan pesanan customer.</p>

        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Customer</th>
                    <th>Jenis Baju</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td data-label="ID Pesanan">#ORD-2025-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
                    <td data-label="Customer"><?= htmlspecialchars($row['nama_customer']) ?></td>
                    <td data-label="Jenis Baju"><?= htmlspecialchars($row['jenis_baju']) ?> (<?= htmlspecialchars($row['ukuran']) ?>)</td>
                    <td data-label="Status">
                        <span style="font-weight:bold; color:#b43f3f;">
                            <?= htmlspecialchars($row['status'] ?? 'Menunggu Konfirmasi') ?>
                        </span>
                    </td>
                    <td data-label="Tanggal">
                        <?= !empty($row['tanggal']) ? date('d M Y', strtotime($row['tanggal'])) : '-' ?>
                    </td>
                    <td data-label="Aksi">
                        <a href="kelola_pesanan.php?id=<?= $row['id'] ?>">
                            <button type="button">Kelola</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if(mysqli_num_rows($query) == 0): ?>
                    <tr><td style="text-align:center;">Belum ada pesanan masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <a href="logout.php" style="color: #b43f3f; font-weight: bold; text-decoration: none;">← Logout dari akun Owner</a>
        </div>
    </div>
</body>
</html>