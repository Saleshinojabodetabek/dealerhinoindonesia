<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
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
  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Series</th>
        <th>Nama Produk</th>
        <th>Gambar</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // coba tampilkan semua produk tanpa join dulu
      $sql = "SELECT * FROM produk ORDER BY id DESC";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              // debug untuk cek kolom apa saja yg ada
              echo "<pre style='background:#f8f9fa;border:1px solid #ccc;padding:5px;'>";
              var_dump($row);
              echo "</pre>";

              $gambar = !empty($row['gambar']) ? "<img src='../uploads/{$row['gambar']}' width='100'>" : "-";

              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>".(isset($row['series_id']) ? $row['series_id'] : "-")."</td>
                      <td>{$row['nama_produk']}</td>
                      <td>{$gambar}</td>
                      <td>
                        <a href='edit_produk.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='hapus_produk.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus produk ini?');\">Hapus</a>
                        <a href='detail_produk.php?id={$row['id']}' class='btn btn-info btn-sm'>Detail</a>
                      </td>
                    </tr>";
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
