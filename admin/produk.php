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
      $sql = "SELECT * FROM produk ORDER BY id DESC";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>{$row['id']}</td>";

              // cek apakah kolom series_id ada
              echo "<td>".(isset($row['series_id']) ? $row['series_id'] : "<span class='text-danger'>[series_id tidak ada]</span>")."</td>";

              // cek apakah kolom nama_produk ada
              echo "<td>".(isset($row['nama_produk']) ? $row['nama_produk'] : "<span class='text-danger'>[nama_produk tidak ada]</span>")."</td>";

              // cek apakah kolom gambar ada
              if (isset($row['gambar']) && !empty($row['gambar'])) {
                  $path = "../uploads/{$row['gambar']}";
                  if (file_exists($path)) {
                      echo "<td><img src='$path' width='100'></td>";
                  } else {
                      echo "<td class='text-danger'>File tidak ditemukan: $path</td>";
                  }
              } else {
                  echo "<td class='text-danger'>[gambar kosong]</td>";
              }

              echo "<td>
                      <a href='edit_produk.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                      <a href='hapus_produk.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus produk ini?');\">Hapus</a>
                      <a href='detail_produk.php?id={$row['id']}' class='btn btn-info btn-sm'>Detail</a>
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