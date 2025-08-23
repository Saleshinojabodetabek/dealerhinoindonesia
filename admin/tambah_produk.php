<?php
// Aktifkan error reporting (untuk debug, bisa dimatikan di production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Koneksi database (karena file ini ada di folder admin, maka naik 1 level)
include '../koneksi.php';

// Proses simpan produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);
    $spesifikasi = $conn->real_escape_string($_POST['spesifikasi']);

    // Upload gambar
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . "_" . basename($_FILES['gambar']['name']); 
        $tmp    = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/" . $gambar);
    }

    // Simpan ke database
    $sql = "INSERT INTO produk (series_id, nama_produk, deskripsi, spesifikasi, gambar) 
            VALUES ('$series_id', '$nama_produk', '$deskripsi', '$spesifikasi', '$gambar')";
    
    if ($conn->query($sql)) {
        header("Location: produk.php?success=1");
        exit();
    } else {
        $error = "Gagal menyimpan produk: " . $conn->error;
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
  <div class="card shadow-lg">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Tambah Produk Baru</h4>
    </div>
    <div class="card-body">
      <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Series</label>
          <select name="series_id" class="form-select" required>
            <option value="">-- Pilih Series --</option>
            <?php
            $series = $conn->query("SELECT * FROM series");
            while ($s = $series->fetch_assoc()) {
                echo "<option value='{$s['id']}'>{$s['nama_series']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Spesifikasi</label>
          <textarea name="spesifikasi" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Gambar Produk</label>
          <input type="file" name="gambar" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="produk.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
