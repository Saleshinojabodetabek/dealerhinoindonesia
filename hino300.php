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

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">


    <!-- JS -->
    <script src="js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
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
          <a href="#promo-utama">Penawaran Harga</a>
          <a href="#products-section">Produk</a>
          <a href="#features">Keunggulan Hino</a>
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

  <div class="tabs">
    <div class="tab active">ALL</div>
    <div class="tab">CARGO</div>
    <div class="tab">DUMP</div>
    <div class="tab">MIXER</div>
  </div>
</div>

<!-- Product -->
<div id="produk-list" class="produk-grid"></div>

<script>
// Fungsi ambil produk dari API
function loadProduk(varian = 'ALL') {
  fetch("admin/api/get_product.php?varian=" + varian)
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
              <p>${p.deskripsi.substring(0,100)}...</p>
              <a href="produk-detail.php?id=${p.id}" class="btn-detail">Lihat Detail</a>
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

// Load semua produk pertama kali
loadProduk();

// Event listener untuk tab kategori
document.querySelectorAll(".tabs .tab").forEach(tab => {
  tab.addEventListener("click", function() {
    document.querySelectorAll(".tabs .tab").forEach(t => t.classList.remove("active"));
    this.classList.add("active");
    loadProduk(this.textContent.trim()); // isi textContent: ALL, CARGO, DUMP, MIXER
  });
});
</script>
