<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];
$produk = $conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id = $_POST['series_id'];
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $spesifikasi = $_POST['spesifikasi'];

    // update gambar jika diupload
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/" . $gambar);
    } else {
        $gambar = $produk['gambar'];
    }

    $sql = "UPDATE produk SET 
            series_id='$series_id', 
            nama_produk='$nama_produk', 
            deskripsi='$deskripsi', 
            spesifikasi='$spesifikasi', 
            gambar='$gambar' 
            WHERE id=$id";

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
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Edit Produk</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Series</label>
      <select name="series_id" class="form-control" required>
        <?php
        $series = $conn->query("SELECT * FROM series");
        while ($s = $series->fetch_assoc()) {
            $selected = ($s['id'] == $produk['series_id']) ? "selected" : "";
            echo "<option value='{$s['id']}' $selected>{$s['nama_series']}</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" value="<?= $produk['nama_produk'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control"><?= $produk['deskripsi'] ?></textarea>
    </div>
    <div class="mb-3">
      <label>Spesifikasi</label>
      <textarea name="spesifikasi" class="form-control"><?= $produk['spesifikasi'] ?></textarea>
    </div>
    <div class="mb-3">
      <label>Gambar (biarkan kosong jika tidak ganti)</label>
      <input type="file" name="gambar" class="form-control">
      <p><img src="../uploads/<?= $produk['gambar'] ?>" width="120"></p>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="produk.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
