<?php
// Ambil ID artikel dari URL
$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("https://dealerhinoindonesia.com/admin/api/get_artikel.php"), true);
$artikel = null;

// Cari artikel berdasarkan ID
if ($id && is_array($data)) {
  foreach ($data as $item) {
    if ($item['id'] == $id) {
      $artikel = $item;
      break;
    }
  }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Dealer Resmi Hino Jakarta - Jual Truk Hino Dutro, Ranger, dan Bus Hino. Dapatkan harga terbaik, promo terbaru 2025, serta layanan kredit dan cicilan untuk seluruh Indonesia. Hubungi Nathan Hino sekarang juga! 0859-7528-7684" />
    <meta name="keywords" content="Dealer Hino Indonesia, Dealer Resmi Hino, Jual Truk Hino, Harga Truk Hino Terbaru, Promo Truk Hino 2025, Hino Dutro, Hino Ranger, Hino Bus, Kredit Hino, Cicilan Hino, DP Hino, Dealer Hino Jakarta, Dealer Hino Tangerang, Dealer Hino Bekasi, Dealer Hino Bogor, Dealer Hino Bandung, Sales Hino Resmi" />
    <meta name="author" content="Nathan Hino" />
    <link rel="canonical" href="https://dealerhinoindonesia.com/" />
    <title>Blog & Artikel Resmi Dealer Hino Jakarta | Harga & Promo Truk Hino Terbaru 2025</title>

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
          <a href="https://dealerhinoindonesia.com/">Home</a>
          <a href="https://dealerhinoindonesia.com/hino300.php">Hino 300 Series</a>
          <a href="https://dealerhinoindonesia.com/hino500.php">Hino 500 Series</a>
          <a href="https://dealerhinoindonesia.com/hinobus.php">Hino Bus Series</a>
          <a href="https://dealerhinoindonesia.com/contact.php">Contact</a>
          <a href="https://dealerhinoindonesia.com/artikel.php">Blog & Artikel</a>
        </nav>
    </div>
</header>

    <!-- Konten Artikel -->
    <section class="detail-artikel">
      <div class="container">
        <div class="artikel-wrapper" style="display: flex; flex-wrap: wrap; gap: 30px;">
          <div class="artikel-main" style="flex: 1 1 65%;">
            <?php if($artikel): ?>
              <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
              <p style="color: #888; font-size: 14px; margin-bottom: 15px;">
                Diposting pada <?= date('d M Y', strtotime($artikel['tanggal'] ?? 'now')) ?>
              </p>
              <img src="<?= htmlspecialchars($artikel['gambar']) ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" class="featured-image" style="width: 100%; height: auto; margin-bottom: 20px;">
              <div class="isi-artikel">
                <?= nl2br($artikel['isi']) ?>
              </div>
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
                  if ($recent['id'] != $id) {
                    echo '<div class="recent-post-item" style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">';
                    echo '<a href="detail_artikel.php?id=' . $recent['id'] . '" style="flex-shrink: 0;">';
                    echo '<img src="' . htmlspecialchars($recent['gambar']) . '" alt="' . htmlspecialchars($recent['judul']) . '" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px;">';
                    echo '</a>';
                    echo '<div style="flex: 1;">';
                    echo '<a href="detail_artikel.php?id=' . $recent['id'] . '" style="font-weight: 600; text-decoration: none; color: #333; line-height: 1.3; display: block;">' . htmlspecialchars($recent['judul']) . '</a>';
                    echo '</div>';
                    echo '</div>';
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
          <div class="related-list" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
            <?php
            $related_count = 0;
            foreach ($data as $rel) {
              if ($rel['id'] != $id && isset($rel['kategori'], $artikel['kategori']) && $rel['kategori'] === $artikel['kategori']) {
                echo '<div class="related-item" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">';
                echo '<a href="detail_artikel.php?id=' . $rel['id'] . '" style="text-decoration: none; color: #333;">';
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

    <?php include 'footer.php'; ?>

    <script>
      feather.replace();
    </script>
  </body>
</html>
