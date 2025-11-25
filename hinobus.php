<?php
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
      content="Dealer Resmi Hino Indonesia – Jual Bus Hino berbagai tipe untuk kebutuhan pariwisata, AKAP, AKDP, dan transportasi perusahaan. Dapatkan harga terbaik, promo 2025, serta layanan kredit dan cicilan. Hubungi Nathan Hino sekarang! 0859-7528-7684"
    />
    <meta
      name="keywords"
      content="harga hino bus terbaru, hino bus series, spesifikasi hino bus, hino bus euro 4, harga chassis hino bus, brosur hino bus, hino microbus, hino bus pariwisata"
    />
    <meta name="author" content="Nathan Hino" />
    <title>Hino Bus Series | Harga & Promo Bus Hino Terbaru 2025</title>

    <!-- Favicon untuk semua browser modern -->
    <link rel="icon" type="image/png" sizes="512x512" href="/favicon_512.png">
    
    <!-- Favicon untuk browser lama -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Apple Touch Icon (iPhone/iPad) -->
    <link rel="apple-touch-icon" href="/favicon_512.png">
    
    <!-- Google Lighthouse Recommendation -->
    <meta name="theme-color" content="#ffffff">

    <link rel="canonical" href="https://dealerhinoindonesia.com/hinobus.php" />

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
    <link rel="stylesheet" href="css/product/hero.css" />
    <link rel="stylesheet" href="css/product/kategori.css" />
    <link rel="stylesheet" href="css/product/product.css" />

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap"
      rel="stylesheet"
    />

    <!-- JS -->
    <script src="js/script.js"></script>

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
    <meta property="og:title" content="Hino Bus Series | Harga, Tipe & Spesifikasi Terbaru" />
    <meta property="og:description" content="Informasi lengkap Bus Hino — Cocok untuk pariwisata, AKAP, dan kebutuhan transportasi perusahaan." />
    <meta property="og:image" content="https://dealerhinoindonesia.com/images/hinobus.jpg" />
    <meta property="og:url" content="https://dealerhinoindonesia.com/hinobus.php" />
    <meta property="og:type" content="product.group" />
    <meta property="og:site_name" content="Dealer Hino Indonesia" />


    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025" />
    <meta name="twitter:description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino dengan harga terbaik dan promo terbaru 2025." />
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
            <img src="images/logo3.webp" alt="Logo Hino" loading="lazy" style="height: 60px" />
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

    <!-- Hero Product -->
    <section class="hero-product">
      <img src="images/Euro 4 Hino Bus.webp" alt="Hino Bus Series" class="hero-product-img" />
    </section>

    <!-- Produk Pilihan -->
    <div id="kategori-section" class="kategori-section">
      <div class="kategori">
        <h1>Hino Bus Series</h1>
        <img src="images/euro4.webp" alt="Euro4 Logo" />
      </div>

      <div class="produk-controls">
        <div class="tabs">
          <div class="tab active">ALL</div>
          <div class="tab">BUS MIKRO</div>
          <div class="tab">BUS MEDIUM</div>
          <div class="tab">BUS BESAR</div>
        </div>

        <!-- Search Bar -->
        <input type="text" id="search-input" placeholder="Cari produk..." />
      </div>
    </div>

    <!-- Product -->
    <div id="produk-list" class="produk-grid"></div>

    <script>
      let currentVarian = 'ALL';
      let currentSearch = '';
      let seriesId = 6;

      // Fungsi load produk
      function loadProduk() {
        fetch(`admin/api/get_product.php?series_id=${seriesId}&varian=${currentVarian}&search=${encodeURIComponent(currentSearch)}`)
          .then(res => res.json())
          .then(data => {
            let html = "";
            if (data.length === 0) {
              html = "<p>Tidak ada produk untuk kategori ini.</p>";
            } else {
              data.forEach(p => {
                html += `
                  <div class="produk-card">
                    <img src="admin/uploads/produk/${p.gambar}" alt="${p.nama_produk}">
                    <h3>${p.nama_produk}</h3>
                    <a href="product-detail-hinobus.php?slug=${p.slug}#hero-section" class="btn-detail">Lihat Detail</a>
                  </div>
                `;
              });
            }
            document.getElementById("produk-list").innerHTML = html;
          })
          .catch(err => {
            document.getElementById("produk-list").innerHTML =
              "<p style='color:red'>Gagal load produk.</p>";
            console.error("Error load produk:", err);
          });
      }

      // Event search
      document.getElementById("search-input").addEventListener("input", function() {
        currentSearch = this.value.trim();
        loadProduk();
      });

      // Event click tab kategori
      document.addEventListener("DOMContentLoaded", () => {
        // Scroll smooth jika halaman dibuka dengan hash
        if (window.location.hash) {
          const target = document.querySelector(window.location.hash);
          if (target) {
            const yOffset = -80;
            const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: "smooth" });
          }
        }

        // Event click tab
        document.querySelectorAll(".tabs .tab").forEach(tab => {
          tab.addEventListener("click", (e) => {
            e.preventDefault();

            // Scroll ke kategori-section dengan offset
            const target = document.getElementById("kategori-section");
            if (target) {
              const yOffset = -105;
              const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
              window.scrollTo({ top: y, behavior: "smooth" });
            }

            // Set tab aktif
            document.querySelectorAll(".tabs .tab").forEach(t => t.classList.remove("active"));
            tab.classList.add("active");

            // Set varian sesuai tab dan reload produk
            currentVarian = tab.textContent.trim();
            loadProduk();
          });
        });

        // Load produk pertama kali
        loadProduk();
      });
    </script>

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
  </body>
</html>

<?php ob_end_flush(); ?>
