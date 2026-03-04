<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Pesanan</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#f5f5f5;
}

.header{
    padding:15px;
    text-align:center;
    font-weight:bold;
    background:white;
    border-bottom:1px solid #ddd;
}

.container{
    padding:20px;
}

.upload-box{
    border:2px dashed #ccc;
    padding:25px;
    text-align:center;
    border-radius:12px;
    margin-bottom:20px;
    background:white;
    cursor:pointer;
}

.upload-box:hover{
    border-color:#111;
}

.upload-box input{
    margin-top:10px;
}

.preview{
    margin-top:15px;
    max-width:100%;
    border-radius:10px;
    display:none;
}

label{
    font-weight:600;
    display:block;
    margin-bottom:5px;
}

select, textarea{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
    margin-bottom:15px;
}

textarea{
    height:100px;
}

button{
    width:100%;
    background:#111;
    color:white;
    padding:15px;
    border:none;
    border-radius:12px;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
}

button:hover{
    background:#333;
}

.back{
    text-decoration:none;
    display:block;
    margin-bottom:15px;
    color:black;
}
</style>
</head>

<body>

<div class="header">
    Buat Pesanan Baru
</div>

<div class="container">

<a href="dashboard_customer.php" class="back">← Kembali ke Dashboard</a>

<form action="proses_pesanan.php" method="POST" enctype="multipart/form-data">

<div class="upload-box">
    <strong>Upload Design</strong><br>
    <small>PNG / JPG maksimal 5MB</small><br>
    <input type="file" name="desain" accept="image/*" onchange="previewImage(event)">
    <img id="preview" class="preview">
</div>

<label>Jenis Baju</label>
<select name="jenis_baju" required>
    <option value="">Pilih jenis baju</option>
    <option value="Kebaya">Kebaya</option>
    <option value="Gamis">Gamis</option>
    <option value="Dress">Dress</option>
</select>

<label>Ukuran</label>
<select name="ukuran" required>
    <option value="">Pilih ukuran</option>
    <option value="S">S</option>
    <option value="M">M</option>
    <option value="L">L</option>
    <option value="XL">XL</option>
</select>

<label>Catatan Tambahan</label>
<textarea name="catatan" placeholder="Warna, model, detail lainnya"></textarea>

<button type="submit">✔ Simpan Pesanan</button>

</form>

</div>

<script>
function previewImage(event){
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = "block";
}
</script>

</body>
</html>
