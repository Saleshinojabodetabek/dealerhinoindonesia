<?php
// Ambil data kategori dari API
$kategoriData = json_decode(file_get_contents("https://dealerhinoindonesia.com/admin/api/get_kategoriartikel.php"), true);

// Ambil parameter filter
$search = $_GET['search'] ?? '';
$selectedKategori = $_GET['kategori'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;

// Bangun URL API artikel
$apiUrl = "https://dealerhinoindonesia.com/admin/api/get_artikel.php?page=$page&perPage=$perPage";
if ($search !== '') {
    $apiUrl .= "&search=" . urlencode($search);
}
if ($selectedKategori !== '') {
    $apiUrl .= "&kategori=" . urlencode($selectedKategori);
}

// Ambil data artikel dari API
$response = json_decode(file_get_contents($apiUrl), true);

// Pastikan data valid
$page = $response['page'] ?? 1;
$totalPages = $response['totalPages'] ?? 1;
$artikel = $response['data'] ?? [];

// Buat base URL pagination
$baseUrl = "?";
if ($search !== '') $baseUrl .= "search=" . urlencode($search) . "&";
if ($selectedKategori !== '') $baseUrl .= "kategori=" . urlencode($selectedKategori) . "&";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan untuk seluruh Indonesia. Hubungi Nathan Hino sekarang juga! 0859-7528-7684" />
    <meta name="keywords" content="Dealer Hino, Dealer Hino Jakarta, Promo Truk Hino 2025, Harga Truk Hino Dutro, Hino Ranger 500 Series, Kredit Truk Hino Jakarta, Cicilan Truk Hino, Dealer Resmi Hino Indonesia, Jual Truk Hino Jakarta, Hino Euro 4 Terbaru, Harga Truk Hino Jabodetabek, Dealer Hino Tangerang, Bekasi, Depok, Bogor, Bandung, Truk Hino untuk Bisnis, Truk Hino Angkut Barang, Sales Hino Resmi Jakarta, Leasing Truk Hino, Hino Dump Truck, Truk Hino Termurah, Bengkel & Servis Hino Resmi" />
    <meta name="author" content="Nathan Hino" />
    <link rel="canonical" href="https://dealerhinoindonesia.com/artikel.php" />
    <title>Blog & Artikel Resmi Dealer Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EC6CVWN4SB"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-EC6CVWN4SB');
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/favicon.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="images/favicon.png" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/blog/artikel.css" />
    <link rel="stylesheet" href="css/blog/hero.css" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />

    <!-- JS -->
    <script src="js/script.js"></script>

    <!-- Open Graph -->
  <meta property="og:title" content="Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025" />
  <meta property="og:description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan." />
  <meta property="og:image" content="https://dealerhinoindonesia.com/images/promohino1.jpg" />
  <meta property="og:url" content="https://dealerhinoindonesia.com/" />
  <meta property="og:type" content="website" />
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025" />
  <meta name="twitter:description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino dengan harga terbaik dan promo terbaru 2025." />
  <meta name="twitter:image" content="https://dealerhinoindonesia.com/images/promohino1.jpg" />
  
  <script type="application/ld+json">
  {
  "@context": "https://schema.org",
  "@type": "AutoDealer",
  "name": "Dealer Hino Indonesia",
  "image": "https://dealerhinoindonesia.com/images/promohino1.jpg",
  "@id": "https://dealerhinoindonesia.com/",
  "url": "https://dealerhinoindonesia.com/",
  "telephone": "+62-859-7528-7684",
  "priceRange": "$$$",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Golf Lake Ruko Venice, Jl. Lkr. Luar Barat No.78 Blok B, RT.9/RW.14",
    "addressLocality": "Jakarta Barat",
    "addressRegion": "DKI Jakarta",
    "postalCode": "11730",
    "addressCountry": "ID"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": -6.1305504,
    "longitude": 106.7279824
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
      ],
      "opens": "08:00",
      "closes": "17:00"
    }
  ],
  "sameAs": [
    "https://www.facebook.com/profile.php?id=61573843992250",
    "https://www.instagram.com/saleshinojabodetabek",
    "https://www.tiktok.com/@saleshinoindonesia"
  ]
  }
  </script>
</head>
<body>

<!-- Header -->
<header>
    <div class="container header-content navbar">
        <div class="header-title">
            <a href="https://dealerhinoindonesia.com">
                <img src="images/logo3.png" alt="Logo Hino Indonesia" loading="lazy" style="height: 60px"/>
            </a>
        </div>
        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="https://dealerhinoindonesia.com/">Home</a>
          <a href="https://dealerhinoindonesia.com/hino300.php">Hino 300 Series</a>
          <a href="https://dealerhinoindonesia.com/hino500.php">Hino 500 Series</a>
          <a href="https://dealerhinoindonesia.com/hinobus.php">Hino Bus Series</a>
          <a href="https://dealerhinoindonesia.com/contact.php">Contact</a>
          <a href="https://dealerhinoindonesia.com/artikel.php" class="active">Blog & Artikel</a>
        </nav>
    </div>
</header>

<!-- Hero -->
<section class="hero-blog">
  <div class="hero-blog-content">
    <div class="hero-blog-text">
      <h1>Jelajahi Artikel</h1>
      <p>Dapatkan informasi terbaru, tips, dan berita seputar Hino untuk mendukung bisnis Anda.</p>
      <a href="#artikel" class="btn-blog">Lihat Artikel</a>
    </div>
    <div class="hero-blog-image"></div>
  </div>
  <div class="dot dot-yellow"></div>
  <div class="dot dot-blue"></div>
</section>

<!-- Blog & Artikel -->
<section class="content-section" id="artikel">
    <div class="container">

        <!-- Filter -->
        <form method="get" class="blog-filter" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>" />
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php if (is_array($kategoriData)): ?>
                    <?php foreach ($kategoriData as $kat): ?>
                        <option value="<?= htmlspecialchars($kat['nama']) ?>" <?= $selectedKategori === $kat['nama'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="submit">Filter</button>
        </form>

        <!-- Artikel Grid -->
        <div class="blog-grid">
            <?php if (is_array($artikel) && count($artikel) > 0): ?>
                <?php foreach ($artikel as $row): ?>
                    <?php
                    // Deteksi otomatis: jika sudah URL penuh, jangan tambahkan prefix lagi
                    if (preg_match('/^https?:\/\//', $row['gambar'])) {
                        // Sudah URL penuh, langsung pakai
                        $gambarPath = $row['gambar'];
                    } else {
                        // Tambahkan base URL lengkap agar tidak salah arah
                        $gambarPath = 'https://dealerhinoindonesia.com/admin/uploads/artikel/' . $row['gambar'];
                    }
                    ?>
                    <div class="blog-post">
                        <img src="<?= htmlspecialchars($gambarPath) ?>" 
                            alt="Artikel Hino - <?= htmlspecialchars($row['judul']) ?>" 
                            loading="lazy">
                        <h2>
                            <a href="detail_artikel.php?slug=<?= urlencode($row['slug']) ?>">
                                <?= htmlspecialchars($row['judul']) ?>
                            </a>
                        </h2>
                        <p><?= substr(strip_tags($row['isi']), 0, 120) ?>...</p>
                        <div class="card-footer">
                            <a href="detail_artikel.php?slug=<?= urlencode($row['slug']) ?>">Baca Selengkapnya</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada artikel yang ditemukan.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination" aria-label="Navigasi halaman">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="<?= $i === $page ? 'active' : '' ?>" href="<?= $baseUrl ?>page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- WhatsApp Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-1c150e27-6597-4113-becd-79df393b9756" data-elfsight-app-lazy></div>

<?php include 'footer.php'; ?>

<script>feather.replace();</script>
</body>
</html>
