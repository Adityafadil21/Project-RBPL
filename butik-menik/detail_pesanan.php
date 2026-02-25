<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['id'];

// Ambil data pesanan
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id' AND user_id='$user_id'");
$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    header("Location: status_pesanan.php");
    exit();
}

// Ambil riwayat status (misal dari tabel status_history jika ada)
// Untuk sementara kita buat array contoh sesuai screenshot
$status_history = [
    ['status' => 'DP Diverifikasi', 'tanggal' => '15 Jan 2025, 10:30', 'selesai' => true],
    ['status' => 'Pemeriksaan Stok', 'tanggal' => '16 Jan 2025, 14:15', 'selesai' => true],
    ['status' => 'Produksi', 'tanggal' => 'Sedang dalam proses', 'selesai' => false],
    ['status' => 'QC', 'tanggal' => 'Menunggu', 'selesai' => false],
    ['status' => 'Jadwal Ambil', 'tanggal' => 'Menunggu', 'selesai' => false],
];

// Ambil detail item pesanan (jika ada tabel terpisah)
$items = [
    ['nama' => 'Kemeja Batik Custom', 'ukuran' => 'L', 'warna' => 'Biru Navy', 'qty' => 2]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container">
        <h2>Status Pesanan #<?php echo str_pad($pesanan['id'], 8, '0', STR_PAD_LEFT); ?></h2>

        <!-- Timeline Status -->
        <div class="status-timeline">
            <?php foreach ($status_history as $step): ?>
                <div class="timeline-step <?php echo $step['selesai'] ? 'completed' : ''; ?>">
                    <?php echo $step['status']; ?>
                    <span class="date"><?php echo $step['tanggal']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Detail Produk -->
        <div class="detail-section">
            <h3>Detail Pesanan</h3>
            <?php foreach ($items as $item): ?>
                <div class="detail-product">
                    <div class="detail-item">
                        <span class="detail-label">Nama Item</span>
                        <span class="detail-value"><?php echo $item['nama']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ukuran</span>
                        <span class="detail-value"><?php echo $item['ukuran']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Warna</span>
                        <span class="detail-value"><?php echo $item['warna']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Qty</span>
                        <span class="detail-value"><?php echo $item['qty']; ?> pcs</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="payment-detail">
            <h3>Informasi Pembayaran</h3>
            <div class="detail-item">
                <span class="detail-label">ID Pesanan</span>
                <span class="detail-value"><?php echo date('d M Y', strtotime($pesanan['tanggal_pesan'])); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Pesanan</span>
                <span class="detail-value"><?php echo date('d M Y', strtotime($pesanan['tanggal_pesan'])); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value status-badge"><?php echo $pesanan['status']; ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total Bayar</span>
                <span class="detail-value">Rp 450.000</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">DP Dibayar</span>
                <span class="detail-value">Rp 225.000</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Sisa Pembayaran</span>
                <span class="detail-value">Rp 225.000</span>
            </div>
        </div>

        <!-- Tombol Ajukan Keluhan -->
        <div class="action-buttons">
            <a href="ajukan_keluhan.php?id=<?php echo $id; ?>" class="btn-outline">Ajukan Keluhan / Revisi</a>
        </div>

        <div class="back-link">
            <a href="status_pesanan.php">Kembali ke Daftar Pesanan</a>
        </div>
    </div>
</body>
</html>
