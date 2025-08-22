<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container mt-4">
    <h1>Selamat Datang, <?php echo $_SESSION['admin']; ?> 👋</h1>
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Kelola Produk</h5>
            <p class="card-text">Tambah, edit, hapus produk Hino.</p>
            <a href="produk.php" class="btn btn-success">Lihat Produk</a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Kelola Artikel</h5>
            <p class="card-text">Tambah, edit, hapus artikel blog.</p>
            <a href="artikel.php" class="btn btn-info">Lihat Artikel</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
