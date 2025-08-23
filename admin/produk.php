<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">Kelola Produk</h2>
  <a href="tambah_produk.php" class="btn btn-success mb-3">+ Tambah Produk</a>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary text-center">
      <tr>
        <th>ID</th>
        <th>Series</th>
        <th>Nama Produk</th>
        <th>Gambar</th>
        <th>Spesifikasi</th>
        <th style="width:260px">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // pakai LEFT JOIN biar produk tetap tampil walau spesifikasinya null
      $sql = "SELECT p.id, p.nama_produk, p.gambar, s.nama_series, ps.detail
              FROM produk p
              LEFT JOIN series s ON p.series_id = s.id
              LEFT JOIN produk_spesifikasi ps ON p.id = ps.produk_id
              ORDER BY p.id DESC";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $id          = (int)$row['id'];
              $namaSeries  = $row['nama_series'] ?? '-';
              $namaProduk  = htmlspecialchars($row['nama_produk'] ?? '-', ENT_QUOTES, 'UTF-8');
              $gambarFile  = $row['gambar'] ?? '';
              $imgPath     = "../uploads/" . $gambarFile;
              $spesifikasi = $row['detail'] ?? '';

              echo "<tr>";
              echo "<td class='text-center'>{$id}</td>";
              echo "<td>" . htmlspecialchars($namaSeries, ENT_QUOTES, 'UTF-8') . "</td>";
              echo "<td>{$namaProduk}</td>";

              // tampilkan gambar
              echo "<td class='text-center'>";
              if (!empty($gambarFile) && file_exists($imgPath)) {
                  echo "<img src='{$imgPath}' alt='Gambar' width='100' class='img-thumbnail'>";
              } else {
                  echo "<span class='text-muted'>Tidak ada gambar</span>";
              }
              echo "</td>";

              // tampilkan spesifikasi
              echo "<td>";
              if (!empty($spesifikasi)) {
                  echo nl2br(htmlspecialchars($spesifikasi, ENT_QUOTES, 'UTF-8'));
              } else {
                  echo "<span class='text-muted'>Belum ada spesifikasi</span>";
              }
              echo "</td>";

              // tombol aksi
              echo "<td class='text-center'>
                      <a href='edit_produk.php?id={$id}' class='btn btn-warning btn-sm me-1'>Edit</a>
                      <a href='hapus_produk.php?id={$id}' class='btn btn-danger btn-sm me-1' onclick=\"return confirm('Yakin hapus produk ini?');\">Hapus</a>
                      <a href='detail_produk.php?id={$id}' class='btn btn-info btn-sm'>Detail</a>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='6' class='text-center'>Belum ada produk</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
