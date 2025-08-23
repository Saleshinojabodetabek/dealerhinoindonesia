<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "Produk tidak ditemukan.";
    exit();
}

// Ambil data produk
$qProduk = $conn->query("SELECT p.*, s.nama_series FROM produk p 
                         LEFT JOIN series s ON s.id = p.series_id
                         WHERE p.id=$id");
if ($qProduk->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit();
}
$produk = $qProduk->fetch_assoc();

// Ambil spesifikasi
$spec_groups = [
    'PERFORMA',
    'MODEL MESIN',
    'KOPLING',
    'TRANSMISI',
    'KEMUDI',
    'SUMBU',
    'REM',
    'RODA & BAN',
    'SISTIM LISTRIK ACCU',
    'TANGKI SOLAR',
    'DIMENSI',
    'SUSPENSI',
    'BERAT CHASIS',
];

$spesifikasi = [];
$qSpec = $conn->query("SELECT * FROM produk_spesifikasi WHERE produk_id=$id ORDER BY grup, sort_order");
while ($row = $qSpec->fetch_assoc()) {
    $spesifikasi[$row['grup']][] = $row;
}

// Ambil karoseri
$karoseri = [];
$qKar = $conn->query("SELECT k.* FROM produk_karoseri pk 
                      JOIN karoseri k ON k.id = pk.karoseri_id
                      WHERE pk.produk_id = $id 
                      ORDER BY k.series, k.nama");
while ($row = $qKar->fetch_assoc()) {
    $karoseri[$row['series']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Produk - <?= htmlspecialchars($produk['nama_produk']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">

  <h2 class="mb-4"><?= htmlspecialchars($produk['nama_produk']); ?></h2>
  <p class="text-muted">Series: <?= htmlspecialchars($produk['nama_series']); ?> | Varian: <?= htmlspecialchars($produk['varian']); ?></p>

  <!-- Gambar Produk -->
  <?php if (!empty($produk['gambar'])): ?>
    <div class="mb-4">
      <img src="uploads/produk/<?= htmlspecialchars($produk['gambar']); ?>" 
           alt="<?= htmlspecialchars($produk['nama_produk']); ?>" 
           class="img-fluid rounded shadow">
    </div>
  <?php endif; ?>

  <!-- Deskripsi -->
  <?php if (!empty($produk['deskripsi'])): ?>
    <div class="mb-5">
      <h5>Deskripsi</h5>
      <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
    </div>
  <?php endif; ?>

  <!-- Spesifikasi -->
  <div class="mb-5">
    <h4 class="mb-3">Spesifikasi</h4>
    <?php foreach ($spec_groups as $group): ?>
      <?php if (!empty($spesifikasi[$group])): ?>
        <h5 class="mt-4"><?= htmlspecialchars($group); ?></h5>
        <table class="table table-bordered">
          <tbody>
            <?php foreach ($spesifikasi[$group] as $row): ?>
              <tr>
                <th style="width: 40%;"><?= htmlspecialchars($row['label']); ?></th>
                <td><?= htmlspecialchars($row['nilai']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <!-- Karoseri -->
  <?php if (!empty($karoseri)): ?>
    <div class="mb-5">
      <h4 class="mb-3">Pilihan Karoseri</h4>
      <?php foreach ($karoseri as $seriesName => $items): ?>
        <h6 class="mt-3"><?= htmlspecialchars($seriesName); ?></h6>
        <div class="row">
          <?php foreach ($items as $kr): ?>
            <div class="col-6 col-md-3 mb-3 text-center">
              <img src="uploads/karoseri/<?= htmlspecialchars($kr['slug']); ?>.png" 
                   alt="<?= htmlspecialchars($kr['nama']); ?>" 
                   class="img-fluid border rounded mb-2" style="max-height:120px;object-fit:contain;">
              <p class="mb-0"><?= htmlspecialchars($kr['nama']); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <a href="produk.php" class="btn btn-secondary">Kembali</a>

</div>
</body>
</html>
