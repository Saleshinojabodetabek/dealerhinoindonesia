<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Ambil Series dari database
$seriesResult = $conn->query("SELECT id, nama FROM series");
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
  <h2 class="mb-4">Tambah Produk Baru</h2>
  <form action="tambah_proses.php" method="POST" enctype="multipart/form-data">
    
    <!-- Nama Produk -->
    <div class="mb-3">
      <label class="form-label">Nama Produk</label>
      <input type="text" name="nama" class="form-control" required>
    </div>

    <!-- Series -->
    <div class="mb-3">
      <label class="form-label">Series</label>
      <select name="series_id" class="form-select" required>
        <option value="">-- Pilih Series --</option>
        <?php while ($row = $seriesResult->fetch_assoc()) { ?>
          <option value="<?= $row['id']; ?>"><?= $row['nama']; ?></option>
        <?php } ?>
      </select>
    </div>

    <!-- Varian -->
    <div class="mb-3">
      <label class="form-label">Varian</label>
      <select name="varian" class="form-select" required>
        <option value="">-- Pilih Varian --</option>
        <option value="All">All (Semua Varian)</option>
        <option value="Cargo">Cargo</option>
        <option value="Dump">Dump</option>
        <option value="Mixer">Mixer</option>
      </select>
    </div>

    <!-- Upload Gambar Produk -->
    <div class="mb-3">
      <label class="form-label">Gambar Produk</label>
      <input type="file" name="gambar" class="form-control" required>
    </div>

    <!-- Upload Gambar Karoseri -->
    <div class="mb-3">
      <label class="form-label">Gambar Karoseri</label>
      <input type="file" name="gambar_karoseri" class="form-control">
    </div>

    <hr>
    <h4>Spesifikasi Produk</h4>

    <!-- Performa -->
    <div class="mb-3">
      <label class="form-label">Performa</label>
      <input type="text" name="performa" class="form-control" placeholder="Contoh: Kecepatan Maks 120 km/h, Daya Tanjak 30%">
    </div>

    <!-- Model Mesin -->
    <div class="mb-3">
      <label class="form-label">Model Mesin</label>
      <textarea name="mesin" class="form-control" rows="2"></textarea>
    </div>

    <!-- Dimensi -->
    <div class="mb-3">
      <label class="form-label">Dimensi</label>
      <textarea name="dimensi" class="form-control" rows="2"></textarea>
    </div>

    <!-- Suspensi -->
    <div class="mb-3">
      <label class="form-label">Suspensi</label>
      <textarea name="suspensi" class="form-control" rows="2"></textarea>
    </div>

    <!-- Rem -->
    <div class="mb-3">
      <label class="form-label">Rem</label>
      <textarea name="rem" class="form-control" rows="2"></textarea>
    </div>

    <!-- Roda & Ban -->
    <div class="mb-3">
      <label class="form-label">Roda & Ban</label>
      <textarea name="roda_ban" class="form-control" rows="2"></textarea>
    </div>

    <!-- Sistem Listrik Accu -->
    <div class="mb-3">
      <label class="form-label">Sistem Listrik Accu</label>
      <textarea name="accu" class="form-control" rows="2"></textarea>
    </div>

    <!-- Tangki Solar -->
    <div class="mb-3">
      <label class="form-label">Tangki Solar</label>
      <textarea name="tangki" class="form-control" rows="2"></textarea>
    </div>

    <!-- Berat Chassis -->
    <div class="mb-3">
      <label class="form-label">Berat Chassis</label>
      <textarea name="berat" class="form-control" rows="2"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Produk</button>
  </form>
</div>
</body>
</html>
