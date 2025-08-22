<?php
include 'header.php';
include 'config.php'; // koneksi ke DB

// Ambil data produk
$query = "SELECT * FROM produk ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<main>
  <section class="products-section fade-element">
    <h2 class="section-title">Produk Truk Hino</h2>
    <div class="products">
      <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="product">
          <img src="uploads/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_produk']; ?>" loading="lazy"/>
          <h3>
            <a href="<?php echo $row['link_detail']; ?>" target="_blank">
              <?php echo $row['nama_produk']; ?>
            </a>
          </h3>
          <p><?php echo substr($row['deskripsi'], 0, 100); ?>...</p>
        </div>
      <?php endwhile; ?>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
