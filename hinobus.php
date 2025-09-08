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
    <title>Hino Bus Series | Harga & Promo Bus Hino Terbaru 2025</title>
    <link rel="icon" type="image/png" href="images/favicon.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="images/favicon.png" />
    <link rel="canonical" href="https://dealerhinoindonesia.com/hinobus.php" />

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
      <img src="images/Euro 4 Hino Bus.jpeg" alt="Hino Bus Series" class="hero-product-img" />
    </section>

    <!-- Produk Pilihan -->
    <div id="kategori-section" class="kategori-section">
      <div class="kategori">
        <h1>Hino Bus Series</h1>
        <img src="images/euro4.png" alt="Euro4 Logo">
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
                <img src="uploads/produk/${p.gambar}" alt="${p.nama_produk}">
                <h3>${p.nama_produk}</h3>
                <a href="product-detail-hinobus.php?id=${p.id}#hero-section" class="btn-detail">Lihat Detail</a>
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
      if(window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if(target){
          const yOffset = -80; // sesuaikan tinggi header
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
          if(target){
            const yOffset = -105; // sesuaikan tinggi header
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
  </body>
</html>

<?php include 'footer.php'; ?>