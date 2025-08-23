<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Daftar grup spesifikasi (sama seperti tambah produk)
$spec_groups = [
    'performa' => ['label' => 'PERFORMA'],
    'model_mesin' => ['label' => 'MODEL MESIN'],
    'kopling' => ['label' => 'KOPLING'],
    'transmisi' => ['label' => 'TRANSMISI'],
    'kemudi' => ['label' => 'KEMUDI'],
    'sumbu' => ['label' => 'SUMBU'],
    'rem' => ['label' => 'REM'],
    'roda_ban' => ['label' => 'RODA & BAN'],
    'Sistim_Listrik_accu' => ['label' => 'SISTIM LISTRIK ACCU'],
    'Tangki_Solar' => ['label' => 'TANGKI SOLAR'],
    'Dimensi' => ['label' => 'DIMENSI'],
    'Suspensi' => ['label' => 'SUSPENSI'],
    'Berat_Chasis' => ['label' => 'BERAT CHASIS'],
];

// Ambil ID produk
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: produk.php");
    exit();
}

// Ambil data produk
$res = $conn->query("SELECT * FROM produk WHERE id=$id");
if ($res->num_rows === 0) {
    header("Location: produk.php");
    exit();
}
$produk = $res->fetch_assoc();

// Ambil karoseri terkait
$karoseri_selected = [];
$krRes = $conn->query("SELECT karoseri_id FROM produk_karoseri WHERE produk_id=$id");
while ($kr = $krRes->fetch_assoc()) {
    $karoseri_selected[] = $kr['karoseri_id'];
}

// Ambil spesifikasi produk
$spesifikasi = [];
$spRes = $conn->query("SELECT * FROM produk_spesifikasi WHERE produk_id=$id ORDER BY grup, sort_order");
while ($sp = $spRes->fetch_assoc()) {
    $slug = array_search($sp['grup'], array_column($spec_groups, 'label'));
    if (!$slug) {
        // jika grup di DB tidak ada di array, skip
        continue;
    }
    $spesifikasi[$slug][] = [
        'label' => $sp['label'],
        'nilai' => $sp['nilai']
    ];
}

// Update data produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    // Upload gambar jika ada
    $gambar = $produk['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $upload_dir = "../uploads/produk/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // Update produk
    $sql = "UPDATE produk SET 
                series_id='$series_id',
                varian='$varian',
                nama_produk='$nama_produk',
                deskripsi='$deskripsi',
                gambar='$gambar'
            WHERE id=$id";
    if (!$conn->query($sql)) {
        $error = "Gagal update produk: " . $conn->error;
    } else {
        // Hapus spesifikasi lama
        $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id=$id");

        // Simpan spesifikasi baru
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
                              VALUES ($id, '$grup', '$labelEsc', '$nilaiEsc', $order)");
            }
        }

        // Update karoseri
        $conn->query("DELETE FROM produk_karoseri WHERE produk_id=$id");
        if (!empty($_POST['karoseri'])) {
            foreach ($_POST['karoseri'] as $kid) {
                $kid = (int)$kid;
                $conn->query("INSERT INTO produk_karoseri (produk_id, karoseri_id)
                              VALUES ($id, $kid)");
            }
        }

        header("Location: produk.php?updated=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-warning text-white">
      <h4 class="mb-0">Edit Produk</h4>
    </div>
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
                $sel = $produk['series_id'] == $s['id'] ? 'selected' : '';
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
            $variants = ['All','Cargo','Dump','Mixer'];
            foreach ($variants as $v) {
                $sel = $produk['varian'] == $v ? 'selected' : '';
                echo "<option value='$v' $sel>$v</option>";
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

        <!-- Gambar -->
        <div class="mb-3">
          <label class="form-label">Gambar Produk (kosongkan jika tidak ganti)</label><br>
          <?php if ($produk['gambar']): ?>
            <img src="../uploads/produk/<?= htmlspecialchars($produk['gambar']); ?>" alt="" style="max-width:150px;" class="mb-2 d-block">
          <?php endif; ?>
          <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>

        <!-- Karoseri -->
        <div class="mb-4">
          <label class="form-label">Pilih Karoseri</label>
          <?php
          $seriesList = $conn->query("SELECT DISTINCT series FROM karoseri ORDER BY series ASC");
          while ($s = $seriesList->fetch_assoc()):
            $seriesName = $s['series'];
          ?>
            <h6 class="mt-3"><?= htmlspecialchars($seriesName); ?></h6>
            <div class="row">
              <?php
              $karoseri = $conn->query("SELECT * FROM karoseri WHERE series='$seriesName' ORDER BY nama ASC");
              while ($kr = $karoseri->fetch_assoc()):
                $checked = in_array($kr['id'], $karoseri_selected) ? 'checked' : '';
              ?>
                <div class="col-md-4 mb-2">
                  <div class="form-check d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" name="karoseri[]" value="<?= $kr['id']; ?>" id="karoseri<?= $kr['id']; ?>" <?= $checked ?>>
                    <label class="form-check-label d-flex align-items-center" for="karoseri<?= $kr['id']; ?>">
                      <img src="../uploads/karoseri/<?= htmlspecialchars($kr['slug']); ?>.png" alt="<?= htmlspecialchars($kr['nama']); ?>" style="width:50px;" class="me-2 border rounded">
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
        <?php foreach ($spec_groups as $slug => $meta): ?>
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="fw-bold"><?= htmlspecialchars($meta['label']); ?></div>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('<?= $slug ?>')">+ Tambah Baris</button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered align-middle" id="table-<?= $slug ?>">
                <thead class="table-light">
                  <tr><th>Parameter</th><th>Nilai</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                  <?php 
                  if (!empty($spesifikasi[$slug])):
                    foreach ($spesifikasi[$slug] as $row): ?>
                      <tr>
                        <td><input type="text" name="spec[<?= $slug ?>][label][]" value="<?= htmlspecialchars($row['label']) ?>" class="form-control"></td>
                        <td><input type="text" name="spec[<?= $slug ?>][value][]" value="<?= htmlspecialchars($row['nilai']) ?>" class="form-control"></td>
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
          <button type="submit" class="btn btn-warning">Update Produk</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function addRow(slug) {
  const tbody = document.querySelector('#table-' + slug + ' tbody');
  if (!tbody) return;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td><input type="text" name="spec[${slug}][label][]" class="form-control"></td>
    <td><input type="text" name="spec[${slug}][value][]" class="form-control"></td>
    <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">Hapus</button></td>
  `;
  tbody.appendChild(tr);
}
function removeRow(btn) { btn.closest('tr').remove(); }
</script>
</body>
</html>
