<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

/** Daftar grup spesifikasi */
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

// --- Ambil produk ---
if (!isset($_GET['id'])) {
    die("ID produk tidak ditemukan.");
}
$produk_id = (int)$_GET['id'];

$produk = $conn->query("SELECT p.*, s.nama_series FROM produk p LEFT JOIN series s ON p.series_id=s.id WHERE p.id=$produk_id")->fetch_assoc();
if (!$produk) die("Produk tidak ditemukan.");

// --- Ambil spesifikasi ---
$existing_spec = [];
$resSpec = $conn->query("SELECT grup,label,nilai FROM produk_spesifikasi WHERE produk_id=$produk_id ORDER BY grup,sort_order,id");
while($r=$resSpec->fetch_assoc()) {
    $existing_spec[$r['grup']][] = $r;
}

// --- Ambil karoseri ---
$selected_karoseri = [];
$resKar = $conn->query("SELECT k.nama, k.slug FROM produk_karoseri pk JOIN karoseri k ON pk.karoseri_id=k.id WHERE pk.produk_id=$produk_id");
while($r=$resKar->fetch_assoc()) {
    $selected_karoseri[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/karoseri.css">
<style>
  .table-spec td { vertical-align: middle; }
  .group-title { font-weight: 700; font-size: 1.05rem; }
</style>
</head>

<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-info text-white">
      <h4 class="mb-0">Detail Produk</h4>
    </div>
    <div class="card-body">

      <!-- Nama, Series, Varian, Deskripsi -->
      <div class="mb-3"><strong>Nama Produk:</strong> <?=htmlspecialchars($produk['nama_produk'])?></div>
      <div class="mb-3"><strong>Series:</strong> <?=htmlspecialchars($produk['nama_series'])?></div>
      <div class="mb-3"><strong>Varian:</strong> <?=htmlspecialchars($produk['varian'])?></div>
      <div class="mb-3"><strong>Deskripsi:</strong> <p><?=nl2br(htmlspecialchars($produk['deskripsi']))?></p></div>

      <!-- Gambar -->
      <?php if(!empty($produk['gambar']) && file_exists("../uploads/".$produk['gambar'])): ?>
        <div class="mb-3">
          <strong>Gambar Produk:</strong><br>
          <img src="../uploads/<?=$produk['gambar']?>" class="img-fluid" style="max-width:250px;">
        </div>
      <?php endif; ?>

<!-- Karoseri -->
<?php if (!empty($selected_karoseri)): ?>
  <div class="mb-3">
    <strong>Karoseri Terpilih:</strong>
    <div class="row row-cols-2 row-cols-md-3 g-4 mt-2">
      <?php foreach ($selected_karoseri as $kr): ?>
        <div class="col text-center">
          <span class="karoseri-icon <?= $kr['slug'] ?> d-block mx-auto mb-2"></span>
          <div class="fw-semibold"><?= htmlspecialchars($kr['nama']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>



      <!-- Spesifikasi -->
      <h5 class="mt-4">Spesifikasi</h5>
      <?php foreach($spec_groups as $slug=>$meta):
        $rows = $existing_spec[$meta['label']] ?? [];
        if(empty($rows)) continue;
      ?>
        <div class="mb-4">
          <div class="group-title"><?=htmlspecialchars($meta['label'])?></div>
          <div class="table-responsive">
            <table class="table table-bordered align-middle table-spec">
              <thead class="table-light"><tr><th style="width:40%">Parameter</th><th>Nilai</th></tr></thead>
              <tbody>
                <?php foreach($rows as $r): ?>
                  <tr>
                    <td><?=htmlspecialchars($r['label'])?></td>
                    <td><?=htmlspecialchars($r['nilai'])?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; ?>

      <a href="produk.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
  </div>
</div>
</body>
</html>
