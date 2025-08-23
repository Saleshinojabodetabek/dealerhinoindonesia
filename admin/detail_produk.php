<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");

include 'config.php';

$spec_groups = [
  'performa' => ['label'=>'PERFORMA', 'order'=>['Kecepatan maksimum (km/h)','Daya tanjak']],
  'model_mesin' => ['label'=>'MODEL MESIN', 'order'=>['Model','Tipe','Tenaga maksimum','Torsi maksimum','Kapasitas']],
  'kopling' => ['label'=>'KOPLING','order'=>['Tipe']],
  'transmisi'=>['label'=>'TRANSMISI','order'=>['Tipe','Rasio']],
  'kemudi'=>['label'=>'KEMUDI','order'=>['Tipe']],
  'sumbu'=>['label'=>'SUMBU','order'=>['Depan','Belakang']],
  'rem'=>['label'=>'REM','order'=>['Utama','Parkir','Tambahan']],
  'roda_ban'=>['label'=>'RODA & BAN','order'=>['Ukuran Ban']],
  'sistim_listrik_accu'=>['label'=>'SISTIM LISTRIK ACCU','order'=>['Accu (V-Ah)']],
  'tangki_solar'=>['label'=>'TANGKI SOLAR','order'=>['Kapasitas']],
  'dimensi'=>['label'=>'DIMENSI','order'=>['Panjang','Lebar','Tinggi','Jarak Sumbu Roda']],
  'suspensi'=>['label'=>'SUSPENSI','order'=>['Depan','Belakang']],
  'berat_chasis'=>['label'=>'BERAT CHASIS','order'=>['Depan','Belakang','Total']],
];

if (!isset($_GET['id'])) die("ID produk tidak ditemukan");
$produk_id = (int)$_GET['id'];

// Ambil produk
$produk = $conn->query("SELECT p.*, s.nama_series FROM produk p LEFT JOIN series s ON p.series_id = s.id WHERE p.id = $produk_id")->fetch_assoc();
if (!$produk) die("Produk tidak ditemukan.");

// Ambil spesifikasi dari DB
$existing_spec = [];
$resSpec = $conn->query("SELECT grup,label,nilai FROM produk_spesifikasi WHERE produk_id=$produk_id ORDER BY grup,sort_order,id");
while($r = $resSpec->fetch_assoc()) {
    $existing_spec[$r['grup']][] = ['label'=>$r['label'], 'nilai'=>$r['nilai']];
}

// --- Helper: urutkan sesuai $spec_groups, hapus baris kosong ---
function get_spec_rows($groupLabel, $order, $existing_spec) {
    $rows = [];
    if (!empty($existing_spec[$groupLabel])) {
        // Buat map label → nilai
        $map = [];
        foreach($existing_spec[$groupLabel] as $r){
            if(trim($r['nilai']) !== '') $map[$r['label']] = $r['nilai'];
        }
        // Tampilkan sesuai urutan $order
        foreach($order as $label){
            if(isset($map[$label])) $rows[] = ['label'=>$label,'nilai'=>$map[$label]];
        }
    }
    return $rows;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk - <?= htmlspecialchars($produk['nama_produk']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;background:#f8f9fa;}
.sidebar{height:100vh;background:#0d6efd;color:white;padding-top:20px;position:fixed;width:220px;text-align:center;}
.sidebar img{max-width:180px;margin-bottom:20px;}
.sidebar a{display:block;padding:12px 20px;color:white;text-decoration:none;margin:4px 0;text-align:left;transition:0.2s;}
.sidebar a:hover,.sidebar a.active{background:#0b5ed7;border-radius:6px;}
.content{margin-left:220px;padding:20px;}
.group-title{font-weight:700;font-size:1.05rem;margin-top:1rem;}
.spec-table td,.spec-table th{vertical-align:middle;}
.card img{max-width:100%;border-radius:8px;}
</style>
</head>
<body>
<div class="sidebar">
<img src="../images/logo3.png" alt="Logo Hino">
<a href="index.php">Dashboard</a>
<a href="artikel.php">Artikel</a>
<a href="produk.php" class="active">Produk</a>
<a href="pesan.php">Pesan Customer</a>
<a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="dashboard-header" style="background:linear-gradient(90deg,#0d6efd,#0b5ed7);color:white;padding:20px;border-radius:12px;margin-bottom:25px;">
<h2>📦 Detail Produk</h2>
<p>Informasi lengkap produk Hino yang dipilih.</p>
</div>

<div class="card p-4 shadow-sm">
<h3><?= htmlspecialchars($produk['nama_produk']); ?></h3>
<p><strong>Series:</strong> <?= htmlspecialchars($produk['nama_series'] ?? '-'); ?></p>
<p><strong>Varian:</strong> <?= htmlspecialchars($produk['varian'] ?? '-'); ?></p>
<?php if(!empty($produk['gambar']) && file_exists("../uploads/".$produk['gambar'])): ?>
<img src="../uploads/<?= $produk['gambar']; ?>" alt="<?= htmlspecialchars($produk['nama_produk']); ?>">
<?php else: ?>
<p class="text-muted">Tidak ada gambar</p>
<?php endif; ?>

<?php if(!empty($produk['deskripsi'])): ?>
<h5 class="mt-3">Deskripsi:</h5>
<p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
<?php endif; ?>

<h5 class="mt-3">Spesifikasi Lengkap:</h5>
<?php foreach($spec_groups as $slug=>$meta):
    $groupLabel = $meta['label'];
    $rows = get_spec_rows($groupLabel,$meta['order'],$existing_spec);
    if(empty($rows)) continue; // skip jika kosong
?>
<div class="group-title"><?= htmlspecialchars($groupLabel); ?></div>
<div class="table-responsive mb-3">
<table class="table table-bordered spec-table">
<thead class="table-light"><tr><th style="width:40%">Parameter</th><th>Nilai</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
<td><?= htmlspecialchars($r['label']); ?></td>
<td><?= htmlspecialchars($r['nilai']); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endforeach; ?>

<a href="produk.php" class="btn btn-primary mt-3">Kembali ke Daftar Produk</a>
</div>
</div>
</body>
</html>
