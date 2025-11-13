<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "config.php"; // file koneksi database

$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesan Customer</title>
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
      text-align: center;
    }
    .sidebar img {
      max-width: 160px;
      margin-bottom: 20px;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      margin: 4px 0;
      transition: background 0.2s;
      text-align: left;
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
      background: linear-gradient(90deg, #0d6efd, #0b5ed7);
      color: white;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-4">
      <img src="../images/logo3.webp" alt="Logo Hino">
    </div>
    <a href="index.php">Dashboard</a>
    <a href="artikel.php">Artikel</a>
    <a href="produk.php">Produk</a>
    <a href="pesan.php" class="active">Pesan Customer</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="dashboard-header">
      <h2>📩 Pesan Customer</h2>
      <p>Lihat semua pesan yang dikirim customer melalui form kontak website.</p>
    </div>

    <table class="table table-bordered table-striped mt-3">
      <thead class="table-primary">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Telepon</th>
          <th>Pesan</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        while($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['phone']); ?></td>
            <td><?= htmlspecialchars($row['message']); ?></td>
            <td><?= $row['created_at']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
