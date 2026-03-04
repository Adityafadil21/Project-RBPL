<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_user    = $_SESSION['id'];
$jenis_baju = mysqli_real_escape_string($conn, $_POST['jenis_baju']);
$ukuran     = mysqli_real_escape_string($conn, $_POST['ukuran']);
$catatan    = mysqli_real_escape_string($conn, $_POST['model']); // textarea kamu

$nama_file = NULL;

/* ===========================
   HANDLE UPLOAD GAMBAR
=========================== */
if (!empty($_FILES['desain']['name'])) {

    $folder = "uploads/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $allowed = ['jpg','jpeg','png'];
    $file_name = $_FILES['desain']['name'];
    $file_tmp  = $_FILES['desain']['tmp_name'];
    $file_size = $_FILES['desain']['size'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed) && $file_size <= 5000000) {

        $nama_file = time() . "_" . $file_name;
        move_uploaded_file($file_tmp, $folder . $nama_file);

    } else {
        die("Format file harus JPG/PNG dan maksimal 5MB.");
    }
}

/* ===========================
   INSERT KE DATABASE
=========================== */

$query = "INSERT INTO pesanan 
(id_user, jenis_baju, ukuran, catatan, desain_gambar) 
VALUES 
('$id_user', '$jenis_baju', '$ukuran', '$catatan', '$nama_file')";

if (mysqli_query($conn, $query)) {
    header("Location: dashboard_customer.php");
    exit();
} else {
    echo "Gagal menyimpan pesanan: " . mysqli_error($conn);
}
?>
