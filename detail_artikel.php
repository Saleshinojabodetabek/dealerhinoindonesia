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
      content="harga truk hino terbaru, tips memilih truk hino, perbandingan hino dutro dan ranger, review truk hino, update harga hino 2025, berita hino terbaru, artikel hino indonesia, promo hino terbaru, panduan kredit truk hino, cara memilih truk bisnis"
    />
    <meta name="author" content="Nathan Hino" />
    <link
      rel="canonical"
      href="https://dealerhinoindonesia.com/detail_artikel.php?slug=<?= urlencode($artikel['slug'] ?? '') ?>"
    />
    <title><?= htmlspecialchars($artikel['judul'] ?? 'Artikel Tidak Ditemukan') ?> | Dealer Hino Indonesia</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17738682772">
    </script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-17738682772');
    </script>

    <!-- Favicon untuk semua browser modern -->
    <link rel="icon" type="image/png" sizes="512x512" href="/favicon_512.png">
    
    <!-- Favicon untuk browser lama -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Apple Touch Icon (iPhone/iPad) -->
    <link rel="apple-touch-icon" href="/favicon_512.png">
    
    <!-- Google Lighthouse Recommendation -->
    <meta name="theme-color" content="#ffffff">


    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/whatsapp.css" />
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
    <meta property="og:title" content="<?= htmlspecialchars($artikel['judul']) ?> | Artikel Hino" />
    <meta property="og:description" content="<?= htmlspecialchars(mb_strimwidth(strip_tags($artikel['isi']), 0, 150, '...')) ?>" />
    <meta property="og:image" content="https://dealerhinoindonesia.com/images/artikel/<?= $artikel['gambar'] ?>" />
    <meta property="og:url" content="https://dealerhinoindonesia.com/artikel/<?= $artikel['slug'] ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:site_name" content="Dealer Hino Indonesia" />


    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025" />
    <meta
      name="twitter:description"
      content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino dengan harga terbaik dan promo terbaru 2025."
    />
    <meta name="twitter:image" content="https://dealerhinoindonesia.com/images/promohino1.webp" />

    <!-- Schema.org JSON-LD untuk SEO Dealer Hino -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Dealer Hino Indonesia",
      "url": "https://dealerhinoindonesia.com"
    }
    </script>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "AutoDealer",
      "@id": "https://dealerhinoindonesia.com/#dealer",
      "name": "Dealer Hino Indonesia",
      "alternateName": "Dealer Resmi Hino Jakarta",
      "url": "https://dealerhinoindonesia.com/",
      "image": "https://dealerhinoindonesia.com/images/promohino1.webp",
      "logo": "https://dealerhinoindonesia.com/favicon_512.png",
      "description": "Dealer Resmi Hino Jakarta Barat - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan untuk seluruh Indonesia, khususnya Jabodetabek dan Jawa Barat. Hubungi Nathan Hino sekarang juga!.",
      "telephone": "+62-859-7528-7684",
      "priceRange": "$$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Tj. Pura.9-10, RT.2/RW.2, Pegadungan, Kec. Kalideres",
        "addressLocality": "Jakarta Barat",
        "addressRegion": "DKI Jakarta",
        "postalCode": "11830",
        "addressCountry": "ID"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": -6.1567,
        "longitude": 106.6901
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
    
    <!-- Event snippet for Pembelian conversion page -->
    <script>
    gtag('event', 'conversion', {
        'send_to': 'AW-17738682772/7zEXCMGP3sIbEJSju4pC',
        'transaction_id': ''
    });
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

  <!-- WhatsApp Floating Widget -->
  <div id="wa-widget-container">

    <!-- Floating Button -->
    <div id="wa-floating-btn">
      <img src="https://dealerhinoindonesia.com/images/wa.png" alt="wa" />
      <span>WhatsApp</span>
    </div>

    <!-- Chat Box -->
    <div id="wa-chatbox">
      <div class="wa-header">
        <img src="https://dealerhinoindonesia.com/images/NT.jpeg" class="wa-avatar" />
        <div>
          <h4>Nathan Hino</h4>
          <p>Online <span class="wa-dot"></span></p>
        </div>
        <div class="wa-close" onclick="toggleWA()">✕</div>
      </div>

      <div class="wa-body">
        <div class="wa-message">
          <p>Halo 👋</p>
          <p>Saya siap membantu untuk info produk Hino.<br>
          Silakan tanya apa saja 😊</p>
        </div>
      </div>

      <a
        href="https://wa.me/6285975287684?text=Halo%20kak%20Nathan.%20Saya%20mau%20bertanya%20tentang%20produk%20Hino."
        class="wa-button"
        target="_blank"
      >
        Chat on WhatsApp
      </a>
    </div>
  </div>

  <script>
    const waBox = document.getElementById("wa-chatbox");
    const waBtn = document.getElementById("wa-floating-btn");

    waBtn.onclick = toggleWA;

    function toggleWA() {
      waBox.classList.toggle("show");
    }
  </script>


      <script>
        // Toggle open/close
        document.getElementById("wa-button").onclick = function () {
          document.getElementById("wa-box").classList.toggle("show");
        };
      </script>

    <?php include 'footer.php'; ?>

    <script>
      feather.replace();
    </script>
  </body>
</html>
