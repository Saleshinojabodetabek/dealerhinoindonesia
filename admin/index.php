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
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }
    .sidebar {
      height: 100vh;
      background: #0d6efd;
      color: white;
      padding-top: 20px;
      position: fixed;
      width: 220px;
    }
    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      margin: 4px 0;
      transition: background 0.2s;
    }
    .sidebar a:hover, .sidebar a.active {
      background: #0b5ed7;
      border-radius: 6px;
    }
    .content {
      margin-left: 220px;
      padding: 20px;
      background: #f8f9fa;
      min-height: 100vh;
    }
    .dashboard-header {
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: white;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 25px;
    }
    .card {
      border: none;
      border-radius: 12px;
      transition: 0.2s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Sales Hino<br><small style="font-size:14px;">Admin Panel</small></h4>
    <a href="index.php" class="active">Dashboard</a>
    <a href="artikel.php">Artikel</a>
    <a href="produk.php">Produk</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="dashboard-header">
      <h2>Selamat Datang, <?php echo $_SESSION['admin']; ?> 👋</h2>
      <p>Kelola produk, artikel, dan konten website melalui dashboard ini.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 text-center">
          <h5 class="text-primary">📦 Kelola Produk</h5>
          <p>Tambah, edit, hapus produk Hino dengan mudah.</p>
          <a href="produk.php" class="btn btn-success">Lihat Produk</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm p-4 text-center">
          <h5 class="text-info">📰 Kelola Artikel</h5>
          <p>Tambah, edit, hapus artikel blog secara praktis.</p>
          <a href="artikel.php" class="btn btn-info">Lihat Artikel</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
