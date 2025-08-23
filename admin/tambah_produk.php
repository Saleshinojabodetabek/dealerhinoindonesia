<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// === Proses simpan produk ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);
    $spesifikasi = $conn->real_escape_string($_POST['spesifikasi']);

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if (!empty($gambar)) {
        $upload_dir = "../uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // buat folder kalau belum ada
        }

        $file_path = $upload_dir . basename($gambar);
        if (move_uploaded_file($tmp, $file_path)) {
            $sql = "INSERT INTO produk (series_id, nama_produk, deskripsi, spesifikasi, gambar) 
                    VALUES ('$series_id', '$nama_produk', '$deskripsi', '$spesifikasi', '$gambar')";
            
            if ($conn->query($sql)) {
                header("Location: produk.php");
                exit();
            } else {
                $error = "Gagal menyimpan produk: " . $conn->error;
            }
        } else {
            $error = "Upload gambar gagal.";
        }
    } else {
        $error = "Gambar wajib diupload.";
    }
}

// === Ambil data series untuk dropdown ===
$series_list = $conn->query("SELECT * FROM series ORDER BY nama_series ASC");
if (!$series_list) {
    die("Query error: " . $conn->error);
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
  <h2 class="mb-4">Tambah Produk</h2>

  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= $error; ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <!-- Series -->
    <div class="mb-3">
      <label for="series_id" class="form-label">Pilih Series</label>
      <select id="series_id" name="series_id" class="form-select" required>
        <option value="" disabled selected>-- Pilih Series --</option>
        <?php while ($s = $series_list->fetch_assoc()) : ?>
          <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['nama_series']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Nama Produk -->
    <div class="mb-3">
      <label for="nama_produk" class="form-label">Nama Produk</label>
      <input type="text" id="nama_produk" name="nama_produk" class="form-control" required>
    </div>

    <!-- Deskripsi -->
    <div class="mb-3">
      <label for="deskripsi" class="form-label">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
    </div>

    <!-- Spesifikasi -->
    <div class="mb-3">
      <label for="spesifikasi" class="form-label">Spesifikasi</label>
      <textarea id="spesifikasi" name="spesifikasi" class="form-control" rows="4"></textarea>
    </div>

    <!-- Gambar -->
    <div class="mb-3">
      <label for="gambar" class="form-label">Gambar Produk</label>
      <input type="file" id="gambar" name="gambar" class="form-control" required>
    </div>

    <!-- Tombol -->
    <div class="d-flex justify-content-between">
      <a href="produk.php" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-success">Simpan Produk</button>
    </div>
  </form>
</div>
</body>
</html>
