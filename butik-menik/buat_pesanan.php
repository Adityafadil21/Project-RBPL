<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['kirim'])) {
    $user_id = $_SESSION['id'];
    $jenis_baju = $_POST['jenis_baju'];
    $ukuran = $_POST['ukuran'];
    $catatan = $_POST['catatan'];

    // upload desain
    $desain = $_FILES['desain']['name'];
    $tmp = $_FILES['desain']['tmp_name'];
    $target_dir = "uploads/desain/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . time() . "_" . basename($desain);
    move_uploaded_file($tmp, $target_file);

    $query = "INSERT INTO pesanan (user_id, jenis_baju, ukuran, catatan, desain, status, tanggal_pesan) 
              VALUES ('$user_id', '$jenis_baju', '$ukuran', '$catatan', '$target_file', 'Menunggu Verifikasi', NOW())";

    if (mysqli_query($conn, $query)) {
        $success = "Pesanan berhasil dibuat!";
    } else {
        $error = "Gagal membuat pesanan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Pesanan - Butik Menik Modeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        Butik Menik Modeste
    </div>

    <div class="container">
        <h2>Buat Pesanan</h2>

        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="order-form">
            <div class="form-group">
                <label>Jenis Baju</label>
                <input type="text" name="jenis_baju" placeholder="Contoh: Kemeja Batik" required>
            </div>

            <div class="form-group">
                <label>Ukuran</label>
                <select name="ukuran" required>
                    <option value="" disabled selected>Pilih ukuran</option>
                    <option>S</option>
                    <option>M</option>
                    <option>L</option>
                    <option>XL</option>
                    <option>XXL</option>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan Tambahan</label>
                <textarea name="catatan" placeholder="Opsional: Warna, model, atau detail lainnya"></textarea>
            </div>

            <div class="form-group upload-area">
                <label>Upload Desain</label>
                <input type="file" name="desain" accept=".jpg,.jpeg,.png" required>
                <small>Tipe file: PNG, JPG, maksimal 50KB</small>
            </div>

            <button type="submit" name="kirim" class="btn-primary">Kirim Pesanan</button>
        </form>

        <div class="back-link">
            <a href="dashboard_customer.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>