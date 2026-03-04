<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

// Staff hanya melihat pesanan yang sedang diproses
$query = mysqli_query($conn, "SELECT p.*, u.nama AS nama_customer FROM pesanan p JOIN users u ON p.id_user = u.id WHERE p.status = 'Diproses (Produksi)' ORDER BY p.id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Produksi - Butik Menik</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">Panel Produksi (Staff)</div>
    
    <div class="container">
        <h2>Antrean Produksi</h2>
        <p class="subtitle" style="margin-bottom: 20px;">Daftar baju yang harus dikerjakan dan pengecekan kualitas (QC).</p>

        <?php if (mysqli_num_rows($query) == 0): ?>
            <p class="empty-state">Hore! Tidak ada antrean produksi saat ini.</p>
        <?php else: ?>
            <div class="order-list">
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <div class="order-card" style="border-left: 5px solid #d97a7a;">
                        <div class="order-info">
                            <div class="order-id">#ORD-2025-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></div>
                            <div class="order-meta" style="margin-top: 8px;">
                                <span><strong>Item:</strong> <?= htmlspecialchars($row['jenis_baju']); ?> (Ukuran <?= $row['ukuran'] ?>)</span>
                                <span><strong>Tahap:</strong> <span style="color: #d97a7a; font-weight: bold;"><?= htmlspecialchars($row['tahap_produksi']); ?></span></span>
                            </div>
                        </div>
                        <div class="order-action">
                            <a href="update_produksi.php?id=<?= $row['id']; ?>" class="btn-detail">Update Progres</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="back-link" style="margin-top: 30px; text-align: center;">
            <a href="logout.php" style="color: #b43f3f; font-weight: bold; text-decoration: none;">← Keluar dari Panel Staff</a>
        </div>
    </div>
</body>
</html>