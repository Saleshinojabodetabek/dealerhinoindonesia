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

if(!empty($selected_karoseri)): ?>
  <div class="mb-3">
    <strong>Karoseri Terpilih:</strong>
    <div class="d-flex flex-wrap gap-4 mt-2">
      <?php foreach($selected_karoseri as $kr): ?>
        <?php
        // Cek apakah file gambar karoseri ada
        $karoseri_image = "admin/karoseri/karoseri.png" . $kr['slug'] . ".png";
        ?>
        <div class="text-center">
          <?php if(file_exists($karoseri_image)): ?>
            <img src="<?=$karoseri_image?>" alt="<?=htmlspecialchars($kr['nama'])?>" style="width:120px; display:block; margin-bottom:5px;">
          <?php else: ?>
            <span class="karoseri-icon <?=$kr['slug']?> mb-1" style="display:block; width:120px; height:80px;"></span>
          <?php endif; ?>
          <div><?=htmlspecialchars($kr['nama'])?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin/css/karoseri.css">
<style>
  .table-spec td { vertical-align: middle; }
  .group-title { font-weight: 700; font-size: 1.05rem; }
  .karoseri-icon { display:inline-block; width:24px; height:24px; background-size:contain; background-repeat:no-repeat; }
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
      <?php if(!empty($selected_karoseri)): ?>
        <div class="mb-3">
          <strong>Karoseri Terpilih:</strong>
          <div class="d-flex flex-wrap gap-3 mt-2">
            <?php foreach($selected_karoseri as $kr): ?>
              <div class="d-flex align-items-center">
                <span class="karoseri-icon <?=$kr['slug']?> me-2"></span>
                <span><?=htmlspecialchars($kr['nama'])?></span>
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
