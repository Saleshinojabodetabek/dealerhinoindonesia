<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id = $_POST['series_id'];
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $spesifikasi = $_POST['spesifikasi'];

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/" . $gambar);

    $sql = "INSERT INTO produk (series_id, nama_produk, deskripsi, spesifikasi, gambar) 
            VALUES ('$series_id', '$nama_produk', '$deskripsi', '$spesifikasi', '$gambar')";
    if ($conn->query($sql)) {
        header("Location: produk.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Tambah Produk</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Series</label>
      <select name="series_id" class="form-control" required>
        <?php
        $series = $conn->query("SELECT * FROM series");
        while ($s = $series->fetch_assoc()) {
            echo "<option value='{$s['id']}'>{$s['nama_series']}</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label>Spesifikasi</label>
      <textarea name="spesifikasi" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label>Gambar</label>
      <input type="file" name="gambar" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="produk.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
