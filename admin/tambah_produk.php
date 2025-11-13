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

// Proses simpan produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id'] ?? '');
    $varian      = $conn->real_escape_string($_POST['varian'] ?? '');
    $nama_produk = $conn->real_escape_string($_POST['nama_produk'] ?? '');

    // Generate slug otomatis
    $slug = createSlug($nama_produk);
    $slug = uniqueSlug($conn, $slug);

    $upload_dir = "uploads/produk/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    $sql = "INSERT INTO produk (series_id, varian, nama_produk, slug, gambar)
            VALUES ('$series_id', '$varian', '$nama_produk', '$slug', '$gambar')";

    if (!$conn->query($sql)) {
        $error = "Gagal menyimpan produk: " . $conn->error;
    } else {
        $produk_id = $conn->insert_id;

        foreach ($spec_groups as $slugSpec => $meta) {
            $labels = $_POST['spec'][$slugSpec]['label'] ?? [];
            $values = $_POST['spec'][$slugSpec]['value'] ?? [];
            $grup   = $conn->real_escape_string($meta['label']);

            for ($i = 0; $i < count($labels); $i++) {
                $label = trim($labels[$i] ?? '');
                $nilai = trim($values[$i] ?? '');
                if ($label === '' && $nilai === '') continue;

                $labelEsc = $conn->real_escape_string($label);
                $nilaiEsc = $conn->real_escape_string($nilai);
                $order    = $i + 1;

                $conn->query("INSERT INTO produk_spesifikasi 
                             (produk_id, grup, label, nilai, sort_order) 
                             VALUES ($produk_id, '$grup', '$labelEsc', '$nilaiEsc', $order)");
            }
        }

        if (!empty($_POST['karoseri']) && is_array($_POST['karoseri'])) {
            foreach ($_POST['karoseri'] as $kid) {
                $kid = (int)$kid;
                $conn->query("INSERT INTO produk_karoseri (produk_id, karoseri_id) VALUES ($produk_id, $kid)");
            }
        }

        header("Location: produk.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
    .sidebar { height: 100vh; background: #0d6efd; color: white; padding-top: 20px; position: fixed; width: 220px; text-align: center; }
    .sidebar img { max-width: 180px; margin-bottom: 20px; }
    .sidebar a { display: block; padding: 12px 20px; color: white; text-decoration: none; margin: 4px 0; transition: background 0.2s; text-align: left; }
    .sidebar a:hover, .sidebar a.active { background: #0b5ed7; border-radius: 6px; }
    .content { margin-left: 220px; padding: 20px; }
    .dashboard-header { background: linear-gradient(90deg, #0d6efd, #0b5ed7); color: white; padding: 20px; border-radius: 12px; margin-bottom: 25px; }
    .btn-primary { background: #0d6efd; border: none; }
    .btn-primary:hover { background: #0b5ed7; }
    .table-spec { border-collapse: collapse; }
    .table-spec th, .table-spec td { vertical-align: middle; border: 2px solid #000; }
    .group-title { font-weight: 700; font-size: 1.05rem; }
    .table-spec input.form-control { border: 2px solid #000; height: 38px; font-weight: 500; }
    .table-spec input.form-control:focus { border-color: #198754; box-shadow: 0 0 3px rgba(25,135,84,0.5); }
  </style>
</head>
<body>
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
  <div class="dashboard-header">
    <h2>📦 Tambah Produk Baru</h2>
    <p>Isi semua data produk Hino melalui form ini.</p>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <!-- Series -->
        <div class="mb-3">
          <label class="form-label">Series</label>
          <select name="series_id" class="form-select" required>
            <option value="">-- Pilih Series --</option>
            <?php
            $series = $conn->query("SELECT * FROM series ORDER BY nama_series");
            while ($s = $series->fetch_assoc()) {
                echo "<option value='{$s['id']}'>".htmlspecialchars($s['nama_series'])."</option>";
            }
            ?>
          </select>
        </div>
        <!-- Varian -->
        <div class="mb-3">
          <label class="form-label">Varian</label>
          <select name="varian" class="form-select" required>
            <option value="">-- Pilih Varian --</option>
            <option value="All">All</option>
            <option value="Cargo">Cargo</option>
            <option value="Dump">Dump</option>
            <option value="Mixer">Mixer</option>
            <option value="Tractor Head">Tractor Head</option>
            <option value="Bus Mikro">Bus Mikro</option>
            <option value="Bus Medium">Bus Medium</option>
            <option value="Bus Besar">Bus Besar</option>
          </select>
        </div>
        <!-- Nama Produk -->
        <div class="mb-3">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" required>
        </div>

        <!-- Gambar -->
        <div class="mb-3">
          <label class="form-label">Gambar Produk</label>
          <input type="file" name="gambar" class="form-control" accept="image/*" required>
        </div>

        <!-- Pilih Karoseri -->
        <div class="mb-4">
          <label class="form-label">Pilih Karoseri</label>
          <?php
          $seriesList = $conn->query("SELECT DISTINCT series FROM karoseri ORDER BY series ASC");
          $selected_karoseri = $selected_karoseri ?? [];

          while ($s = $seriesList->fetch_assoc()):
            $seriesName = $s['series'];
          ?>
            <h6 class="mt-3"><?= htmlspecialchars($seriesName); ?></h6>
            <div class="row">
              <?php
              $karoseri = $conn->query("SELECT * FROM karoseri WHERE series='$seriesName' ORDER BY nama ASC");
              while ($kr = $karoseri->fetch_assoc()):
                $checked = in_array($kr['id'], $selected_karoseri) ? 'checked' : '';
              ?>
                <div class="col-md-4 mb-2">
                  <div class="form-check d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" name="karoseri[]" value="<?= $kr['id']; ?>" id="karoseri<?= $kr['id']; ?>" <?= $checked ?>>
                    <label class="form-check-label d-flex align-items-center" for="karoseri<?= $kr['id']; ?>">
                      <img src="uploads/karoseri/<?= htmlspecialchars($kr['slug']); ?>.webp" alt="<?= htmlspecialchars($kr['nama']); ?>" style="width:50px;height:auto;object-fit:contain;" class="me-2 border rounded">
                      <span><?= htmlspecialchars($kr['nama']); ?></span>
                    </label>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Spesifikasi -->
        <h5 class="mb-3">Spesifikasi</h5>
        <?php foreach ($spec_groups as $slugSpec => $meta): $slug_lower = $slugSpec; ?>
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="group-title"><?= htmlspecialchars($meta['label']); ?></div>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('<?= $slug_lower ?>')">+ Tambah Baris</button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered align-middle table-spec" id="table-<?= $slug_lower ?>">
                <thead class="table-light">
                  <tr>
                    <th style="width:40%">Parameter</th>
                    <th>Nilai</th>
                    <th style="width:80px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($meta['defaults'] as $def): ?>
                    <tr>
                      <td><input type="text" name="spec[<?= $slug_lower ?>][label][]" value="<?= htmlspecialchars($def) ?>" class="form-control"></td>
                      <td><input type="text" name="spec[<?= $slug_lower ?>][value][]" class="form-control"></td>
                      <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">Hapus</button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="d-flex gap-2">
          <a href="produk.php" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-success">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function addRow(slug) {
  const tbody = document.querySelector('#table-' + slug + ' tbody');
  if (!tbody) return alert("Tabel tidak ditemukan untuk slug: " + slug);
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td><input type="text" name="spec[${slug}][label][]" class="form-control" placeholder="Parameter"></td>
    <td><input type="text" name="spec[${slug}][value][]" class="form-control" placeholder="Nilai"></td>
    <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">Hapus</button></td>
  `;
  tbody.appendChild(tr);
}

function removeRow(btn) {
  btn.closest('tr').remove();
}
</script>
</body>
</html>
