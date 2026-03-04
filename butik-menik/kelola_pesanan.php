<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success = '';
$error = '';

// Proses Update Status & Jadwal
if (isset($_POST['update_pesanan'])) {
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Tangani jadwal kosong agar masuk sebagai NULL ke database
    if (!empty($_POST['jadwal_ambil'])) {
        $jadwal_ambil = "'" . mysqli_real_escape_string($conn, $_POST['jadwal_ambil']) . "'";
    } else {
        $jadwal_ambil = "NULL";
    }
    
    $query_update = "UPDATE pesanan SET status='$status_baru', jadwal_ambil=$jadwal_ambil WHERE id='$id_pesanan'";
    if (mysqli_query($conn, $query_update)) {
        $success = "Data pesanan berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui pesanan.";
    }
}

// Ambil data pesanan spesifik
$query = mysqli_query($conn, "SELECT p.*, u.nama AS nama_customer, u.email FROM pesanan p JOIN users u ON p.id_user = u.id WHERE p.id='$id_pesanan'");
$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    header("Location: dashboard_owner.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pesanan - Butik Menik</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">Butik Menik Modeste (Owner)</div>
    
    <div class="container login-container" style="max-width: 600px;">
        <h2>Kelola Pesanan #<?= str_pad($pesanan['id'], 3, '0', STR_PAD_LEFT) ?></h2>
        <p class="subtitle">Tinjau desain customer dan perbarui status operasional.</p>

        <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?= $error ?></div><?php endif; ?>

        <div class="detail-product">
            <p><strong>Customer:</strong> <?= htmlspecialchars($pesanan['nama_customer']) ?> (<?= htmlspecialchars($pesanan['email']) ?>)</p>
            <p><strong>Jenis Baju:</strong> <?= htmlspecialchars($pesanan['jenis_baju']) ?> (<?= htmlspecialchars($pesanan['ukuran']) ?>)</p>
            <p><strong>Catatan:</strong> <?= nl2br(htmlspecialchars($pesanan['catatan'] ?? '-')) ?></p>
            
            <?php if(!empty($pesanan['desain_gambar'])): ?>
                <p style="margin-top: 15px;">
                    <a href="uploads/<?= htmlspecialchars($pesanan['desain_gambar']) ?>" target="_blank" style="color:#b43f3f; font-weight:bold; text-decoration:none; border-bottom: 1px dashed #b43f3f;">Lihat File Desain Customer ↗</a>
                </p>
            <?php endif; ?>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Ubah Status Pesanan</label>
                <select name="status" style="width: 100%; padding: 14px 18px; border-radius: 20px; border: 1.5px solid #e9e9f0; background: #fafbff; font-size: 1rem;">
                    <?php 
                    $status_list = ['Menunggu Konfirmasi', 'Ditolak', 'Menunggu Pembayaran DP', 'Diproses (Produksi)', 'Selesai (Siap Diambil)'];
                    $current = $pesanan['status'] ?? 'Menunggu Konfirmasi';
                    foreach ($status_list as $s) {
                        $selected = ($current == $s) ? 'selected' : '';
                        echo "<option value=\"$s\" $selected>$s</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Atur Jadwal Pengambilan (Opsional)</label>
                <input type="datetime-local" name="jadwal_ambil" value="<?= !empty($pesanan['jadwal_ambil']) ? date('Y-m-d\TH:i', strtotime($pesanan['jadwal_ambil'])) : '' ?>">
                <small style="color:gray; font-size:0.85rem; display:block; margin-top:6px;">Isi jadwal ini jika status sudah "Selesai (Siap Diambil)".</small>
            </div>

            <button type="submit" name="update_pesanan" class="btn-primary">Simpan Perubahan</button>
        </form>

        <p style="text-align: center; margin-top: 25px;">
            <a href="dashboard_owner.php" style="color: #b43f3f; text-decoration:none; font-weight:bold;">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>