<?php include 'config.php'; ?>
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
