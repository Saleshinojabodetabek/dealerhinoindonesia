<?php
include 'admin/config.php';

// aktifkan webp loader
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
$produk_id = (int)$produk['id']; // masih dipakai untuk ambil spesifikasi & karoseri


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

// Daftar grup spesifikasi (urutan & label)
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


<!-- HTML -->

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
    <title>Hino 500 Series | Harga & Promo Truk Hino Terbaru 2025</title>
    <link rel="icon" type="image/png" href="images/favicon.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="images/favicon.png" />
    <link rel="canonical" href="https://dealerhinoindonesia.com/hino500.php" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/product/hero.css" />
    <link rel="stylesheet" href="css/product/kategori.css" />
    <link rel="stylesheet" href="css/product/product.css" />
    <link rel="stylesheet" href="css/product/detail.css" />

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
            <img src="images/logo3.png" alt="Logo Hino" loading="lazy" style="height: 60px"/>
          </a>
        </div>
        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="https://dealerhinoindonesia.com/">Home</a>
          <a href="https://dealerhinoindonesia.com/hino300.php">Hino 300 Series</a>
          <a href="https://dealerhinoindonesia.com/hino500.php">Hino 500 Series</a>
          <a href="https://dealerhinoindonesia.com/hinobus.php">Hino Bus Series</a>
          <a href="https://dealerhinoindonesia.com/contact.php">Contact</a>
          <a href="https://dealerhinoindonesia.com/artikel.php">Blog & Artikel</a>
        </nav>
      </div>
    </header>

    <!-- Hero Product — Gambar Penuh -->
    <section class="hero-product">
      <img src="images/Euro 4 Hino 500.jpeg" alt="Hino 500 Series" class="hero-product-img" />
    </section>

    <!-- Produk Pilihan -->
    <div class="kategori-section">
      <div class="kategori">
        <h1>Hino 500 Series</h1>
        <img src="images/euro4.png" alt="Euro4 Logo">
      </div>

      <div class="produk-controls">
        <div class="tabs">
          <a href="hino500.php#kategori-section" class="tab">ALL</a>
          <a href="hino500.php#kategori-section" class="tab">CARGO</a>
          <a href="hino500.php#kategori-section" class="tab">DUMP</a>
          <a href="hino500.php#kategori-section" class="tab">MIXER</a>
          <a href="hino500.php#kategori-section" class="tab">TRACTOR HEAD</a>
        </div>


        <!-- Search Bar -->
        <input type="text" id="search-input" placeholder="Cari produk..." />
      </div>
    </div>

<!-- Hero Section -->
<section id="hero-section" class="hero-diagonal">
  <div class="hero-text">
    <h3>TRUK</h3>
    <h1><?= htmlspecialchars($produk['nama_produk']) ?></h1>
  </div>
  <div class="hero-image">
    <img src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" 
         alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
  </div>
</section>

<!-- Detail Specs -->
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
            <img src="uploads/karoseri/<?= htmlspecialchars($k['slug']) ?>.png" 
                alt="<?= htmlspecialchars($k['nama']) ?>">
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

<!-- Back to Top Button -->
<button id="back-to-top" title="Kembali ke atas">&#8679;</button>


<!-- JS -->
<script>
document.querySelectorAll('.accordion-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.classList.toggle('active');
    let content = btn.nextElementSibling;
    content.style.display = content.style.display === "block" ? "none" : "block";
  });
});
// Back to Top Button
const backToTopBtn = document.getElementById("back-to-top");

// Tampilkan tombol ketika scroll lebih dari 300px
window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    backToTopBtn.style.display = "block";
  } else {
    backToTopBtn.style.display = "none";
  }
});

// Scroll ke atas ketika diklik
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
