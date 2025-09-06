<?php
session_start();
include 'admin/config.php'; // sesuaikan path ke file koneksi

// Ambil ID produk dari URL
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Produk tidak ditemukan.");
}

// Query produk
$sql = "SELECT * FROM produk WHERE id = $id";
$res = $conn->query($sql);
if (!$res || $res->num_rows === 0) {
    die("Produk tidak ditemukan.");
}
$produk = $res->fetch_assoc();

// Ambil spesifikasi produk
$specs = [];
$specRes = $conn->query("SELECT * FROM spesifikasi_produk WHERE produk_id = $id ORDER BY grup, id ASC");
if ($specRes && $specRes->num_rows > 0) {
    while ($row = $specRes->fetch_assoc()) {
        $specs[$row['grup']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="description"
      content="Dealer Resmi Hino Indonesia - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan untuk seluruh Indonesia, khususnya Jabodetabek dan Jawa Barat. Hubungi Nathan Hino sekarang juga! 0859-7528-7684"
    />
    <meta
      name="keywords"
      content="Dealer Hino Indonesia, Dealer Resmi Hino, Jual Truk Hino, Harga Truk Hino Terbaru, Promo Truk Hino 2025, Hino Dutro 300 Series, Hino Ranger 500 Series, Hino Profia, Hino Bus, Hino Euro 4, Kredit Truk Hino, Cicilan Truk Hino, DP Truk Hino, Harga Hino Jabodetabek, Dealer Hino Jakarta, Dealer Hino Tangerang, Dealer Hino Bekasi, Dealer Hino Bogor, Dealer Hino Depok, Dealer Hino Bandung, Penjualan Hino Indonesia, Sales Hino Resmi, Sales Truk Hino, Promo Hino Jabodetabek, Harga Hino Termurah, Hino Dump Truck, Hino Wingbox, Hino Box, Hino Trailer, Spare Part Hino, Servis Hino Resmi, Bengkel Hino, Truk Hino Angkutan, Truk Hino Logistik, Truk Hino Tambang, Hino Termurah 2025, Beli Hino Baru, Truk Hino Kredit, Leasing Truk Hino, Hino Dutro Murah, Hino Ranger Murah, Harga Hino Profia, Penawaran Dealer Hino, Truk Hino untuk Bisnis, Truk Hino Angkut Barang, Truk Hino Ekspedisi, Hino Resmi Indonesia, Hino Terpercaya, Harga Hino Resmi, Truk Hino Jabodetabek, Truk Hino Jawa Barat, Dealer Hino Terlengkap"
    />
    <meta name="author" content="Nathan Hino" />
    <title>Dealer Resmi Hino Indonesia | Harga & Promo Truk Hino Terbaru 2025</title>
    <link rel="icon" type="image/png" href="images/favicon.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="images/favicon.png" />
    <link rel="canonical" href="https://www.dealerhinoindonesia.id/" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/product/hero.css" />
    <link rel="stylesheet" href="css/product/kategori.css" />
    <link rel="stylesheet" href="css/product/product.css" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- JS -->
    <script src="js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <style>
      /* Tambahan CSS untuk search */
      .produk-controls {
        text-align: center;
        margin: 20px 0;
      }

      #search-input {
        width: 100%;
        max-width: 400px;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
      }
    </style>
  </head>
  <body>

    <!-- Header -->
    <header>
      <div class="container header-content navbar">
        <div class="header-title">
          <a href="https://dealerhinoindonesia.id">
            <img src="images/logo3.png" alt="Logo Hino" loading="lazy" style="height: 60px"/>
          </a>
        </div>
        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="index.php">Home</a>
          <a href="hino300.php">Hino 300 Series</a>
          <a href="hino500.php">Hino 500 Series</a>
          <a href="hinobus.php">Hino Bus Series</a>
          <a href="contact.php">Contact</a>
          <a href="admin/artikel.php">Blog & Artikel</a>
        </nav>
      </div>
    </header>

    <!-- Hero Product — Gambar Penuh -->
    <section class="hero-product">
      <img src="images/Euro 4 Hino 300.jpeg" alt="Hino 300 Series" class="hero-product-img" />
    </section>

    <!-- Produk Pilihan -->
    <div class="kategori-section">
      <div class="kategori">
        <h1>Hino 300 Series</h1>
        <img src="images/euro4.png" alt="Euro4 Logo">
      </div>

      <div class="produk-controls">
        <div class="tabs">
          <div class="tab active">ALL</div>
          <div class="tab">CARGO</div>
          <div class="tab">DUMP</div>
          <div class="tab">MIXER</div>
        </div>

        <!-- Search Bar -->
        <input type="text" id="search-input" placeholder="Cari produk..." />
      </div>
    </div>

    <!-- Product Detail -->
    <section class="product-detail">
    <div class="product-top">
        <div class="img-gallery">
        <img src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" 
            alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
        </div>
        <div class="product-info">
        <h1><?= htmlspecialchars($produk['nama_produk']) ?></h1>
        <?php if (!empty($produk['harga'])): ?>
            <div class="price">Harga OTR: 
            <strong>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></strong>
            </div>
        <?php endif; ?>
        <div class="actions">
            <a href="https://wa.me/6285975287684?text=Halo%20saya%20ingin%20tanya%20tentang%20<?= urlencode($produk['nama_produk']) ?>" 
            class="btn-contact">Hubungi Sales</a>
            <button class="btn-wishlist">Wishlist</button>
        </div>
        </div>
    </div>

    <div class="product-specs">
        <?php foreach ($specs as $grup => $rows): ?>
        <div class="spec-group">
            <h3><?= htmlspecialchars($grup) ?></h3>
            <table>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <th><?= htmlspecialchars($r['label']) ?></th>
                    <td><?= htmlspecialchars($r['nilai']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
        <?php endforeach; ?>
    </div>
    </section>
</body>
</html>
