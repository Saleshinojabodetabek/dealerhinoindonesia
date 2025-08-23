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

// Ambil ID produk
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: produk.php");
    exit();
}

// Ambil data produk
$qProduk = $conn->query("SELECT * FROM produk WHERE id=$id");
$produk = $qProduk->fetch_assoc();
if (!$produk) {
    header("Location: produk.php");
    exit();
}

// Ambil spesifikasi
$specList = [];
$qSpec = $conn->query("SELECT * FROM produk_spesifikasi WHERE produk_id=$id ORDER BY grup, sort_order");
while ($row = $qSpec->fetch_assoc()) {
    $specList[$row['grup']][] = $row;
}

// Ambil karoseri yang sudah terpilih
$selected_karoseri = [];
$qKaroseri = $conn->query("SELECT karoseri_id FROM produk_karoseri WHERE produk_id=$id");
while ($row = $qKaroseri->fetch_assoc()) {
    $selected_karoseri[] = $row['karoseri_id'];
}

// Grup spesifikasi (sama seperti tambah.php)
$spec_groups = [
    'PERFORMA' => ['Kecepatan maksimum (km/h)', 'Daya tanjak'],
    'MODEL MESIN' => ['Model', 'Tipe', 'Tenaga maksimum', 'Torsi maksimum', 'Kapasitas'],
    'KOPLING' => ['Tipe'],
    'TRANSMISI' => ['Tipe', 'Rasio'],
    'KEMUDI' => ['Tipe'],
    'SUMBU' => ['Depan', 'Belakang'],
    'REM' => ['Utama', 'Parkir', 'Tambahan'],
    'RODA & BAN' => ['Ukuran Ban'],
    'SISTIM LISTRIK ACCU' => ['Accu (V-Ah)'],
    'TANGKI SOLAR' => ['Kapasitas'],
    'DIMENSI' => ['Dimensi'],
    'SUSPENSI' => ['Depan & Belakang'],
    'BERAT CHASIS' => ['Depan & Belakang'],
];

// Update produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    // Gambar (opsional)
    $upload_dir = "../uploads/produk/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $gambar = $produk['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // Update tabel produk
    $sql = "UPDATE produk SET series_id='$series_id', varian='$varian', nama_produk='$nama_produk', deskripsi='$deskripsi', gambar='$gambar' WHERE id=$id";
    if ($conn->query($sql)) {
        // Hapus spesifikasi lama & simpan baru
        $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id=$id");
        foreach ($_POST['spec'] as $slug => $group) {
            $labels = $group['label'] ?? [];
            $values = $group['value'] ?? [];
            for ($i=0; $i<count($labels); $i++) {
                $label = trim($labels[$i]);
                $nilai = trim($values[$i]);
                if ($label==='' && $nilai==='') continue;
                $labelEsc = $conn->real_escape_string($label);
                $nilaiEsc = $conn->real_escape_string($nilai);
                $conn->query("INSERT INTO produk_spesifikasi (produk_id, grup, label, nilai, sort_order) VALUES ($id, '$slug', '$labelEsc', '$nilaiEsc', $i+1)");
            }
        }

        // Update karoseri
        $conn->query("DELETE FROM produk_karoseri WHERE produk_id=$id");
        if (!empty($_POST['karoseri'])) {
            foreach ($_POST['karoseri'] as $kid) {
                $kid = (int)$kid;
                $conn->query("INSERT INTO produk_karoseri (produk_id, karoseri_id) VALUES ($id, $kid)");
            }
        }

        header("Location: produk.php?updated=1");
        exit();
    } else {
        $error = "Gagal update: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk</title>
<link rel="stylesheet" href="admin/css/karoseri.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-warning">
      <h4 class="mb-0">Edit Produk</h4>
    </div>
    <div class="card-body">
      <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="post" enctype="multipart/form-data">
        <!-- Series -->
        <div class="mb-3">
          <label class="form-label">Series</label>
          <select name="series_id" class="form-select" required>
            <?php
            $series = $conn->query("SELECT * FROM series ORDER BY nama_series");
            while ($s = $series->fetch_assoc()) {
                $sel = $s['id']==$produk['series_id'] ? 'selected' : '';
                echo "<option value='{$s['id']}' $sel>".htmlspecialchars($s['nama_series'])."</option>";
            }
            ?>
          </select>
        </div>

        <!-- Varian -->
        <div class="mb-3">
          <label class="form-label">Varian</label>
          <select name="varian" class="form-select">
            <?php
            $variants = ['All','Cargo','Dump','Mixer'];
            foreach($variants as $v){
                $sel = $produk['varian']==$v ? 'selected' : '';
                echo "<option value='$v' $sel>$v</option>";
            }
            ?>
          </select>
        </div>

        <!-- Nama & Deskripsi -->
        <div class="mb-3">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
        </div>

        <!-- Gambar -->
        <div class="mb-3">
          <label class="form-label">Gambar Produk (kosongkan jika tidak ganti)</label>
          <input type="file" name="gambar" class="form-control" accept="image/*">
          <?php if($produk['gambar']): ?>
            <img src="../uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>" style="max-width:150px" class="mt-2 border rounded">
          <?php endif; ?>
        </div>

        <!-- Karoseri -->
        <div class="mb-4">
          <label class="form-label">Pilih Karoseri</label>
          <?php
          $seriesList = $conn->query("SELECT DISTINCT series FROM karoseri ORDER BY series ASC");
          while ($s = $seriesList->fetch_assoc()):
            $seriesName = $s['series'];
          ?>
            <h6 class="mt-3"><?= htmlspecialchars($seriesName) ?></h6>
            <div class="row">
              <?php
              $karoseri = $conn->query("SELECT * FROM karoseri WHERE series='$seriesName' ORDER BY nama ASC");
              while ($kr = $karoseri->fetch_assoc()):
                $checked = in_array($kr['id'], $selected_karoseri) ? 'checked' : '';
              ?>
              <div class="col-md-4 mb-2">
                <div class="form-check d-flex align-items-center">
                  <input class="form-check-input me-2" type="checkbox" name="karoseri[]" value="<?= $kr['id'] ?>" <?= $checked ?>>
                  <label class="form-check-label d-flex align-items-center">
                    <img src="../uploads/karoseri/<?= htmlspecialchars($kr['slug']) ?>.png" style="width:50px;height:auto;" class="me-2 border rounded">
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
        <?php foreach ($spec_groups as $group => $defaults): ?>
        <div class="mb-4">
          <h6><?= htmlspecialchars($group) ?></h6>
          <table class="table table-bordered" id="table-<?= md5($group) ?>">
            <thead class="table-light">
              <tr><th>Parameter</th><th>Nilai</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              <?php
              $rows = $specList[$group] ?? [];
              if(empty($rows)){
                foreach($defaults as $def){
                  echo "<tr><td><input type='text' name='spec[$group][label][]' value='$def' class='form-control'></td><td><input type='text' name='spec[$group][value][]' class='form-control'></td><td><button type='button' class='btn btn-sm btn-outline-danger' onclick='removeRow(this)'>Hapus</button></td></tr>";
                }
              } else {
                foreach($rows as $r){
                  echo "<tr><td><input type='text' name='spec[$group][label][]' value='".htmlspecialchars($r['label'])."' class='form-control'></td><td><input type='text' name='spec[$group][value][]' value='".htmlspecialchars($r['nilai'])."' class='form-control'></td><td><button type='button' class='btn btn-sm btn-outline-danger' onclick='removeRow(this)'>Hapus</button></td></tr>";
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php endforeach; ?>

        <div class="d-flex gap-2">
          <a href="produk.php" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function removeRow(btn){ btn.closest('tr').remove(); }
</script>
</body>
</html>
