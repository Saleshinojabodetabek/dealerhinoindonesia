<?php
// Ambil data kategori dari API
$kategoriData = json_decode(file_get_contents("https://dealerhinoindonesia.com/admin/api/get_kategoriartikel.php"), true);

// Ambil parameter filter
$search = $_GET['search'] ?? '';
$selectedKategori = $_GET['kategori'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;

// Bangun URL API artikel
$apiUrl = "https://dealerhinoindonesia.com/admin/api/get_artikel.php";
$params = [];

if ($search !== '') {
    $params[] = "search=" . urlencode($search);
}
if ($selectedKategori !== '') {
    $params[] = "kategori=" . urlencode($selectedKategori);
}
if (!empty($params)) {
    $apiUrl .= '?' . implode('&', $params);
}

// Ambil data artikel
$artikelData = json_decode(file_get_contents($apiUrl), true);
$totalArtikel = is_array($artikelData) ? count($artikelData) : 0;
$totalPages = ceil($totalArtikel / $perPage);
$offset = ($page - 1) * $perPage;
$artikel = array_slice($artikelData, $offset, $perPage);

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
    <meta name="keywords" content="Dealer Hino Indonesia, Dealer Resmi Hino, Jual Truk Hino, Harga Truk Hino Terbaru, Promo Truk Hino 2025, Hino Dutro, Hino Ranger, Hino Bus, Kredit Hino, Cicilan Hino, DP Hino, Dealer Hino Jakarta, Dealer Hino Tangerang, Dealer Hino Bekasi, Dealer Hino Bogor, Dealer Hino Bandung, Sales Hino Resmi" />
    <meta name="author" content="Nathan Hino" />
    <link rel="canonical" href="https://dealerhinoindonesia.com/" />
    <title>Dealer Resmi Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025</title>

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
            <a href="index.php">Home</a>
            <a href="#promo-utama">Penawaran Harga</a>
            <a href="#products-section">Produk</a>
            <a href="#features">Keunggulan Hino</a>
            <a href="contact.php">Contact</a>
            <a href="artikel.php" class="active">Blog & Artikel</a>
        </nav>
    </div>
</header>

<!-- Hero Banner -->
<section class="hero-banner">
    <div class="overlay"></div>
    <div class="hero-content">
        <h1>Blog & Artikel Hino Indonesia</h1>
        <p>Dapatkan berita terbaru, tips, promo, dan informasi seputar truk Hino untuk mendukung bisnis Anda.</p>
        <a href="#artikel" class="hero-btn">Jelajahi Artikel</a>
    </div>
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
                    <div class="blog-post">
                        <img src="<?= htmlspecialchars($row['gambar']) ?>" 
                             alt="Artikel Hino - <?= htmlspecialchars($row['judul']) ?>" 
                             loading="lazy">
                        <h2>
                            <a href="detail_artikel.php?id=<?= urlencode($row['id']) ?>">
                                <?= htmlspecialchars($row['judul']) ?>
                            </a>
                        </h2>
                        <p><?= substr(strip_tags($row['isi']), 0, 120) ?>...</p>
                        <div class="card-footer">
                            <a href="detail_artikel.php?id=<?= urlencode($row['id']) ?>">Baca Selengkapnya</a>
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

<?php include 'footer.php'; ?>

<script>feather.replace();</script>
</body>
</html>
