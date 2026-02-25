<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE user_id='$user_id' ORDER BY tanggal_pesan DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Pesanan - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container">
        <h2>Status Pesanan Saya</h2>

        <?php if (mysqli_num_rows($query) == 0): ?>
            <p class="empty-state">Belum ada pesanan. <a href="buat_pesanan.php">Buat pesanan sekarang</a>.</p>
        <?php else: ?>
            <div class="order-list">
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <div class="order-card">
                        <div class="order-info">
                            <div class="order-id">Order ID: #<?php echo str_pad($row['id'], 8, '0', STR_PAD_LEFT); ?></div>
                            <div class="order-meta">
                                <span>Tanggal Pesanan: <?php echo date('d M Y', strtotime($row['tanggal_pesan'])); ?></span>
                                <span>Total Item: <?php echo isset($row['jumlah']) ? $row['jumlah'] : '1'; ?> Item</span>
                            </div>
                        </div>
                        <div class="order-action">
                            <a href="detail_pesanan.php?id=<?php echo $row['id']; ?>" class="btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="back-link">
            <a href="dashboard_customer.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>