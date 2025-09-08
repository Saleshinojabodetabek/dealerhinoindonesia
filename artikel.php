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

if (is_array($artikelData)) {
    $totalArtikel = count($artikelData);
    $totalPages = ceil($totalArtikel / $perPage);
    $offset = ($page - 1) * $perPage;
    $artikel = array_slice($artikelData, $offset, $perPage);
} else {
    $totalArtikel = 0;
    $totalPages = 0;
    $artikel = [];
}

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
  <title>Blog & Artikel Resmi Dealer Hino Jakarta</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/blog/artikel.css" />
  <link rel="stylesheet" href="css/blog/hero.css" />
  <style>
    .blog-filter { display:flex; gap:10px; margin-bottom:20px; }
    .blog-filter input, .blog-filter select { padding:6px 10px; border:1px solid #ddd; border-radius:5px; }
    .blog-filter input { width:200px; }
    .blog-filter button { padding:6px 15px; background:#006400; color:#fff; border:none; border-radius:5px; }
    .pagination { text-align:center; margin-top:20px; }
    .pagination a { padding:6px 12px; margin:0 3px; border:1px solid #ddd; border-radius:4px; text-decoration:none; color:#333; }
    .pagination a.active { background:#006400; color:#fff; border-color:#006400; }
  </style>
</head>
<body>

<header>
  <div class="container header-content navbar">
    <div class="header-title">
      <a href="https://dealerhinoindonesia.com">
        <img src="images/logo3.png" alt="Logo Hino Indonesia" style="height:60px"/>
      </a>
    </div>
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

<section class="content-section" id="artikel">
  <div class="container">

    <!-- Filter -->
    <form method="get" class="blog-filter">
      <input type="text" name="search" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>" />
      <select name="kategori" onchange="this.form.submit()">
        <option value="">Semua</option>
        <?php if (is_array($kategoriData)): ?>
          <?php foreach ($kategoriData as $kat): ?>
            <option value="<?= htmlspecialchars($kat['nama']) ?>" <?= $selectedKategori === $kat['nama'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($kat['nama']) ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
      <button type="submit">Cari</button>
    </form>

    <!-- Artikel -->
    <div class="blog-grid">
      <?php if (count($artikel) > 0): ?>
        <?php foreach ($artikel as $row): ?>
          <div class="blog-post">
            <img src="<?= !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'images/no-image.jpg' ?>" 
                 alt="<?= htmlspecialchars($row['judul']) ?>" loading="lazy">
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
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a class="<?= $i === $page ? 'active' : '' ?>" href="<?= $baseUrl ?>page=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
