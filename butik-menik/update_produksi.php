<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (isset($_POST['update_progres'])) {
    $tahap = mysqli_real_escape_string($conn, $_POST['tahap_produksi']);
    $qc = mysqli_real_escape_string($conn, $_POST['catatan_qc']);
    
    mysqli_query($conn, "UPDATE pesanan SET tahap_produksi='$tahap', catatan_qc='$qc' WHERE id='$id_pesanan'");
    header("Location: update_produksi.php?id=$id_pesanan&msg=success");
    exit();
}

$query = mysqli_query($conn, "SELECT p.*, u.nama AS nama_customer FROM pesanan p JOIN users u ON p.id_user = u.id WHERE p.id='$id_pesanan'");
$pesanan = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Produksi - Butik Menik</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">Panel Produksi</div>
    
    <div class="container login-container" style="max-width: 600px;">
        <h2>Progres Produksi</h2>
        <p class="subtitle">Order #<?= str_pad($pesanan['id'], 3, '0', STR_PAD_LEFT) ?> a.n. <?= htmlspecialchars($pesanan['nama_customer']) ?></p>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert success">Progres berhasil diperbarui!</div>
        <?php endif; ?>

        <div style="text-align: center; margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 15px;">
            <p style="font-weight: bold; margin-bottom: 10px;">Catatan Customer:</p>
            <p style="color: #555; margin-bottom: 15px;">"<?= nl2br(htmlspecialchars($pesanan['catatan'] ?? 'Tidak ada catatan khusus.')) ?>"</p>
            <?php if(!empty($pesanan['desain_gambar'])): ?>
                <img src="uploads/<?= htmlspecialchars($pesanan['desain_gambar']) ?>" style="max-width: 200px; border-radius: 10px; border: 1px solid #ddd;">
            <?php endif; ?>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Pilih Tahapan Pengerjaan Saat Ini</label>
                <select name="tahap_produksi" style="width: 100%; padding: 14px 18px; border-radius: 20px; border: 1.5px solid #e9e9f0; background: #fafbff;">
                    <?php 
                    $tahapan = ['Belum Mulai', 'Pemotongan Bahan', 'Penjahitan', 'Finishing', 'Pengecekan Kualitas (QC)', 'Lolos QC & Siap Ambil'];
                    foreach ($tahapan as $t) {
                        $selected = ($pesanan['tahap_produksi'] == $t) ? 'selected' : '';
                        echo "<option value=\"$t\" $selected>$t</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Input Hasil Pengecekan Kualitas (QC)</label>
                <textarea name="catatan_qc" placeholder="Misal: Jahitan rapi, tidak ada cacat, ukuran sesuai." style="width: 100%; padding: 14px 18px; border-radius: 20px; border: 1.5px solid #e9e9f0; background: #fafbff; height: 100px;"><?= htmlspecialchars($pesanan['catatan_qc'] ?? '') ?></textarea>
            </div>

            <button type="submit" name="update_progres" class="btn-primary">Simpan Progres</button>
        </form>

        <p style="text-align: center; margin-top: 20px;"><a href="dashboard_staff.php" style="color: #6b6b7b;">← Kembali ke Antrean</a></p>
    </div>
</body>
</html>