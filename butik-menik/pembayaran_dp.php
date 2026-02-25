<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE user_id='$user_id' AND status NOT IN ('Selesai','Dibatalkan')");

if (isset($_POST['kirim'])) {
    $pesanan_id = $_POST['pesanan_id'];
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    // Upload bukti transfer
    $bukti = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];
    $target_dir = "uploads/bukti/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . time() . "_" . basename($bukti);
    move_uploaded_file($tmp, $target_file);

    $query = "INSERT INTO pembayaran (pesanan_id, bukti_transfer, catatan, status) 
              VALUES ('$pesanan_id', '$target_file', '$catatan', 'Menunggu Verifikasi')";

    if (mysqli_query($conn, $query)) {
        // Update status pesanan jadi "Menunggu Verifikasi DP" (opsional)
        mysqli_query($conn, "UPDATE pesanan SET status='Menunggu Verifikasi DP' WHERE id='$pesanan_id'");
        $success = "Bukti pembayaran berhasil dikirim!";
    } else {
        $error = "Gagal mengirim bukti pembayaran.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran DP - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container">
        <h2>Pembayaran DP</h2>

        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="payment-form">
            <div class="form-group">
                <label>Pilih Pesanan</label>
                <select name="pesanan_id" required>
                    <option value="" disabled selected>Pilih pesanan</option>
                    <?php while ($row = mysqli_fetch_assoc($pesanan)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['jenis_baju']); ?> - <?php echo date('d M Y', strtotime($row['tanggal_pesan'])); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="payment-info">
                <p>Total DP yang harus dibayar: <strong>Rp 500.000</strong></p>
                <p>Metode Pembayaran: <strong>Bank BCA</strong><br>
                No. Rekening: 1234567890 a.n. Butik Menik Modeste</p>
            </div>

            <div class="form-group upload-area">
                <label>Upload Buku Transfer</label>
                <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required>
                <small>Format: .JPG, .PNG, .PDF (Maks 5MB)</small>
            </div>

            <div class="form-group">
                <label>Catatan (Opsional)</label>
                <textarea name="catatan" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <button type="submit" name="kirim" class="btn-primary">Upload Pembayaran</button>
        </form>

        <div class="back-link">
            <a href="dashboard_customer.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>