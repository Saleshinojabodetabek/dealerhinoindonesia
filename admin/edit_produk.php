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

// --- Konfigurasi grup spesifikasi (sinkron dengan tambah_produk.php) ---
$spec_groups = [
  'performa'     => ['label' => 'PERFORMA',     'defaults' => ['Kecepatan maksimum (km/h)', 'Daya tanjak']],
  'model_mesin'  => ['label' => 'MODEL MESIN',  'defaults' => ['Model', 'Tipe', 'Tenaga maksimum', 'Torsi maksimum', 'Kapasitas']],
  'kopling'      => ['label' => 'KOPLING',      'defaults' => ['Tipe']],
  'transmisi'    => ['label' => 'TRANSMISI',    'defaults' => ['Tipe', 'Rasio']],
  'kemudi'       => ['label' => 'KEMUDI',       'defaults' => ['Tipe']],
  'sumbu'        => ['label' => 'SUMBU',        'defaults' => ['Depan', 'Belakang']],
  'rem'          => ['label' => 'REM',          'defaults' => ['Utama', 'Parkir', 'Tambahan']],
  'roda_ban'     => ['label' => 'RODA & BAN',   'defaults' => ['Ukuran Ban']],
  'sistim_listrik_accu' => ['label' => 'SISTIM LISTRIK ACCU', 'defaults' => ['Accu (V-Ah)']],
  'tangki_solar' => ['label' => 'TANGKI SOLAR', 'defaults' => ['Kapasitas']],
  'dimensi'      => ['label' => 'DIMENSI',      'defaults' => ['Panjang', 'Lebar', 'Tinggi', 'Jarak Sumbu Roda']],
  'suspensi'     => ['label' => 'SUSPENSI',     'defaults' => ['Depan', 'Belakang']],
  'berat_chasis' => ['label' => 'BERAT CHASIS', 'defaults' => ['Depan', 'Belakang', 'Total']],
];

// --- Ambil produk ---
if (!isset($_GET['id'])) {
    die("ID produk tidak ditemukan.");
}
$produk_id = (int)$_GET['id'];

$produk = $conn->query("SELECT * FROM produk WHERE id = $produk_id")->fetch_assoc();
if (!$produk) {
    die("Produk tidak ditemukan.");
}

// --- Ambil spesifikasi yang sudah tersimpan ---
$existing_spec = [];
$resSpec = $conn->query("SELECT grup, label, nilai, sort_order 
                         FROM produk_spesifikasi 
                         WHERE produk_id = $produk_id
                         ORDER BY grup, sort_order, id");
while ($r = $resSpec->fetch_assoc()) {
    $existing_spec[$r['grup']][] = ['label' => $r['label'], 'nilai' => $r['nilai']];
}

// --- Ambil karoseri yang sudah terhubung ---
$selected_karoseri = [];
$resKar = $conn->query("SELECT karoseri_id FROM produk_karoseri WHERE produk_id = $produk_id");
while ($r = $resKar->fetch_assoc()) {
    $selected_karoseri[] = $r['karoseri_id'];
}

// --- Simpan update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    // Upload gambar utama
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $gambar_set = "";
    if (!empty($_FILES['gambar']['name'])) {
        $newName = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $newName)) {
            if (!empty($produk['gambar']) && file_exists($upload_dir . $produk['gambar'])) {
                @unlink($upload_dir . $produk['gambar']);
            }
            $gambar_set = ", gambar = '" . $conn->real_escape_string($newName) . "'";
        }
    }

    // Upload karoseri (opsional, single)
    $karoseri_set = "";
    if (!empty($_FILES['karoseri_gambar']['name'])) {
        $newKar = "karoseri_" . time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['karoseri_gambar']['name']));
        if (move_uploaded_file($_FILES['karoseri_gambar']['tmp_name'], $upload_dir . $newKar)) {
            if (!empty($produk['karoseri_gambar']) && file_exists($upload_dir . $produk['karoseri_gambar'])) {
                @unlink($upload_dir . $produk['karoseri_gambar']);
            }
            $karoseri_set = ", karoseri_gambar = '" . $conn->real_escape_string($newKar) . "'";
        }
    }

    // Simpan produk
    $spec_from_form = $_POST['spec'] ?? [];
    $spec_json = $conn->real_escape_string(json_encode($spec_from_form, JSON_UNESCAPED_UNICODE));

    $sqlUpdate = "
        UPDATE produk 
        SET series_id = '$series_id',
            varian = '$varian',
            nama_produk = '$nama_produk',
            deskripsi = '$deskripsi',
            spesifikasi = '$spec_json'
            $gambar_set
            $karoseri_set
        WHERE id = $produk_id
    ";
    if (!$conn->query($sqlUpdate)) {
        die("Gagal update produk: " . $conn->error);
    }

    // Replace spesifikasi detail
    $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id = $produk_id");
    foreach ($spec_groups as $slug => $meta) {
        $labels = $_POST['spec'][$slug]['label'] ?? [];
        $values = $_POST['spec'][$slug]['value'] ?? [];
        for ($i = 0; $i < count($labels); $i++) {
            $label = trim($labels[$i] ?? '');
            $nilai = trim($values[$i] ?? '');
            if ($label === '' && $nilai === '') continue;
            $labelEsc = $conn->real_escape_string($label);
            $nilaiEsc = $conn->real_escape_string($nilai);
            $order    = $i + 1;
            $grup     = $conn->real_escape_string($meta['label']);
            $conn->query("INSERT INTO produk_spesifikasi (produk_id, grup, label, nilai, sort_order)
                          VALUES ($produk_id, '$grup', '$labelEsc', '$nilaiEsc', $order)");
        }
    }

    // Replace relasi karoseri
    $conn->query("DELETE FROM produk_karoseri WHERE produk_id = $produk_id");
    if (!empty($_POST['karoseri'])) {
        foreach ($_POST['karoseri'] as $kid) {
            $kid = (int)$kid;
            $conn->query("INSERT INTO produk_karoseri (produk_id, karoseri_id) VALUES ($produk_id, $kid)");
        }
    }

    header("Location: produk.php?updated=1");
    exit();
}

// Helper ambil baris untuk group tertentu
function rows_for_group($groupLabel, $meta, $existing_spec) {
    if (!empty($existing_spec[$groupLabel])) {
        return $existing_spec[$groupLabel];
    }
    $rows = [];
    foreach ($meta['defaults'] as $d) {
        $rows[] = ['label' => $d, 'nilai' => ''];
    }
    return $rows;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>.table-spec td { vertical-align: middle; } .group-title { font-weight: 700; font-size: 1.05rem; }</style>
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white"><h4 class="mb-0">Edit Produk</h4></div>
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">

        <!-- Series -->
        <div class="mb-3">
          <label class="form-label">Series</label>
          <select name="series_id" class="form-select" required>
            <option value="">-- Pilih Series --</option>
            <?php
            $series = $conn->query("SELECT * FROM series ORDER BY nama_series");
            while ($s = $series->fetch_assoc()) {
                $sel = ($s['id'] == $produk['series_id']) ? 'selected' : '';
                echo "<option value='{$s['id']}' $sel>".htmlspecialchars($s['nama_series'])."</option>";
            }
            ?>
          </select>
        </div>

        <!-- Varian -->
        <div class="mb-3">
          <label class="form-label">Varian</label>
          <select name="varian" class="form-select" required>
            <?php
              $var_opts = ['All','Cargo','Dump','Mixer'];
              foreach ($var_opts as $v) {
                  $sel = ($produk['varian'] === $v) ? 'selected' : '';
                  echo "<option value='{$v}' {$sel}>{$v}</option>";
              }
            ?>
          </select>
        </div>

        <!-- Nama & Deskripsi -->
        <div class="mb-3">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
        </div>

        <!-- Gambar Utama -->
        <div class="mb-3">
          <label class="form-label">Gambar Produk (opsional)</label><br>
          <?php if (!empty($produk['gambar']) && file_exists("../uploads/".$produk['gambar'])): ?>
            <img src="../uploads/<?= $produk['gambar'] ?>" width="120" class="mb-2"><br>
          <?php endif; ?>
          <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>

        <!-- Karoseri gambar -->
        <div class="mb-3">
          <label class="form-label">Karoseri (Gambar, opsional)</label><br>
          <?php if (!empty($produk['karoseri_gambar']) && file_exists("../uploads/".$produk['karoseri_gambar'])): ?>
            <img src="../uploads/<?= $produk['karoseri_gambar'] ?>" width="120" class="mb-2"><br>
          <?php endif; ?>
          <input type="file" name="karoseri_gambar" class="form-control" accept="image/*">
        </div>

        <!-- Pilih Karoseri (multi checkbox) -->
        <div class="mb-4">
          <label class="form-label">Pilih Karoseri</label>
          <div class="row">
            <?php
            $karoseri = $conn->query("SELECT * FROM karoseri ORDER BY nama");
            while ($kr = $karoseri->fetch_assoc()):
              $checked = in_array($kr['id'], $selected_karoseri) ? 'checked' : '';
            ?>
              <div class="col-md-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="karoseri[]" value="<?= $kr['id'] ?>" id="karoseri<?= $kr['id'] ?>" <?= $checked ?>>
                  <label class="form-check-label" for="karoseri<?= $kr['id'] ?>">
                    <?= htmlspecialchars($kr['nama']); ?>
                  </label>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>

        <h5 class="mb-3">Spesifikasi</h5>
        <?php foreach ($spec_groups as $slug => $meta): 
            $groupLabel = $meta['label']; 
            $rows = rows_for_group($groupLabel, $meta, $existing_spec);
        ?>
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="group-title"><?= htmlspecialchars($groupLabel); ?></div>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('<?= $slug ?>')">+ Tambah Baris</button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered align-middle table-spec" id="table-<?= $slug ?>">
                <thead class="table-light"><tr><th>Parameter</th><th>Nilai</th><th>Aksi</th></tr></thead>
                <tbody>
                  <?php foreach ($rows as $r): ?>
                    <tr>
                      <td><input type="text" name="spec[<?= $slug ?>][label][]" value="<?= htmlspecialchars($r['label']) ?>" class="form-control"></td>
                      <td><input type="text" name="spec[<?= $slug ?>][value][]" value="<?= htmlspecialchars($r['nilai']) ?>" class="form-control"></td>
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
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function addRow(slug) {
  const tbody = document.querySelector('#table-' + slug + ' tbody');
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td><input type="text" name="spec[${slug}][label][]" class="form-control"></td>
    <td><input type="text" name="spec[${slug}][value][]" class="form-control"></td>
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
