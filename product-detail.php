<?php
include "admin/config.php";

// Ambil ID dari URL
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Produk tidak ditemukan.");
}

// Query produk berdasarkan ID
$sql = "SELECT * FROM produk WHERE id = $id LIMIT 1";
$res = $conn->query($sql);
if (!$res || $res->num_rows === 0) {
    die("Produk tidak ditemukan.");
}
$produk = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($produk['nama_produk']) ?> | Detail Produk</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/product/detail.css">
</head>
<body>

<header>
  <div class="container header-content navbar">
    <div class="header-title">
      <a href="index.php"><img src="images/logo3.png" alt="Logo Hino" style="height:60px"/></a>
    </div>
    <nav class="nav links">
      <a href="index.php">Home</a>
      <a href="#products-section">Produk</a>
      <a href="contact.php">Contact</a>
    </nav>
  </div>
</header>

<!-- Hero Detail -->
<section class="detail-hero">
  <img src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
</section>

<!-- Konten Detail -->
<section class="detail-container">
  <div class="detail-info">
    <h1><?= htmlspecialchars($produk['nama_produk']) ?></h1>
    <p><strong>Varian:</strong> <?= htmlspecialchars($produk['varian']) ?></p>
    
    <?php if (!empty($produk['spesifikasi'])): ?>
      <div class="detail-spesifikasi">
        <h2>Spesifikasi</h2>
        <p><?= nl2br(htmlspecialchars($produk['spesifikasi'])) ?></p>
      </div>
    <?php endif; ?>

    <?php if (!empty($produk['karoseri_gambar'])): ?>
      <div class="detail-karoseri">
        <h2>Karoseri</h2>
        <img src="uploads/karoseri/<?= htmlspecialchars($produk['karoseri_gambar']) ?>" alt="Karoseri <?= htmlspecialchars($produk['nama_produk']) ?>">
      </div>
    <?php endif; ?>
  </div>
</section>

</body>
</html>
