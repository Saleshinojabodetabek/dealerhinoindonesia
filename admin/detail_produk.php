<?php
include 'config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { echo "Produk tidak ditemukan"; exit(); }

$produk = $conn->query("SELECT p.*, s.nama_series 
                        FROM produk p 
                        LEFT JOIN series s ON p.series_id = s.id 
                        WHERE p.id=$id")->fetch_assoc();
if (!$produk) { echo "Produk tidak ditemukan"; exit(); }

$spesifikasi = $conn->query("SELECT * FROM produk_spesifikasi WHERE produk_id=$id ORDER BY grup, sort_order");
$spec_data = [];
while ($r = $spesifikasi->fetch_assoc()) {
    $spec_data[$r['grup']][] = $r;
}

$karoseri = $conn->query("SELECT k.* FROM produk_karoseri pk 
                          JOIN karoseri k ON pk.karoseri_id=k.id 
                          WHERE pk.produk_id=$id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><?= htmlspecialchars($produk['nama_produk']); ?></h4>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <?php if (!empty($produk['gambar'])): ?>
          <img src="../uploads/produk/<?= htmlspecialchars($produk['gambar']); ?>" class="img-fluid mb-3" style="max-width:300px;">
        <?php endif; ?>
        <p><strong>Series:</strong> <?= htmlspecialchars($produk['nama_series']); ?></p>
        <p><strong>Varian:</strong> <?= htmlspecialchars($produk['varian']); ?></p>
        <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
      </div>

      <h5>Spesifikasi</h5>
      <?php foreach ($spec_data as $group => $items): ?>
        <h6 class="mt-3"><?= htmlspecialchars($group); ?></h6>
        <table class="table table-sm table-bordered mb-3">
          <tbody>
            <?php foreach ($items as $row): ?>
              <tr>
                <td style="width:40%"><?= htmlspecialchars($row['label']); ?></td>
                <td><?= htmlspecialchars($row['nilai']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endforeach; ?>

      <h5 class="mt-4">Karoseri</h5>
      <div class="row">
        <?php while ($k = $karoseri->fetch_assoc()): ?>
          <div class="col-md-3 col-6 mb-3 text-center">
            <img src="../uploads/karoseri/<?= htmlspecialchars($k['slug']); ?>.png" class="img-fluid mb-2" style="max-height:80px;">
            <div><?= htmlspecialchars($k['nama']); ?></div>
          </div>
        <?php endwhile; ?>
      </div>

      <a href="produk.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
  </div>
</div>
</body>
</html>
