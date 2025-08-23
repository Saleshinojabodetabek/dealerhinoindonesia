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

/** Daftar grup spesifikasi & default baris parameternya (boleh Anda ubah/tambah) */
$spec_groups = [
  'performa'     => ['label' => 'PERFORMA',     'defaults' => ['Kecepatan maksimum (km/h)', 'Daya tanjak']],
  'model_mesin'  => ['label' => 'MODEL MESIN',  'defaults' => ['Model', 'Tipe', 'Tenaga maksimum', 'Torsi maksimum', 'Kapasitas']],
  'kopling'      => ['label' => 'KOPLING',      'defaults' => ['Tipe']],
  'transmisi'    => ['label' => 'TRANSMISI',    'defaults' => ['Tipe', 'Rasio']],
  'kemudi'       => ['label' => 'KEMUDI',       'defaults' => ['Tipe']],
  'sumbu'        => ['label' => 'SUMBU',        'defaults' => ['Depan', 'Belakang']],
  'rem'          => ['label' => 'REM',          'defaults' => ['Utama', 'Parkir', 'Tambahan']],
  'roda_ban'     => ['label' => 'RODA & BAN',   'defaults' => ['Ukuran Ban']],
  'Sistim_Listrik_accu'     => ['label' => 'SISTIM LISTRIK ACCU',   'defaults' => ['Accu (V-Ah)']],
  'Tangki_Solar'     => ['label' => 'TANGKI SOLAR',   'defaults' => ['Kapasitas']],
  'Dimensi'     => ['label' => 'DIMENSI',   'defaults' => ['Dimensi']],
  'Suspensi'     => ['label' => 'SUSPENSI',   'defaults' => ['Depan & Belakang']],
  'Berat_Chasis'     => ['label' => 'BERAT CHASIS',   'defaults' => ['Depan & Belakang']],
];

// Simpan produk + spesifikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']); // All/Cargo/Dump/Mixer
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    // Pastikan folder uploads ada
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Upload gambar utama
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // Upload gambar karoseri
    $karoseri_gambar = null;
    if (!empty($_FILES['karoseri_gambar']['name'])) {
        $karoseri_gambar = "karoseri_" . time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['karoseri_gambar']['name']));
        move_uploaded_file($_FILES['karoseri_gambar']['tmp_name'], $upload_dir . $karoseri_gambar);
    }

    // Insert produk
    $sql = "INSERT INTO produk (series_id, varian, nama_produk, deskripsi, gambar, karoseri_gambar)
            VALUES ('$series_id', '$varian', '$nama_produk', '$deskripsi', '$gambar', '$karoseri_gambar')";
    if (!$conn->query($sql)) {
        $error = "Gagal menyimpan produk: " . $conn->error;
    } else {
        $produk_id = $conn->insert_id;

        // Simpan spesifikasi per grup (baris parameter–nilai)
        foreach ($spec_groups as $slug => $meta) {
            $labels = isset($_POST['spec'][$slug]['label']) ? $_POST['spec'][$slug]['label'] : [];
            $values = isset($_POST['spec'][$slug]['value']) ? $_POST['spec'][$slug]['value'] : [];
            for ($i = 0; $i < count($labels); $i++) {
                $label = trim($labels[$i] ?? '');
                $nilai = trim($values[$i] ?? '');
                if ($label === '' && $nilai === '') continue;

                $labelEsc = $conn->real_escape_string($label);
                $nilaiEsc = $conn->real_escape_string($nilai);
                $order    = $i + 1;
                $grup     = $conn->real_escape_string($meta['label']); // simpan nama grup (huruf besar)

                $conn->query("INSERT INTO produk_spesifikasi (produk_id, grup, label, nilai, sort_order)
                              VALUES ($produk_id, '$grup', '$labelEsc', '$nilaiEsc', $order)");
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
  <meta charset="UTF-8" />
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .table-spec td { vertical-align: middle; }
    .group-title { font-weight: 700; font-size: 1.05rem; }
  </style>
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Tambah Produk Baru</h4>
    </div>
    <div class="card-body">
      <?php if (!empty($error)) : ?>
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
          </select>
          <div class="form-text">Pilih <b>All</b> jika produk masuk semua varian.</div>
        </div>

        <!-- Nama & Deskripsi -->
        <div class="mb-3">
          <label class="form-label">Nama Produk</label>
          <input type="text" name="nama_produk" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>

        <!-- Gambar Utama -->
        <div class="mb-3">
          <label class="form-label">Gambar Produk</label>
          <input type="file" name="gambar" class="form-control" accept="image/*" required>
        </div>

        <!-- Karoseri (gambar) -->
        <div class="mb-4">
          <label class="form-label">Karoseri (Gambar)</label>
          <input type="file" name="karoseri_gambar" class="form-control" accept="image/*">
        </div>

        <h5 class="mb-3">Spesifikasi</h5>

        <?php foreach ($spec_groups as $slug => $meta): ?>
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="group-title"><?= htmlspecialchars($meta['label']); ?></div>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('<?= $slug ?>')">+ Tambah Baris</button>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered align-middle table-spec" id="table-<?= $slug ?>">
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
                      <td><input type="text" name="spec[<?= $slug ?>][label][]" value="<?= htmlspecialchars($def) ?>" class="form-control"></td>
                      <td><input type="text" name="spec[<?= $slug ?>][value][]" class="form-control"></td>
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
