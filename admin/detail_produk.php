<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// --- Ambil ID ---
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("ID produk tidak ditemukan.");
}

// --- Data produk ---
$qProduk = $conn->query("SELECT p.*, s.nama_series 
                         FROM produk p 
                         LEFT JOIN series s ON s.id = p.series_id 
                         WHERE p.id=$id");
if ($qProduk->num_rows === 0) die("Produk tidak ditemukan.");
$produk = $qProduk->fetch_assoc();

// --- Daftar grup spesifikasi (sama dengan edit.php) ---
$spec_groups = [
  'performa'     => ['label' => 'PERFORMA'],
  'model_mesin'  => ['label' => 'MODEL MESIN'],
  'kopling'      => ['label' => 'KOPLING'],
  'transmisi'    => ['label' => 'TRANSMISI'],
  'kemudi'       => ['label' => 'KEMUDI'],
  'sumbu'        => ['label' => 'SUMBU'],
  'rem'          => ['label' => 'REM'],
  'roda_ban'     => ['label' => 'RODA & BAN'],
  'Sistim_Listrik_accu' => ['label' => 'SISTIM LISTRIK ACCU'],
  'Tangki_Solar' => ['label' => 'TANGKI SOLAR'],
  'Dimensi'      => ['label' => 'DIMENSI'],
  'Suspensi'     => ['label' => 'SUSPENSI'],
  'Berat_Chasis' => ['label' => 'BERAT CHASIS'],
];

// --- Ambil spesifikasi ---
$existing_spec = [];
$resSpec = $conn->query("SELECT grup,label,nilai 
                         FROM produk_spesifikasi 
                         WHERE produk_id=$id 
                         ORDER BY grup,sort_order,id");
while ($r = $resSpec->fetch_assoc()) {
    $groupKey = strtolower(str_replace(' ', '_', $r['grup']));
    $existing_spec[$groupKey][] = $r;
}


// --- Ambil karoseri (urut sesuai input) ---
$selected_karoseri = [];
$resKar = $conn->query("SELECT k.nama, k.slug 
                        FROM produk_karoseri pk 
                        JOIN karoseri k ON pk.karoseri_id=k.id 
                        WHERE pk.produk_id=$id 
                        ORDER BY pk.id ASC");
while ($r = $resKar->fetch_assoc()) {
    $selected_karoseri[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .table-spec td { vertical-align: middle; }
  .group-title { font-weight: 700; font-size: 1.05rem; margin-top: 1rem; }
</style>
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-info text-white">
      <h4 class="mb-0">Detail Produk</h4>
    </div>
    <div class="card-body">

      <!-- Info utama -->
      <div class="mb-3"><strong>Nama Produk:</strong> <?= htmlspecialchars($produk['nama_produk']); ?></div>
      <div class="mb-3"><strong>Series:</strong> <?= htmlspecialchars($produk['nama_series']); ?></div>
      <div class="mb-3"><strong>Varian:</strong> <?= htmlspecialchars($produk['varian']); ?></div>
      <?php if (!empty($produk['deskripsi'])): ?>
        <div class="mb-3"><strong>Deskripsi:</strong><p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p></div>
      <?php endif; ?>

      <!-- Gambar utama -->
      <?php if (!empty($produk['gambar']) && file_exists("uploads/".$produk['gambar'])): ?>
        <div class="mb-3">
          <strong>Gambar Produk:</strong><br>
          <img src="uploads/<?= htmlspecialchars($produk['gambar']); ?>" class="img-fluid" style="max-width:250px;">
        </div>
      <?php endif; ?>

      <!-- Karoseri -->
      <?php if (!empty($selected_karoseri)): ?>
        <div class="mb-4">
          <strong>Karoseri Terpilih:</strong>
          <div class="row row-cols-2 row-cols-md-4 g-3 mt-2">
            <?php foreach ($selected_karoseri as $kr): ?>
              <div class="col text-center">
                <img src="uploads/karoseri/<?= htmlspecialchars($kr['slug']); ?>.png" 
                     alt="<?= htmlspecialchars($kr['nama']); ?>" 
                     class="img-fluid mb-2 border rounded" style="max-height:120px;object-fit:contain;">
                <div><?= htmlspecialchars($kr['nama']); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Spesifikasi -->
      <h5 class="mt-4">Spesifikasi</h5>
      <?php foreach ($spec_groups as $slug => $meta):
        $rows = $existing_spec[$slug] ?? [];
        if (empty($rows)) continue;
      ?>
        <div class="mb-4">
          <div class="group-title"><?= htmlspecialchars($meta['label']); ?></div>
          <table class="table table-bordered table-spec">
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td style="width:40%;"><?= htmlspecialchars($r['label']); ?></td>
                  <td><?= htmlspecialchars($r['nilai']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>

      <a href="produk.php" class="btn btn-secondary mt-3">Kembali</a>

    </div>
  </div>
</div>
</body>
</html>
