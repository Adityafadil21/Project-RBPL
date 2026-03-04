<?php
session_start();
include 'koneksi.php';

/* ==========================
   VALIDASI LOGIN
========================== */
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'customer') {
    header("Location: dashboard_owner.php");
    exit();
}

/* ==========================
   VALIDASI ID PESANAN
========================== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard_customer.php");
    exit();
}

$id = intval($_GET['id']);

/* ==========================
   AMBIL DATA PESANAN
========================== */
$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id = ? AND id_user = ?");
$stmt->bind_param("ii", $id, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Pesanan tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f5f5f5;
    margin:0;
}

.header{
    background:#111;
    color:white;
    padding:15px;
    text-align:center;
}

.container{
    padding:20px;
}

.card{
    background:white;
    border-radius:15px;
    padding:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

.row{
    margin-bottom:15px;
}

.label{
    font-weight:bold;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    color:white;
}

.menunggu { background:orange; }
.proses { background:#007bff; }
.selesai { background:green; }

img{
    max-width:100%;
    border-radius:10px;
    margin-top:10px;
}

.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    background:#111;
    color:white;
    padding:12px;
    border-radius:10px;
}
</style>
</head>
<body>

<div class="header">
    Detail Pesanan
</div>

<div class="container">

<div class="card">

<div class="row">
<span class="label">Order ID:</span><br>
#ORD-2025-<?= str_pad($data['id'],3,'0',STR_PAD_LEFT); ?>
</div>

<div class="row">
<span class="label">Jenis Baju:</span><br>
<?= htmlspecialchars($data['jenis_baju']); ?>
</div>

<div class="row">
<span class="label">Ukuran:</span><br>
<?= htmlspecialchars($data['ukuran']); ?>
</div>

<div class="row">
<span class="label">Catatan:</span><br>
<?= nl2br(htmlspecialchars($data['catatan'] ?? '-')); ?>
</div>

<div class="row">
<span class="label">Status:</span><br>
<?php
$status = strtolower($data['status'] ?? 'menunggu');
$class = "menunggu";

if (strpos($status, 'produksi') !== false) $class = "proses";
if (strpos($status, 'selesai') !== false) $class = "selesai";
?>
<span class="badge <?= $class; ?>">
<?= htmlspecialchars($data['status']); ?>
</span>
</div>

<div class="row">
<span class="label">Tanggal:</span><br>
<?php
if (!empty($data['tanggal'])) {
    echo date('d M Y H:i', strtotime($data['tanggal']));
} else {
    echo '-';
}
?>
</div>

<?php if (!empty($data['desain_gambar'])) { ?>
<div class="row">
<span class="label">Desain:</span><br>
<img src="uploads/<?= htmlspecialchars($data['desain_gambar']); ?>">
</div>
<?php } ?>

</div>

<a href="dashboard_customer.php" class="back">
← Kembali ke Dashboard
</a>

</div>

</body>
</html>
