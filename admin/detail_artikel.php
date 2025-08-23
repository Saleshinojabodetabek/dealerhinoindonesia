<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include 'config.php';

$id = (int)($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM artikel WHERE id=$id");
if(!$res || $res->num_rows==0) header("Location: artikel.php");
$artikel = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
<div class="card shadow">
<div class="card-header bg-primary text-white"><h4>Detail Artikel</h4></div>
<div class="card-body">
<h5>Judul</h5>
<p><?= htmlspecialchars($artikel['judul']) ?></p>
<h5>Konten</h5>
<p><?= nl2br(htmlspecialchars($artikel['konten'])) ?></p>
<h5>Tanggal</h5>
<p><?= $artikel['tanggal'] ?></p>
<a href="artikel.php" class="btn btn-secondary mt-3">Kembali</a>
</div>
</div>
</div>
</body>
</html>
