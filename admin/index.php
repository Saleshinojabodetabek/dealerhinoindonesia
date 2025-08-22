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
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .dashboard-header {
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .card h5 {
      font-weight: 600;
    }
    .btn-custom {
      border-radius: 10px;
      font-weight: 500;
      padding: 10px 20px;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="container mt-4">
    <!-- Header -->
    <div class="dashboard-header mb-4 text-center">
      <h1 class="fw-bold">Selamat Datang, <?php echo $_SESSION['admin']; ?> 👋</h1>
      <p class="mb-0">Kelola produk dan artikel langsung dari dashboard ini</p>
    </div>

    <!-- Cards -->
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm p-4">
          <div class="card-body text-center">
            <h5 class="card-title text-primary">📦 Kelola Produk</h5>
            <p class="card-text">Tambah, edit, hapus produk Hino dengan mudah.</p>
            <a href="produk.php" class="btn btn-success btn-custom">Lihat Produk</a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm p-4">
          <div class="card-body text-center">
            <h5 class="card-title text-info">📰 Kelola Artikel</h5>
            <p class="card-text">Tambah, edit, hapus artikel blog secara praktis.</p>
            <a href="artikel.php" class="btn btn-info btn-custom">Lihat Artikel</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
