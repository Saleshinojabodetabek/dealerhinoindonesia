<?php
// ===========================================================
// 🚫 BLOKIR LINK MALWARE - FIX HOSTINGER
// ===========================================================

$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$query       = $_SERVER['QUERY_STRING'] ?? '';
$path_info   = $_SERVER['PATH_INFO'] ?? '';

// Daftar pola malware umum yang ditemukan
$malware_patterns = [
  '#index\.php\?detail/[0-9]+#i',   // contoh: index.php?detail/1234
  '#/detail/[0-9]+#i',              // contoh: /detail/1234
  '#detail/[0-9]+#i',               // contoh: ?detail=1234
  '#\?w=[0-9]+#i',                  // contoh: ?w=768850
  '#\?[0-9]+\.shtml#i',             // contoh: ?2256707.shtml
  '#/[0-9]+\.shtml#i',              // contoh: /2256707.shtml
];

// Periksa apakah ada pola mencurigakan
foreach ($malware_patterns as $pattern) {
  if (
    preg_match($pattern, $request_uri) ||
    preg_match($pattern, $query) ||
    preg_match($pattern, $path_info)
  ) {
    // Blokir langsung
    header("HTTP/1.1 410 Gone");
    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html><head><title>410 Gone</title></head><body>";
    echo "<h1>410 - Halaman sudah dihapus</h1>";
    echo "<p>Konten ini tidak tersedia lagi di situs Dealer Hino Indonesia.</p>";
    echo "</body></html>";
    exit;
  }
}

include 'webp_loader.php'; // panggil fungsi convertImgToWebp
ob_start('convertImgToWebp'); // aktifkan output buffering
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
      content="Dealer Hino Indonesia resmi melayani penjualan truk Hino Dutro, Ranger, dan Bus. Dapatkan harga terbaru, promo menarik, simulasi kredit ringan, dan unit ready stock untuk wilayah Jakarta & Jabodetabek."
    />
    <meta
      name="keywords"
      content="dealer hino, dealer hino jakarta, dealer hino jabodetabek, dealer hino jakarta barat, dealer hino jakarta timur, dealer hino jakarta utara, dealer hino jakarta selatan, dealer hino tangerang, dealer hino bekasi, dealer hino depok, dealer hino bogor, dealer hino bandung, dealer resmi hino indonesia, sales hino, promo hino, harga truk hino, jual truk hino, kredit truk hino, cicilan truk hino, hino ready stock, stok unit hino terbaru, harga hino terbaru 2025, promo kredit hino"
    />
    <meta name="author" content="Nathan Hino" />

    <title>Dealer Hino Indonesia | Promo Hino, Kredit Ringan, Harga Truk Terbaru 2025</title>

    <!-- Favicon untuk semua browser modern -->
    <link rel="icon" type="image/png" sizes="512x512" href="/favicon_512.png">
    
    <!-- Favicon untuk browser lama -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Apple Touch Icon (iPhone/iPad) -->
    <link rel="apple-touch-icon" href="/favicon_512.png">
    

    <link rel="canonical" href="https://dealerhinoindonesia.com/" />

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17738682772">
    </script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-17738682772');
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/whatsapp.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/home/hero.css" />
    <link rel="stylesheet" href="css/home/promoutama.css" />
    <link rel="stylesheet" href="css/home/produk.css" />
    <link rel="stylesheet" href="css/home/feature.css" />
    <link rel="stylesheet" href="css/home/contact.css" />
    <link rel="stylesheet" href="css/home/blog.css" />

    <!-- JS -->
    <script src="js/script.js"></script>

    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap"
      rel="stylesheet"
    />

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Dealer Hino Indonesia | Harga Truk Hino & Promo Kredit Terbaru 2025" />
    <meta property="og:description" content="Dealer resmi Hino Indonesia melayani penjualan Hino Dutro, Ranger, dan Bus. Dapatkan harga terbaru, promo kredit ringan, DP murah, dan unit ready stock untuk Jakarta & Jabodetabek." />
    <meta property="og:image" content="https://dealerhinoindonesia.com/images/promohino1.webp" />
    <meta property="og:url" content="https://dealerhinoindonesia.com/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Dealer Hino Indonesia" />
    <meta property="og:locale" content="id_ID" />
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Dealer Hino Indonesia | Promo & Harga Truk Hino Terbaru 2025" />
    <meta name="twitter:description" content="Dealer Resmi Hino Jakarta Barat - Promo Truk Hino 2025, kredit ringan, cicilan DP murah untuk Dutro, Ranger, dan Bus Hino." />
    <meta name="twitter:image" content="https://dealerhinoindonesia.com/images/promohino1.webp" />
    <meta name="twitter:site" content="@DealerHinoIndonesia" />
    
    <!-- SEO Extra -->
    <meta name="author" content="Dealer Hino Indonesia" />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#006A31" />


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
      <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P7TN9DJW"
        height="0" width="0" style="display:none;visibility:hidden">
      </iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Header -->
    <header>
      <div class="container header-content navbar">
        <div class="header-title">
          <a href="https://dealerhinoindonesia.com">
            <img src="images/logo3.webp" alt="Logo Hino" loading="lazy" style="height: 60px" />
          </a>
        </div>
        <div class="hamburger-menu">&#9776;</div>
        <nav class="nav links">
          <a href="https://dealerhinoindonesia.com/">Home</a>
          <a href="#promo-utama">Penawaran Harga</a>
          <a href="#products-section">Produk</a>
          <a href="#features">Keunggulan Hino</a>
          <a href="https://dealerhinoindonesia.com/contact">Contact</a>
          <a href="https://dealerhinoindonesia.com/artikel">Blog & Artikel</a>
        </nav>
      </div>
    </header>

    <!-- Hero -->
    <section class="hero">
      <div class="slider">
        <img src="images/Euro 4 Hino 300.webp" class="slide active" alt="Hino 300 Series" loading="lazy"/>
        <img src="images/Euro 4 Hino 500.webp" class="slide" alt="Hino 500 Series" loading="lazy"/>
        <img src="images/Euro 4 Hino Bus.webp" class="slide" alt="Hino Bus Series" loading="lazy"/>
      </div>
      <div class="container">
        <h2>Dealer Resmi Hino Indonesia – Pilihan Tepat untuk Bisnis Anda</h2>
        <p>Kami hadir sebagai mitra terpercaya dalam menyediakan berbagai kendaraan Hino yang tangguh, mulai dari truk ringan, truk berat hingga bus. Dengan layanan profesional, stok lengkap, serta penawaran harga dan pembiayaan yang kompetitif, kami siap mendukung kebutuhan transportasi dan operasional bisnis Anda. Dapatkan solusi terbaik hanya di Dealer Resmi Hino Indonesia.</p>
        <div class="hero-buttons">
          <a href="https://wa.me/+6285975287684?text=Halo%20Saya%20Dapat%20Nomor%20Anda%20Dari%20Google" class="btn btn-contact" target="_blank">Hubungi Nathan Sekarang</a>
        </div>
      </div>
    </section>

    <main>
      <!-- About Company -->
      <section class="about-company">
        <div class="container">
          <div class="about-content">
            <div class="text">
              <h2>Dealer Hino Indonesia</h2>
              <div class="divider"></div>
              <p>
              Nathan adalah sales <strong>Dealer Hino Indonesia</strong> yang berpengalaman dan profesional, siap menjadi mitra terbaik Anda dalam memenuhi kebutuhan kendaraan niaga. Sebagai sales <strong>dealer Hino Jakarta</strong> dan area sekitarnya, Nathan menyediakan berbagai model truk dan bus Hino dengan pelayanan cepat, ramah, dan solusi pembiayaan fleksibel untuk setiap jenis usaha. Jika Anda sedang mencari <strong>dealer Hino terdekat</strong>, Nathan siap membantu dengan respons cepat dan penawaran terbaik.
              </p>
              <p>
              Komitmen Nathan sebagai sales <strong>dealer resmi Hino Indonesia</strong> adalah memberikan lebih dari sekadar penjualan. Nathan hadir untuk membangun hubungan jangka panjang melalui layanan purna jual (after-sales) yang responsif, konsultasi kebutuhan armada yang akurat, serta promo dan harga terbaik. Percayakan kebutuhan truk dan bus Hino Anda kepada <strong>Nathan – Dealer Hino Jabodetabek</strong>, dan rasakan pengalaman membeli yang aman, nyaman, serta menguntungkan.
              </p>
              <div class="contact-buttons">
                <a href="https://wa.me/6285975287684" class="btn whatsapp-btn"><i class="fab fa-whatsapp"></i> +62 859-7528-7684</a>
                <a href="mailto:saleshinojabodetabek@gmail.com" class="btn email-btn"><i class="fas fa-envelope"></i> saleshinojabodetabek@gmail.com</a>
              </div>
            </div>
            <div class="image-gallery">
              <img src="images/promohino.webp" alt="Promo Hino" />
            </div>
            </div>
          </div>
        </section>

      <!-- Produk -->
      <section id="products-section" class="products-section fade-element">
        <h2 class="section-title">Produk Truk Hino Unggulan</h2>
        <div class="products">
          <div class="product">
            <img src="images/Euro 4 Hino 300.webp" alt="Hino 300 Dutro" loading="lazy"/>
            <h3><a href="https://dealerhinoindonesia.com/hino300.php" target="_blank" rel="noopener noreferrer">Hino 300 Series (Dutro)</a></h3>
            <p>Truk ringan dan tangguh, cocok untuk usaha kecil dan menengah.</p>
          </div>
          <div class="product">
            <img src="images/Euro 4 Hino 500.webp" alt="Hino 500 Ranger" loading="lazy"/>
            <h3><a href="https://dealerhinoindonesia.com/hino500.php" target="_blank" rel="noopener noreferrer">Hino 500 Series (Ranger)</a></h3>
            <p>Performa handal untuk pengangkutan berat dan jarak jauh.</p>
          </div>
          <div class="product">
            <img src="images/Euro 4 Hino Bus.webp" alt="Hino Bus Series" loading="lazy"/>
            <h3><a href="https://dealerhinoindonesia.com/hinobus.php" target="_blank" rel="noopener noreferrer">Hino Bus Series</a></h3>
            <p>Solusi transportasi penumpang dengan kenyamanan terbaik.</p>
          </div>
        </div>
      </section>
      
      <!-- Section: Promo Utama -->
      <section id="promo-utama" class="promo-section fade-element">
        <div class="promo-text">
          <h2>Dapatkan Harga dan Penawaran Terbaik Langsung dari Dealer Resmi Hino Indonesia</h2>
          <ul>
            <li>Ingin harga terbaik untuk semua jenis truk Hino?</li>
            <li>Bingung memilih kendaraan yang tepat untuk bisnis Anda?</li>
            <li>Butuh pelayanan cepat, ramah, dan profesional?</li>
            <li>Hubungi Nathan Hino sekarang juga dan dapatkan solusi terbaik!</li>
          </ul>
          <p>Anda berada di tempat yang tepat! Nathan Hino siap membantu Anda mendapatkan truk Hino baru dengan harga kompetitif untuk seluruh Indonesia, <strong>terutama di Jabodetabek dan Jawa Barat</strong>. Pelayanan cepat, terpercaya, dan tanpa ribet menanti Anda!</p>
          <div class="promo-buttons">
            <a href="https://wa.me/6285975287684" class="btn-primary" target="_blank" rel="noopener noreferrer">Konsultasi Pembelian</a>
          </div>
        </div>
        <img src="images/hino.webp" alt="Truk Hino Hijau" loading="lazy" class="promo-main-image"/>
      </section>
      
      <!-- Fitur -->
      <section id="features" class="features fade-element">
        <h2 class="section-title">Kenapa Pilih Hino?</h2>
        <div class="feature-list">
          <div class="feature">
            <div class="icon">🛻</div>
            <h3>Durabilitas Tinggi</h3>
            <p>Mesin dan bodi tahan lama untuk penggunaan berat sehari-hari.</p>
          </div>
          <div class="feature">
            <div class="icon">👥</div>
            <h3>Pelatihan & Konsultasi</h3>
            <p>Kami siap memberikan pelatihan dan konsultasi sesuai kebutuhan bisnis Anda.</p>
          </div>
          <div class="feature">
            <div class="icon">🔧</div>
            <h3>Servis dan Support</h3>
            <p>Jaringan servis luas dan suku cadang tersedia di seluruh Indonesia.</p>
          </div>
        </div>
      </section>

      <!-- Kontak -->
      <section id="contact" class="contact fade-element">
        <h2>Butuh Bantuan atau Info Harga?</h2>
        <p>Hubungi Nathan langsung via WhatsApp. Nathan siap membantu Anda memilih truk terbaik.</p>
        <a href="https://wa.me/6285975287684" class="whatsapp-link" target="_blank" rel="noopener noreferrer">Chat WhatsApp Sekarang</a>
      </section>

      <!-- Blog Section -->
      <section class="blog-section fade-element">
        <div class="container">
          <h2>Blog & Artikel</h2>
          <p>Dapatkan informasi terbaru seputar Truk Hino, perawatan, dan promo terbaik.</p>
          <div class="blog-grid">
            <?php
              $artikelData = json_decode(file_get_contents("https://dealerhinoindonesia.com/admin/api/get_artikel.php?page=1&perPage=3"), true);
              if (isset($artikelData['data']) && is_array($artikelData['data'])) {
                $terbaru = array_slice($artikelData['data'], 0, 3);
                foreach ($terbaru as $artikel):
            ?>
              <div class="blog-card">
                <img src="<?= htmlspecialchars($artikel['gambar']) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" loading="lazy"/>
                <div class="blog-card-content">
                  <h3>
                    <a href="detail_artikel.php?slug=<?= urlencode($artikel['slug']) ?>">
                      <?= htmlspecialchars($artikel['judul']) ?>
                    </a>
                  </h3>
                  <p><?= htmlspecialchars(substr(strip_tags($artikel['isi']), 0, 100)) ?>...</p>
                  <a href="detail_artikel.php?slug=<?= urlencode($artikel['slug']) ?>" class="read-more">Baca Selengkapnya</a>
                </div>
              </div>
            <?php endforeach; } else { echo "<p>Tidak ada artikel ditemukan.</p>"; } ?>
          </div>
        </div>
      </section>
    </main>

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

    <!-- Load Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>feather.replace()</script>
  </body>
</html>
<?php ob_end_flush(); ?>