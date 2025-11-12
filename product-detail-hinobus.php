<?php
include 'admin/config.php';

// Aktifkan WebP loader
include 'webp_loader.php';
ob_start('convertImgToWebp');

$slug = $conn->real_escape_string($_GET['slug'] ?? '');
if ($slug === '') {
  die("Produk tidak ditemukan.");
}

// Ambil data produk
$res = $conn->query("SELECT * FROM produk WHERE slug='$slug'");
if (!$res || $res->num_rows == 0) {
  die("Produk tidak ditemukan.");
}
$produk = $res->fetch_assoc();
$produk_id = (int) $produk['id']; // dipakai untuk ambil spesifikasi & karoseri

// Ambil data karoseri terkait
$karoseri = [];
$qk = $conn->query("
  SELECT k.nama, k.slug
  FROM produk_karoseri pk
  JOIN karoseri k ON pk.karoseri_id = k.id
  WHERE pk.produk_id = $produk_id
");
while ($row = $qk->fetch_assoc()) {
  $karoseri[] = $row;
}

// Daftar grup spesifikasi
$spec_groups = [
  'PERFORMA' => ['label' => 'PERFORMA'],
  'MODEL MESIN' => ['label' => 'MODEL MESIN'],
  'KOPLING' => ['label' => 'KOPLING'],
  'TRANSMISI' => ['label' => 'TRANSMISI'],
  'KEMUDI' => ['label' => 'KEMUDI'],
  'SUMBU' => ['label' => 'SUMBU'],
  'REM' => ['label' => 'REM'],
  'RODA & BAN' => ['label' => 'RODA & BAN'],
  'SISTIM LISTRIK ACCU' => ['label' => 'SISTIM LISTRIK ACCU'],
  'TANGKI SOLAR' => ['label' => 'TANGKI SOLAR'],
  'DIMENSI' => ['label' => 'DIMENSI'],
  'SUSPENSI' => ['label' => 'SUSPENSI'],
  'BERAT CHASIS' => ['label' => 'BERAT CHASIS'],
];

// Ambil spesifikasi produk
$specs = [];
$res_spec = $conn->query("
  SELECT grup, label, nilai, sort_order
  FROM produk_spesifikasi
  WHERE produk_id = $produk_id
  ORDER BY FIELD(grup, '" . implode("','", array_keys($spec_groups)) . "'), sort_order ASC
");
while ($row = $res_spec->fetch_assoc()) {
  $specs[$row['grup']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-P7TN9DJW');
  </script>
  <!-- End Google Tag Manager -->

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Dealer Resmi Hino Indonesia - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan untuk seluruh Indonesia, khususnya Jabodetabek dan Jawa Barat. Hubungi Nathan Hino sekarang juga! 0859-7528-7684" />
  <meta name="keywords" content="Dealer Hino, Dealer Hino Jakarta, Promo Truk Hino 2025, Harga Truk Hino Dutro, Hino Ranger 500 Series, Kredit Truk Hino Jakarta, Cicilan Truk Hino, Dealer Resmi Hino Indonesia, Jual Truk Hino Jakarta, Hino Euro 4 Terbaru, Harga Truk Hino Jabodetabek, Dealer Hino Tangerang, Bekasi, Depok, Bogor, Bandung, Truk Hino untuk Bisnis, Truk Hino Angkut Barang, Sales Hino Resmi Jakarta, Leasing Truk Hino, Hino Dump Truck, Truk Hino Termurah, Bengkel & Servis Hino Resmi" />
  <meta name="author" content="Nathan Hino" />
  <title>Hino Bus Series | Harga & Promo Truk Hino Terbaru 2025</title>

  <link rel="icon" type="image/png" href="images/favicon.png" sizes="32x32" />
  <link rel="apple-touch-icon" href="images/favicon.png" />
  <link rel="canonical" href="https://dealerhinoindonesia.com/hinobus.php" />

  <!-- Google Tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-EC6CVWN4SB"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-EC6CVWN4SB');
  </script>

  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/product/hero.css" />
  <link rel="stylesheet" href="css/product/kategori.css" />
  <link rel="stylesheet" href="css/product/product.css" />
  <link rel="stylesheet" href="css/product/detail.css" />

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Custom Search Bar Style -->
  <style>
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

  <!-- Open Graph -->
  <meta property="og:title" content="Dealer Hino Indonesia | Harga & Promo Truk Hino Terbaru 2025" />
  <meta property="og:description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan." />
  <meta property="og:image" content="https://dealerhinoindonesia.com/images/promohino1.jpg" />
  <meta property="og:url" content="https://dealerhinoindonesia.com/" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Dealer Hino Indonesia">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025" />
  <meta name="twitter:description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino dengan harga terbaik dan promo terbaru 2025." />
  <meta name="twitter:image" content="https://dealerhinoindonesia.com/images/promohino1.jpg" />

  <!-- Structured Data -->
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
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
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
  <!-- Google Tag Manager (noscript) -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P7TN9DJW" height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>
  <!-- End Google Tag Manager (noscript) -->

  <!-- Header -->
  <header>
    <div class="container header-content navbar">
      <div class="header-title">
        <a href="https://dealerhinoindonesia.com">
          <img src="images/logo3.png" alt="Logo Hino" loading="lazy" style="height: 60px" />
        </a>
      </div>
      <div class="hamburger-menu">&#9776;</div>
      <nav class="nav links">
        <a href="/">Home</a>
        <a href="hino300">Hino 300 Series</a>
        <a href="hino500">Hino 500 Series</a>
        <a href="hinobus">Hino Bus Series</a>
        <a href="contact">Contact</a>
        <a href="artikel">Blog & Artikel</a>
      </nav>
    </div>
  </header>

  <!-- Hero Product -->
  <section class="hero-product">
    <img src="images/Euro 4 Hino Bus.jpeg" alt="Hino Bus Series" class="hero-product-img" />
  </section>

  <!-- Produk Pilihan -->
  <div class="kategori-section">
    <div class="kategori">
      <h1>Hino Bus Series</h1>
      <img src="images/euro4.png" alt="Euro4 Logo">
    </div>

    <div class="produk-controls">
      <div class="tabs">
        <a href="hinobus.php#kategori-section" class="tab">ALL</a>
        <a href="hinobus.php#kategori-section" class="tab">BUS MIKRO</a>
        <a href="hinobus.php#kategori-section" class="tab">BUS MEDIUM</a>
        <a href="hinobus.php#kategori-section" class="tab">BUS BESAR</a>
      </div>
      <input type="text" id="search-input" placeholder="Cari produk..." />
    </div>
  </div>

  <!-- Hero Section -->
  <section id="hero-section" class="hero-diagonal">
    <div class="hero-text">
      <h3>BUS</h3>
      <h1><?= htmlspecialchars($produk['nama_produk']) ?></h1>
    </div>
    <div class="hero-image">
      <img src="admin/uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
    </div>
  </section>

  <!-- Detail Spesifikasi -->
  <section class="detail-specs">
    <h2>Spesifikasi</h2>

    <!-- Karoseri -->
    <?php if (!empty($karoseri)): ?>
      <div class="spec-accordion">
        <button class="accordion-btn">KAROSERI <span class="icon">+</span></button>
        <div class="accordion-content">
          <div class="karoseri-grid">
            <?php foreach ($karoseri as $k): ?>
              <div class="karoseri-item">
                <img src="admin/uploads/karoseri/<?= htmlspecialchars($k['slug']) ?>.png" alt="<?= htmlspecialchars($k['nama']) ?>">
                <p><?= htmlspecialchars($k['nama']) ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Spesifikasi Lain -->
    <?php foreach ($spec_groups as $slug => $meta):
      if (!empty($specs[$slug])): ?>
        <div class="spec-accordion">
          <button class="accordion-btn"><?= htmlspecialchars($meta['label']) ?> <span class="icon">+</span></button>
          <div class="accordion-content">
            <table class="spec-table">
              <tbody>
                <?php foreach ($specs[$slug] as $r): ?>
                  <tr>
                    <td class="spec-label"><?= htmlspecialchars($r['label']) ?></td>
                    <td class="spec-value"><?= htmlspecialchars($r['nilai']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
    <?php endif; endforeach; ?>
  </section>

  <!-- Back to Top -->
  <button id="back-to-top" title="Kembali ke atas">&#8679;</button>

  <!-- JS Accordion & Back to Top -->
  <script>
    document.querySelectorAll('.accordion-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        btn.classList.toggle('active');
        const content = btn.nextElementSibling;
        content.style.display = content.style.display === "block" ? "none" : "block";
      });
    });

    const backToTopBtn = document.getElementById("back-to-top");
    window.addEventListener("scroll", () => {
      backToTopBtn.style.display = window.scrollY > 300 ? "block" : "none";
    });
    backToTopBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>

  <!-- WhatsApp Chat -->
  <script src="https://static.elfsight.com/platform/platform.js" async></script>
  <div class="elfsight-app-1c150e27-6597-4113-becd-79df393b9756" data-elfsight-app-lazy></div>

  <?php include 'footer.php'; ?>
</body>
</html>
<?php ob_end_flush(); ?>
