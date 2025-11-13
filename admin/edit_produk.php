<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';
include 'function.php';

// Daftar grup spesifikasi
$spec_groups = [
    'performa' => ['label'=>'PERFORMA','defaults'=>['Kecepatan Maksimum (km/h)','Daya Tanjak (tan %)']],
    'model_mesin' => ['label'=>'MODEL MESIN','defaults'=>['Model','Model Tipe','Tenaga Maksimum (PS/rpm)','Daya Maksimum (Kgm/rpm)','Jumlah Silinder','Diameter x Langkah Piston (mm)','Isi Silinder (L)']],
    'kopling' => ['label'=>'KOPLING','defaults'=>['Tipe','Diameter Cakram (mm)']],
    'transmisi' => ['label'=>'TRANSMISI','defaults'=>['Tipe','Ke-1','Ke-2','Ke-3','Ke-4','Ke-5','Mundur']],
    'kemudi' => ['label'=>'KEMUDI','defaults'=>['Tipe','Minimal Radius Putar (m)']],
    'sumbu' => ['label'=>'SUMBU','defaults'=>['Depan','Belakang','Perbandingan Gigi Akhir']],
    'rem' => ['label'=>'REM','defaults'=>['Rem Utama','Rem Pelambat','Rem Parkir']],
    'roda_ban' => ['label'=>'RODA & BAN','defaults'=>['Ukuran Ban','Ukuran Rim','Jumlah Ban']],
    'Sistim_Listrik_accu' => ['label'=>'SISTIM LISTRIK ACCU','defaults'=>['Accu (V-Ah)']],
    'Tangki_Solar' => ['label'=>'TANGKI SOLAR','defaults'=>['Kapasitas (L)']],
    'Dimensi' => ['label'=>'DIMENSI','defaults'=>['Jarak Sumbu Roda	WB (mm)','Total Panjang OL (mm)','Total Lebar OW (mm)','Total Tinggi OH (mm)','Lebar Jejak Depan FR Tr (mm)','Lebar Jejak Belakang RR Tr (mm)','Julur Depan	FOH (mm)','Julur Belakang ROH (mm)','Kabin Kesumbu Roda Belakang CA (mm)']],
    'Suspensi' => ['label'=>'SUSPENSI','defaults'=>['Depan & Belakang']],
    'Berat_Chasis' => ['label'=>'BERAT CHASIS','defaults'=>['Berat Kosong (kg)','Berat Total Kendaraan (kg)']],
];

// Ambil ID produk
$produk_id = (int)($_GET['id'] ?? 0);
if ($produk_id <= 0) header("Location: produk.php");

// Ambil data produk
$res = $conn->query("SELECT * FROM produk WHERE id=$produk_id");
if (!$res || $res->num_rows==0) header("Location: produk.php");
$produk = $res->fetch_assoc();

// Ambil karoseri yang dipilih
$karoseriSelected = [];
$res_kar = $conn->query("SELECT karoseri_id FROM produk_karoseri WHERE produk_id=$produk_id");
while ($row = $res_kar->fetch_assoc()) $karoseriSelected[] = (int)$row['karoseri_id'];

// Ambil spesifikasi
$specData = [];
$res_spec = $conn->query("SELECT grup,label,nilai,sort_order FROM produk_spesifikasi WHERE produk_id=$produk_id ORDER BY grup, sort_order");
while ($row = $res_spec->fetch_assoc()) $specData[$row['grup']][] = ['label'=>$row['label'],'value'=>$row['nilai']];

// Proses update
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id'] ?? '');
    $varian      = $conn->real_escape_string($_POST['varian'] ?? '');
    $nama_produk = $conn->real_escape_string($_POST['nama_produk'] ?? '');
    
    $gambar = $produk['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $upload_dir = "uploads/produk/";
        if (!is_dir($upload_dir)) mkdir($upload_dir,0777,true);
        $gambar = time()."_".preg_replace('/\s+/','_',basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'],$upload_dir.$gambar);
    }

    // ✅ Generate slug baru
    $slug = createSlug($nama_produk);
    $slug = uniqueSlug($conn, $slug, $produk_id);

    $conn->query("UPDATE produk 
                  SET series_id='$series_id', varian='$varian', nama_produk='$nama_produk', slug='$slug', gambar='$gambar' 
                  WHERE id=$produk_id");

    // Update spesifikasi
    $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id=$produk_id");
    foreach ($spec_groups as $slugSpec=>$meta) {
        $labels = $_POST['spec'][$slugSpec]['label'] ?? [];
        $values = $_POST['spec'][$slugSpec]['value'] ?? [];
        $grup = $conn->real_escape_string($meta['label']);
        for ($i=0;$i<count($labels);$i++) {
            $label = trim($labels[$i] ?? '');
            $nilai = trim($values[$i] ?? '');
            if ($label==='' && $nilai==='') continue;
            $conn->query("INSERT INTO produk_spesifikasi (produk_id,grup,label,nilai,sort_order) 
                          VALUES ($produk_id,'$grup','".$conn->real_escape_string($label)."','".$conn->real_escape_string($nilai)."',".($i+1).")");
        }
    }

    // Update karoseri
    $conn->query("DELETE FROM produk_karoseri WHERE produk_id=$produk_id");
    if (!empty($_POST['karoseri'])) {
        foreach ($_POST['karoseri'] as $kid) {
            $conn->query("INSERT INTO produk_karoseri (produk_id,karoseri_id) VALUES ($produk_id,".(int)$kid.")");
        }
    }

    header("Location: produk.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background:#f8f9fa; }
.sidebar { height:100vh; background:#0d6efd; color:white; padding-top:20px; position:fixed; width:220px; text-align:center; }
.sidebar img { max-width:180px;margin-bottom:20px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; margin:4px 0; text-align:left; transition:0.2s; }
.sidebar a:hover, .sidebar a.active { background:#0b5ed7; border-radius:6px; }
.content { margin-left:220px; padding:20px; }
.dashboard-header { background:linear-gradient(90deg,#0d6efd,#0b5ed7); color:white; padding:20px; border-radius:12px; margin-bottom:25px; }
.table-spec { border-collapse:collapse; }
.table-spec th, .table-spec td { vertical-align:middle; border:2px solid #000; }
.group-title { font-weight:700; font-size:1.05rem; margin-top:10px; }
.table-spec input.form-control { border:2px solid #000; height:38px; font-weight:500; }
.table-spec input.form-control:focus { border-color:#198754; box-shadow:0 0 3px rgba(25,135,84,0.5);}
.img-karoseri { width:50px; height:auto; object-fit:contain; margin-right:8px; border:1px solid #ccc; padding:2px; border-radius:4px; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="text-center mb-4"><img src="../images/logo3.webp" alt="Logo Hino"></div>
  <a href="index.php">Dashboard</a>
  <a href="artikel.php">Artikel</a>
  <a href="produk.php" class="active">Produk</a>
  <a href="pesan.php">Pesan Customer</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
  <div class="dashboard-header">
    <h2>✏️ Edit Produk</h2>
    <p>Ubah informasi, gambar, spesifikasi, dan karoseri produk Hino.</p>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="post" enctype="multipart/form-data">

        <!-- Series -->
        <div class="mb-3">
          <label class="form-label">Series</label>
          <select name="series_id" class="form-select" required>
            <option value="">-- Pilih Series --</option>
            <?php
            $series = $conn->query("SELECT * FROM series ORDER BY nama_series");
            while ($s=$series->fetch_assoc()):
              $selected=($s['id']==$produk['series_id'])?'selected':'';
              echo "<option value='{$s['id']}' $selected>".htmlspecialchars($s['nama_series'])."</option>";
            endwhile;
            ?>
          </select>
        </div>

        <!-- Varian -->
        <div class="mb-3">
          <label class="form-label">Varian</label>
          <select name="varian" class="form-select" required>
            <option value="">-- Pilih Varian --</option>
            <?php foreach (['All','Cargo','Dump','Mixer','Tractor Head','Bus Mikro','Bus Medium','Bus Besar'] as $v):
              $selected = ($produk['varian']==$v)?'selected':'';
              echo "<option value='$v' $selected>$v</option>";
            endforeach; ?>
          </select>
        </div>

        <!-- Nama Produk -->
        <div class="mb-3">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" required value="<?= htmlspecialchars($produk['nama_produk']) ?>">
        </div>

        <!-- Gambar -->
        <div class="mb-3">
          <label class="form-label">Gambar Produk</label>
          <?php if($produk['gambar']): ?>
          <div class="mb-2"><img src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" width="120"></div>
          <?php endif; ?>
          <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>

        <!-- Karoseri -->
        <div class="mb-4">
          <label class="form-label">Pilih Karoseri</label>
          <?php
          $seriesList=$conn->query("SELECT DISTINCT series FROM karoseri ORDER BY series ASC");
          while($s=$seriesList->fetch_assoc()):
            $seriesName=$s['series'];
          ?>
          <h6 class="mt-3"><?= htmlspecialchars($seriesName) ?></h6>
          <div class="row">
            <?php
            $karoseri=$conn->query("SELECT * FROM karoseri WHERE series='$seriesName' ORDER BY nama ASC");
            while($kr=$karoseri->fetch_assoc()):
              $checked = in_array($kr['id'],$karoseriSelected)?'checked':'';
            ?>
            <div class="col-md-4 mb-2">
              <div class="form-check d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" name="karoseri[]" value="<?= $kr['id'] ?>" <?= $checked ?>>
                <label class="form-check-label d-flex align-items-center">
                  <img src="uploads/karoseri/<?= htmlspecialchars($kr['slug']) ?>.webp" class="img-karoseri" alt="<?= htmlspecialchars($kr['nama']) ?>">
                  <span><?= htmlspecialchars($kr['nama']) ?></span>
                </label>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
          <?php endwhile; ?>
        </div>

        <!-- Spesifikasi -->
        <h5 class="mb-3">Spesifikasi</h5>
        <?php foreach($spec_groups as $slug=>$meta): $slug_lower=$slug;?>
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="group-title"><?= htmlspecialchars($meta['label']) ?></div>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('<?= $slug_lower ?>')">+ Tambah Baris</button>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered align-middle table-spec" id="table-<?= $slug_lower ?>">
              <thead class="table-light">
                <tr><th style="width:40%">Parameter</th><th>Nilai</th><th style="width:80px">Aksi</th></tr>
              </thead>
              <tbody>
              <?php
              $existing=$specData[$meta['label']]??[];
              if($existing):
                foreach($existing as $row):
              ?>
              <tr>
                <td><input type="text" name="spec[<?= $slug_lower ?>][label][]" value="<?= htmlspecialchars($row['label']) ?>" class="form-control"></td>
                <td><input type="text" name="spec[<?= $slug_lower ?>][value][]" value="<?= htmlspecialchars($row['value']) ?>" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">Hapus</button></td>
              </tr>
              <?php
                endforeach;
              else:
                foreach($meta['defaults'] as $def):
              ?>
              <tr>
                <td><input type="text" name="spec[<?= $slug_lower ?>][label][]" value="<?= htmlspecialchars($def) ?>" class="form-control"></td>
                <td><input type="text" name="spec[<?= $slug_lower ?>][value][]" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">Hapus</button></td>
              </tr>
              <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php endforeach; ?>

        <div class="d-flex gap-2">
          <a href="produk.php" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-success">Update Produk</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
function addRow(slug){
  const tbody=document.querySelector('#table-'+slug+' tbody');
  if(!tbody) return alert("Tabel tidak ditemukan untuk slug: "+slug);
  const tr=document.createElement('tr');
  tr.innerHTML=`<td><input type="text" name="spec[${slug}][label][]" class="form-control" placeholder="Parameter"></td>
  <td><input type="text" name="spec[${slug}][value][]" class="form-control" placeholder="Nilai"></td>
  <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">Hapus</button></td>`;
  tbody.appendChild(tr);
}

function removeRow(btn){ btn.closest('tr').remove(); }
</script>

</body>
</html>
