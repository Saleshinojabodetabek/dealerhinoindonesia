<?php include 'header.php'; ?>
<main>
  <section class="about-hero"
    style="background-image: url('img/Euro 4 Hino 300.jpeg'); background-size:cover; background-position:center;">
  </section>

  <div class="gallery-wrapper">
    <div class="gallery">
      <?php
      include 'config.php';
      $result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
      while ($row = mysqli_fetch_assoc($result)) {
          echo "
          <div class='gallery-item'>
            <a href='{$row['link_wa']}'>
              <img src='uploads/{$row['gambar']}' alt='{$row['nama']}'>
              <p>{$row['nama']}</p>
            </a>
          </div>";
      }
      ?>
    </div>
  </div>

  <div class="cta-full">
    <h2>Tidak menemukan apa yang kamu cari?</h2>
    <a href='https://wa.me/+6285975287684?text=Halo%20Saya%20Ingin%20Menanyakan%20Tentang%20Produk' 
       class='cta-full-button'>Hubungi Kami</a>
  </div>
</main>
<?php include 'footer.php'; ?>
