<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];
$produk = $conn->query("SELECT produk.*, series.nama_series 
                        FROM produk 
                        JOIN series ON produk.series_id = series.id 
                        WHERE produk.id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .spec-box {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card p-4">
    <h3><?= $produk['nama_produk'] ?> (<?= $produk['nama_series'] ?>)</h3>
    <img src="../uploads/<?= $produk['gambar'] ?>" width="400" class="my-3">
    <h5>Deskripsi</h5>
    <p><?= nl2br($produk['deskripsi']) ?></p>

    <h5>Spesifikasi</h5>
    <div class="spec-box">
      <?= nl2br($produk['spesifikasi']) ?>
    </div>

    <a href="produk.php" class="btn btn-secondary mt-3">Kembali</a>
  </div>
</div>
</body>
</html>
