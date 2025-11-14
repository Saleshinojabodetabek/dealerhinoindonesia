<?php
$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
$response = json_decode(file_get_contents("https://dealerhinoindonesia.com/admin/api/get_artikel.php?perPage=100"), true);
$data = $response['data'] ?? [];
$artikel = null;

if ($slug !== '' && is_array($data)) {
  foreach ($data as $item) {
    if (isset($item['slug']) && $item['slug'] === $slug) {
      $artikel = $item;
      break;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <!-- Google Tag Manager -->
    <script>
      (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
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
    <meta
      name="description"
      content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan untuk seluruh Indonesia. Hubungi Nathan Hino sekarang juga! 0859-7528-7684"
    />
    <meta
      name="keywords"
      content="Dealer Hino, Dealer Hino Jakarta, Promo Truk Hino 2025, Harga Truk Hino Dutro, Hino Ranger 500 Series, Kredit Truk Hino Jakarta, Cicilan Truk Hino, Dealer Resmi Hino Indonesia, Jual Truk Hino Jakarta, Hino Euro 4 Terbaru, Harga Truk Hino Jabodetabek, Dealer Hino Tangerang, Bekasi, Depok, Bogor, Bandung, Truk Hino untuk Bisnis, Truk Hino Angkut Barang, Sales Hino Resmi Jakarta, Leasing Truk Hino, Hino Dump Truck, Truk Hino Termurah, Bengkel & Servis Hino Resmi"
    />
    <meta name="author" content="Nathan Hino" />
    <link
      rel="canonical"
      href="https://dealerhinoindonesia.com/detail_artikel.php?slug=<?= urlencode($artikel['slug'] ?? '') ?>"
    />
    <title><?= htmlspecialchars($artikel['judul'] ?? 'Artikel Tidak Ditemukan') ?> | Dealer Hino Indonesia</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EC6CVWN4SB"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      gtag('js', new Date());
      gtag('config', 'G-EC6CVWN4SB');
    </script>

    <!-- Favicon -->
    <link rel="icon" href="https://dealerhinoindonesia.com/images/favicon.png" type="image/png" sizes="32x32"/>
    <link rel="apple-touch-icon" href="images/favicon.png" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/blog/artikel.css" />
    <link rel="stylesheet" href="css/blog/hero.css" />

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap"
      rel="stylesheet"
    />

    <!-- JS -->
    <script src="js/script.js"></script>

    <!-- Open Graph -->
    <meta property="og:title" content="Dealer Hino Indonesia | Harga & Promo Truk Hino Terbaru 2025" />
    <meta
      property="og:description"
      content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan."
    />
    <meta property="og:image" content="https://dealerhinoindonesia.com/images/promohino1.webp" />
    <meta property="og:url" content="https://dealerhinoindonesia.com/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Dealer Hino Indonesia" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025" />
    <meta
      name="twitter:description"
      content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino dengan harga terbaik dan promo terbaru 2025."
    />
    <meta name="twitter:image" content="https://dealerhinoindonesia.com/images/promohino1.webp" />

    <!-- Schema.org -->
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "AutoDealer",
        "name": "Dealer Hino Indonesia",
        "image": "https://dealerhinoindonesia.com/images/promohino1.webp",
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
        "geo": { "@type": "GeoCoordinates", "latitude": -6.1305504, "longitude": 106.7279824 },
        "openingHoursSpecification": [{
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
          "opens": "08:00",
          "closes": "17:00"
        }],
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
      <iframe
        src="https://www.googletagmanager.com/ns.html?id=GTM-P7TN9DJW"
        height="0"
        width="0"
        style="display:none;visibility:hidden"
      ></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Header -->
    <header>
      <div class="container header-content navbar">
        <div class="header-title">
          <a href="https://dealerhinoindonesia.com">
            <img src="images/logo3.webp" alt="Logo Hino Indonesia" loading="lazy" style="height: 60px" />
          </a>
        </div>
        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="https://dealerhinoindonesia.com/">Home</a>
          <a href="https://dealerhinoindonesia.com/hino300">Hino 300 Series</a>
          <a href="https://dealerhinoindonesia.com/hino500">Hino 500 Series</a>
          <a href="https://dealerhinoindonesia.com/hinobus">Hino Bus Series</a>
          <a href="https://dealerhinoindonesia.com/contact">Contact</a>
          <a href="https://dealerhinoindonesia.com/artikel">Blog & Artikel</a>
        </nav>
      </div>
    </header>

    <!-- Konten Artikel -->
    <section class="detail-artikel">
      <div class="container">
        <div class="artikel-wrapper" style="display: flex; flex-wrap: wrap; gap: 30px;">
          <div class="artikel-main" style="flex: 1 1 65%;">
            <?php if ($artikel): ?>
              <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
              <p style="color: #888; font-size: 14px; margin-bottom: 15px;">
                Diposting pada <?= date('d M Y', strtotime($artikel['tanggal'] ?? 'now')) ?>
              </p>
              <img
                src="<?= htmlspecialchars($artikel['gambar']) ?>"
                alt="<?= htmlspecialchars($artikel['judul']) ?>"
                class="featured-image"
                style="width: 100%; height: auto; margin-bottom: 20px;"
              />
              <div class="isi-artikel"><?= nl2br($artikel['isi']) ?></div>
              <a href="artikel.php" class="btn-kembali" style="display:inline-block; margin-top:20px;">Kembali ke Daftar Artikel</a>
            <?php else: ?>
              <p>Artikel tidak ditemukan.</p>
            <?php endif; ?>
          </div>

          <!-- Sidebar -->
          <aside class="artikel-sidebar" style="flex: 1 1 30%;">
            <div class="sidebar-section">
              <h3>Recent Posts</h3>
              <div class="recent-posts-list">
                <?php
                foreach (array_slice($data, 0, 5) as $recent) {
                  if ($recent['slug'] != $slug) {
                    echo '<div class="recent-post-item" style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">';
                    echo '<a href="detail_artikel.php?slug=' . urlencode($recent['slug']) . '" style="flex-shrink: 0;">';
                    echo '<img src="' . htmlspecialchars($recent['gambar']) . '" alt="' . htmlspecialchars($recent['judul']) . '" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px;">';
                    echo '</a>';
                    echo '<div style="flex: 1;">';
                    echo '<a href="detail_artikel.php?slug=' . urlencode($recent['slug']) . '" style="font-weight: 600; text-decoration: none; color: #333; line-height: 1.3; display: block;">' . htmlspecialchars($recent['judul']) . '</a>';
                    echo '</div></div>';
                  }
                }
                ?>
              </div>
            </div>

            <div class="sidebar-section">
              <h3>Kategori</h3>
              <ul style="list-style: none; padding-left: 0;">
                <?php
                $kategori = array_unique(array_column($data, 'kategori'));
                foreach ($kategori as $kat) {
                  if (!empty($kat)) {
                    echo '<li style="margin-bottom: 8px;">';
                    echo '<a href="artikel.php?kategori=' . urlencode($kat) . '" style="text-decoration: none; color: #333; font-weight: 500;">• ' . htmlspecialchars($kat) . '</a>';
                    echo '</li>';
                  }
                }
                ?>
              </ul>
            </div>
          </aside>
        </div>

        <!-- Related Posts -->
        <?php if ($artikel): ?>
          <div class="related-posts" style="margin-top: 60px;">
            <h2 style="margin-bottom: 25px; font-size: 26px; font-weight: 700;">Related Posts</h2>
            <div
              class="related-list"
              style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;"
            >
              <?php
              $related_count = 0;
              foreach ($data as $rel) {
                if (
                  $rel['slug'] != $slug &&
                  isset($rel['kategori'], $artikel['kategori']) &&
                  $rel['kategori'] === $artikel['kategori']
                ) {
                  echo '<div class="related-item" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">';
                  echo '<a href="detail_artikel.php?slug=' . urlencode($rel['slug']) . '" style="text-decoration: none; color: #333;">';
                  echo '<img src="' . htmlspecialchars($rel['gambar']) . '" alt="' . htmlspecialchars($rel['judul']) . '" style="width: 100%; height: 160px; object-fit: cover;">';
                  echo '<div style="padding: 15px;">';
                  echo '<h4 style="font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">' . htmlspecialchars($rel['judul']) . '</h4>';
                  echo '<p style="font-size: 14px; color: #666;">' . substr(strip_tags($rel['isi']), 0, 100) . '...</p>';
                  echo '</div></a></div>';
                  $related_count++;
                  if ($related_count >= 3) break;
                }
              }
              if ($related_count === 0) {
                echo "<p>Tidak ada artikel terkait.</p>";
              }
              ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- WhatsApp Chat -->
    <script src="https://static.elfsight.com/platform/platform.js" async></script>
    <div class="elfsight-app-1c150e27-6597-4113-becd-79df393b9756" data-elfsight-app-lazy></div>

    <?php include 'footer.php'; ?>

    <script>
      feather.replace();
    </script>
  </body>
</html>
