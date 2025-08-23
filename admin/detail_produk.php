<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php'; // koneksi database

// Ambil ID produk dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: produk.php");
    exit();
}

$id = (int)$_GET['id'];

// Ambil data produk dari database
$sql = "SELECT p.id, p.nama_produk, p.gambar, p.deskripsi, s.nama_series
        FROM produk p
        LEFT JOIN series s ON p.series_id = s.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit();
}

$produk = $result->fetch_assoc();
$imgPath = "../uploads/" . ($produk['gambar'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk - <?= htmlspecialchars($produk['nama_produk']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background: #0d6efd;
      color: white;
      padding-top: 20px;
      position: fixed;
      width: 220px;
      text-align: center;
    }
    .sidebar img {
      max-width: 180px;
      margin-bottom: 20px;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      margin: 4px 0;
      transition: background 0.2s;
      text-align: left;
    }
    .sidebar a:hover, .sidebar a.active {
      background: #0b5ed7;
      border-radius: 6px;
    }
    .content {
      margin-left: 220px;
      padding: 20px;
    }
    .card img {
      max-width: 100%;
      border-radius: 8px;
    }
    .btn-primary {
      background: #0d6efd;
      border: none;
    }
    .btn-primary:hover {
      background: #0b5ed7;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="../images/logo3.png" alt="Logo Hino">
    </div>
    <a href="index.php">Dashboard</a>
    <a href="artikel.php">Artikel</a>
    <a href="produk.php" class="active">Produk</a>
    <a href="pesan.php">Pesan Customer</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="dashboard-header">
      <h2>📦 Detail Produk</h2>
      <p>Informasi lengkap produk Hino yang dipilih.</p>
    </div>

    <div class="card p-4 shadow-sm">
      <h3><?= htmlspecialchars($produk['nama_produk']); ?></h3>
      <p><strong>Series:</strong> <?= htmlspecialchars($produk['nama_series'] ?? '-'); ?></p>
      <?php if (!empty($produk['gambar']) && file_exists($imgPath)): ?>
        <img src="<?= $imgPath; ?>" alt="<?= htmlspecialchars($produk['nama_produk']); ?>">
      <?php else: ?>
        <p class="text-muted">Tidak ada gambar</p>
      <?php endif; ?>

      <?php if (!empty($produk['deskripsi'])): ?>
        <h5 class="mt-3">Deskripsi / Spesifikasi:</h5>
        <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
      <?php else: ?>
        <p class="text-muted mt-3">Deskripsi belum tersedia.</p>
      <?php endif; ?>

      <a href="produk.php" class="btn btn-primary mt-3">Kembali ke Daftar Produk</a>
    </div>
  </div>
</body>
</html>
