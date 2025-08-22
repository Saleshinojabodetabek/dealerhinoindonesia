<?php
// koneksi ke database (jika nanti backend CRUD produk sudah ada)
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dealer Hino Indonesia | Produk</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/produk/produk.css" /> <!-- CSS khusus produk -->
</head>
<body>

  <!-- Header khusus produk -->
  <header>
    <div class="produk-header">
      <div class="produk-header-content">
        <img src="images/logo3.png" alt="Logo Hino">
        <h1>Produk Truk & Bus Hino</h1>
        <p>Pilihan lengkap Hino Series: 300, 500, Bus, dan Profia</p>
      </div>
    </div>

    <!-- Navbar Produk -->
    <nav class="produk-nav">
      <a href="index.php">Home</a>
      <a href="produk.php?kategori=300">Hino 300 Series</a>
      <a href="produk.php?kategori=500">Hino 500 Series</a>
      <a href="produk.php?kategori=bus">Hino Bus</a>
      <a href="produk.php?kategori=profia">Hino Profia</a>
      <a href="artikel.php">Blog</a>
      <a href="index.php#contact">Contact</a>
    </nav>
  </header>

  <!-- Konten Produk -->
  <main class="produk-container">
    <h2>Daftar Produk</h2>

    <div class="produk-grid">
      <?php
      // contoh query ambil produk (nanti kita buat tabel `produk` di database)
      $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
      while($row = mysqli_fetch_assoc($query)) {
      ?>
        <div class="produk-card">
          <img src="uploads/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>">
          <h3><?php echo $row['nama']; ?></h3>
          <p><?php echo substr($row['deskripsi'], 0, 100); ?>...</p>
          <a href="produk-detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Lihat Detail</a>
        </div>
      <?php } ?>
    </div>
  </main>

  <!-- Footer dipisah -->
  <?php include 'footer.php'; ?>

</body>
</html>
