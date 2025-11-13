<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'config.php'; // <- pastikan ini benar
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f9fa;
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
      max-width: 180px;
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
    }
    .dashboard-header {
      background: linear-gradient(90deg, #0d6efd, #0b5ed7);
      color: white;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 25px;
    }
    .btn-primary {
      background: #0d6efd;
      border: none;
    }
    .btn-primary:hover {
      background: #0b5ed7;
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
    <a href="produk.php" class="active">Produk</a>
    <a href="pesan.php">Pesan Customer</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <!-- Header -->
    <div class="dashboard-header">
      <h2>📦 Kelola Produk</h2>
      <p>Tambah, edit, hapus, dan kelola semua produk Hino melalui halaman ini.</p>
    </div>

    <!-- Tombol tambah produk -->
    <div class="d-flex justify-content-end mb-3">
      <a href="tambah_produk.php" class="btn btn-success">+ Tambah Produk</a>
    </div>

    <!-- Tabel produk -->
    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Series</th>
          <th>Nama Produk</th>
          <th>Gambar</th>
          <th style="width:260px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT p.id, p.nama_produk, p.gambar, s.nama_series
                FROM produk p
                LEFT JOIN series s ON p.series_id = s.id
                ORDER BY p.id DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id          = (int)$row['id'];
                $namaSeries  = $row['nama_series'] ?? '-';
                $namaProduk  = htmlspecialchars($row['nama_produk'] ?? '-', ENT_QUOTES, 'UTF-8');
                $gambarFile  = $row['gambar'] ?? '';
                $imgPath     = "/uploads/produk/" . $gambarFile;

                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>" . htmlspecialchars($namaSeries, ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>{$namaProduk}</td>";
                echo "<td>";
                if (!empty($gambarFile) && file_exists($imgPath)) {
                    echo "<img src='{$imgPath}' alt='Gambar' width='100'>";
                } else {
                    echo "<span class='text-muted'>Tidak ada gambar</span>";
                }
                echo "</td>";
                echo "<td>
                        <a href='edit_produk.php?id={$id}' class='btn btn-warning btn-sm me-1'>Edit</a>
                        <a href='hapus_produk.php?id={$id}' class='btn btn-danger btn-sm me-1' onclick=\"return confirm('Yakin hapus produk ini?');\">Hapus</a>
                        <a href='detail_produk.php?id={$id}' class='btn btn-info btn-sm'>Detail</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Belum ada produk</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
