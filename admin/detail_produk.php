<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Ambil ID produk
$produk_id = (int)($_GET['id'] ?? 0);
if ($produk_id <= 0) {
    header("Location: produk.php");
    exit();
}

// Ambil data produk
$res = $conn->query("SELECT p.*, s.nama_series 
                     FROM produk p
                     LEFT JOIN series s ON p.series_id = s.id
                     WHERE p.id = $produk_id");
if (!$res || $res->num_rows == 0) {
    header("Location: produk.php");
    exit();
}
$produk = $res->fetch_assoc();

// Ambil karoseri terkait
$karoseriList = [];
$res_kar = $conn->query("SELECT k.nama, k.slug 
                         FROM produk_karoseri pk
                         JOIN karoseri k ON pk.karoseri_id = k.id
                         WHERE pk.produk_id = $produk_id");
while ($row = $res_kar->fetch_assoc()) {
    $karoseriList[] = $row;
}

// Daftar grup spesifikasi (untuk urutan)
$spec_groups = [
    'performa' => ['label'=>'PERFORMA'],
    'model_mesin' => ['label'=>'MODEL MESIN'],
    'kopling' => ['label'=>'KOPLING'],
    'transmisi' => ['label'=>'TRANSMISI'],
    'kemudi' => ['label'=>'KEMUDI'],
    'sumbu' => ['label'=>'SUMBU'],
    'rem' => ['label'=>'REM'],
    'roda_ban' => ['label'=>'RODA & BAN'],
    'Sistim_Listrik_accu' => ['label'=>'SISTIM LISTRIK ACCU'],
    'Tangki_Solar' => ['label'=>'TANGKI SOLAR'],
    'Dimensi' => ['label'=>'DIMENSI'],
    'Suspensi' => ['label'=>'SUSPENSI'],
    'Berat_Chasis' => ['label'=>'BERAT CHASIS'],
];

// Ambil spesifikasi
$specs = [];
$res_spec = $conn->query("SELECT grup, label, nilai, sort_order 
                          FROM produk_spesifikasi 
                          WHERE produk_id=$produk_id 
                          ORDER BY sort_order ASC");
while ($row = $res_spec->fetch_assoc()) {
    $specs[$row['grup']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
.sidebar { height: 100vh; background: #0d6efd; color: white; padding-top: 20px; position: fixed; width: 220px; text-align: center; }
.sidebar img { max-width: 180px; margin-bottom: 20px; }
.sidebar a { display: block; padding: 12px 20px; color: white; text-decoration: none; margin: 4px 0; transition: background 0.2s; text-align: left; }
.sidebar a:hover, .sidebar a.active { background: #0b5ed7; border-radius: 6px; }
.content { margin-left: 220px; padding: 20px; }
.dashboard-header { background: linear-gradient(90deg, #0d6efd, #0b5ed7); color: white; padding: 20px; border-radius: 12px; margin-bottom: 25px; }
.table-spec { border-collapse: collapse; width: 100%; }
.table-spec th, .table-spec td { border:2px solid #000; vertical-align: middle; padding:8px; text-align:left; }
.table-spec th { background-color: #f8f9fa; }
.table-spec td:first-child { width: 40%; }
.table-spec td:last-child { width: 60%; }
.group-title { font-weight:700; font-size:1.1rem; margin-top:20px; }
.img-karoseri { width:60px; height:auto; object-fit:contain; margin-right:8px; border:1px solid #ccc; padding:2px; border-radius:4px; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="text-center mb-4">
    <img src="../images/logo3.webp" alt="Logo Hino">
  </div>
  <a href="index.php">Dashboard</a>
  <a href="artikel.php">Artikel</a>
  <a href="produk.php" class="active">Produk</a>
  <a href="pesan.php">Pesan Customer</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
  <!-- Header -->
  <div class="dashboard-header">
    <h2>📦 Detail Produk</h2>
    <p>Informasi lengkap tentang produk Hino ini.</p>
  </div>

  <div class="card shadow">
    <div class="card-body">

      <!-- Info dasar -->
      <h5>Informasi Produk</h5>
      <table class="table table-bordered">
        <tr><th>Nama Produk</th><td><?= htmlspecialchars($produk['nama_produk']) ?></td></tr>
        <tr><th>Series</th><td><?= htmlspecialchars($produk['nama_series']) ?></td></tr>
        <tr><th>Varian</th><td><?= htmlspecialchars($produk['varian']) ?></td></tr>
        <tr><th>Gambar</th>
          <td>
          <?php if($produk['gambar'] && file_exists("uploads/produk/".$produk['gambar'])): ?>
            <img src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" width="180">
          <?php else: ?>
            - Tidak ada gambar -
          <?php endif; ?>
          </td>
        </tr>
      </table>

      <!-- Karoseri -->
      <h5 class="mt-4">Karoseri Terkait</h5>
      <?php if(count($karoseriList)>0): ?>
        <div class="d-flex flex-wrap">
          <?php foreach($karoseriList as $k): ?>
            <div class="d-flex align-items-center me-3 mb-2">
              <img src="uploads/karoseri/<?= htmlspecialchars($k['slug']) ?>.webp" class="img-karoseri" alt="<?= htmlspecialchars($k['nama']) ?>">
              <span><?= htmlspecialchars($k['nama']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>- Tidak ada karoseri terkait -</p>
      <?php endif; ?>

      <!-- Spesifikasi -->
      <h5 class="mt-4">Spesifikasi</h5>
      <?php foreach ($spec_groups as $slug => $meta):
          $grupName = $meta['label'];
          if (!empty($specs[$grupName])):
      ?>
      <div class="group-title"><?= htmlspecialchars($grupName) ?></div>
      <table class="table table-spec mb-3">
        <thead class="table-light"><tr><th>Parameter</th><th>Nilai</th></tr></thead>
        <tbody>
          <?php foreach ($specs[$grupName] as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['label']) ?></td>
            <td><?= htmlspecialchars($r['nilai']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; endforeach; ?>

      <a href="produk.php" class="btn btn-secondary mt-3">Kembali</a>

    </div>
  </div>
</div>
</body>
</html>
