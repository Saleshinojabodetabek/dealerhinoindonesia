<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include 'config.php';

$id=(int)($_GET['id']??0);
if($id<=0) header("Location: artikel.php");

$res=$conn->query("SELECT a.*, k.nama AS kategori FROM artikel a LEFT JOIN kategori_artikel k ON a.kategori_id=k.id WHERE a.id=$id");
if(!$res || $res->num_rows==0) header("Location: artikel.php");
$artikel = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family:"Segoe UI",sans-serif; background:#f8f9fa; }
.sidebar { height:100vh; background:#0d6efd; color:white; padding-top:20px; position:fixed; width:220px; text-align:center; }
.sidebar img { max-width:180px; margin-bottom:20px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; margin:4px 0; text-align:left; transition: background 0.2s; }
.sidebar a:hover, .sidebar a.active { background:#0b5ed7; border-radius:6px; }
.content { margin-left:220px; padding:20px; }
.dashboard-header { background: linear-gradient(90deg, #0d6efd, #0b5ed7); color:white; padding:20px; border-radius:12px; margin-bottom:25px; }
</style>
</head>
<body>
<div class="sidebar">
  <div class="text-center mb-4">
    <img src="../images/logo3.webp" alt="Logo Hino">
  </div>
  <a href="index.php">Dashboard</a>
  <a href="artikel.php" class="active">Artikel</a>
  <a href="produk.php">Produk</a>
  <a href="pesan.php">Pesan Customer</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="dashboard-header">
<h2>📰 Detail Artikel</h2>
<p>Informasi lengkap artikel ini.</p>
</div>

<div class="card shadow">
<div class="card-body">
<table class="table table-bordered">
<tr><th>Judul</th><td><?= htmlspecialchars($artikel['judul']) ?></td></tr>
<tr><th>Kategori</th><td><?= htmlspecialchars($artikel['kategori']) ?></td></tr>
<tr><th>Isi</th><td><?= nl2br(htmlspecialchars($artikel['isi'])) ?></td></tr>
<tr><th>Gambar</th><td>
<?php if($artikel['gambar'] && file_exists("uploads/artikel/".$artikel['gambar'])): ?>
<img src="uploads/artikel/<?= htmlspecialchars($artikel['gambar']) ?>" width="200">
<?php else: echo "-"; endif; ?>
</td></tr>
<tr><th>Tanggal</th><td><?= $artikel['tanggal'] ?></td></tr>
</table>

<a href="artikel.php" class="btn btn-secondary mt-3">Kembali</a>
</div>
</div>
</div>
</body>
</html>
