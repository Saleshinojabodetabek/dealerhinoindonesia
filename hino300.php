<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hino 300 Series | Truk Ringan Tangguh</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/produk/headerproduk.css">
  <link rel="stylesheet" href="css/produk/produk.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>

  <?php include 'header.php'; ?>

  <main>
    <!-- Hero -->
    <section class="about-hero" style="background:url('images/Euro 4 Hino 300.jpeg') center/cover no-repeat;"></section>

    <!-- Gallery Produk -->
    <div class="gallery-wrapper">
      <div class="gallery">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM produk WHERE kategori='300' ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "
              <div class='gallery-item'>
                <a href='{$row['link_wa']}'>
                  <img src='uploads/{$row['gambar']}' alt='{$row['nama']}'>
                  <p>{$row['nama']}</p>
                </a>
              </div>
            ";
        }
        ?>
      </div>
    </div>

    <!-- CTA -->
    <div class="cta-full">
      <h2>Tidak menemukan apa yang kamu cari?</h2>
      <a href='https://wa.me/+6285975287684?text=Halo%20Saya%20Ingin%20Menanyakan%20Tentang%20Produk' class='cta-full-button'>Hubungi Kami</a>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script>feather.replace();</script>
</body>
</html>
