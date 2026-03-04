<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id_user = ? ORDER BY id DESC");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$query = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Customer</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#f5f5f5;
}

/* Header */
.header{
    background:#111;
    color:white;
    padding:20px;
}

.header h3{
    margin:0;
    font-weight:400;
}

.header h2{
    margin:5px 0 0;
}

/* Container */
.container{
    padding:20px;
    padding-bottom:80px;
}

/* Button */
.btn-main{
    background:#111;
    color:white;
    padding:15px;
    border-radius:12px;
    text-align:center;
    text-decoration:none;
    display:block;
    margin-bottom:20px;
    transition:0.2s;
}

.btn-main:hover{
    background:#333;
}

/* Card */
.card{
    background:white;
    border-radius:15px;
    padding:15px;
    margin-bottom:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
    transition:0.2s;
}

.card:hover{
    transform:translateY(-3px);
}

/* Badge */
.badge{
    float:right;
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    color:white;
}

/* Detail Button */
.detail-btn{
    border:1px solid #111;
    padding:10px;
    border-radius:8px;
    text-align:center;
    margin-top:10px;
    text-decoration:none;
    color:black;
    display:block;
    transition:0.2s;
}

.detail-btn:hover{
    background:#111;
    color:white;
}

/* Bottom Nav */
.bottom-nav{
    position:fixed;
    bottom:0;
    width:100%;
    background:white;
    display:flex;
    justify-content:space-around;
    padding:10px 0;
    border-top:1px solid #ddd;
}

.bottom-nav a{
    text-decoration:none;
    color:gray;
    font-size:12px;
}

.bottom-nav .active{
    color:black;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="header">
    <h3>Halo, <?= htmlspecialchars($_SESSION['nama']); ?></h3>
    <h2>Butik Menik Modeste</h2>
</div>

<div class="container">

<a href="buat_pesanan.php" class="btn-main">
    + Buat Pesanan Baru
</a>

<h3>Status Pesanan Saya</h3>

<?php if($query->num_rows == 0){ ?>
    <p>Belum ada pesanan.</p>
<?php } ?>

<?php while($row = $query->fetch_assoc()) { 

$status = strtolower($row['status']);
$warna = "orange";

if (strpos($status, 'produksi') !== false) $warna = "#007bff";
if (strpos($status, 'selesai') !== false) $warna = "green";
?>

<div class="card">

    <div>
        <strong>Order ID</strong>
        <span class="badge" style="background:<?= $warna ?>;">
            <?= htmlspecialchars($row['status']); ?>
        </span><br>
        #ORD-2025-<?= str_pad($row['id'],3,'0',STR_PAD_LEFT); ?>
    </div>

    <hr>

    <p>
        Tanggal Pesanan 
        <span style="float:right">
            <?= !empty($row['tanggal']) ? date('d M Y', strtotime($row['tanggal'])) : '-' ?>
        </span>
    </p>

    <p>
        Total Item 
        <span style="float:right">1 Item</span>
    </p>

    <a href="detail_pesanan.php?id=<?= $row['id']; ?>" class="detail-btn">
        Lihat Detail
    </a>

</div>

<?php } ?>

</div>

<div class="bottom-nav">
    <a href="dashboard_customer.php" class="active">Home</a>
    <a href="#">Pesanan</a>
    <a href="#">Profil</a>
</div>

</body>
</html>
