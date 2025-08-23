<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include '../config.php';

// Ambil daftar series dari DB
$series_result = $conn->query("SELECT * FROM series ORDER BY nama_series");

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $_POST['series_id'];
    $varian      = $_POST['varian'];
    $nama_produk = $_POST['nama_produk'];
    $deskripsi   = $_POST['deskripsi'];

    // Upload gambar utama
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $target_name = "produk_" . time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        $upload_dir  = "../uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $target_name);
        $gambar = $target_name;
    }

    // Upload banyak gambar karoseri
    $karoseri_files = [];
    if (!empty($_FILES['karoseri_gambar']['name'][0])) {
        foreach ($_FILES['karoseri_gambar']['name'] as $i => $filename) {
            if ($_FILES['karoseri_gambar']['error'][$i] === UPLOAD_ERR_OK) {
                $target_name = "karoseri_" . time() . "_" . preg_replace('/\s+/', '_', basename($filename));
                move_uploaded_file($_FILES['karoseri_gambar']['tmp_name'][$i], $upload_dir . $target_name);
                $karoseri_files[] = $target_name;
            }
        }
    }
    $karoseri_json = $karoseri_files ? json_encode($karoseri_files) : null;

    // Ambil spesifikasi (pakai JSON)
    $spesifikasi = [];
    if (isset($_POST['spesifikasi'])) {
        $spesifikasi = $_POST['spesifikasi'];
    }
    $spesifikasi_json = json_encode($spesifikasi);

    // Simpan ke DB
    $sql = "INSERT INTO produk (series_id, varian, nama_produk, deskripsi, gambar, karoseri_gambar, spesifikasi) 
            VALUES ('$series_id', '$varian', '$nama_produk', '$deskripsi', '$gambar', '$karoseri_json', '$spesifikasi_json')";
    if ($conn->query($sql)) {
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Daftar grup spesifikasi
$spec_groups = [
  'performa'       => ['label' => 'PERFORMA',       'defaults' => ['Kecepatan maksimum (km/h)', 'Daya tanjak']],
  'model_mesin'    => ['label' => 'MODEL MESIN',    'defaults' => ['Model', 'Tipe', 'Tenaga maksimum', 'Torsi maksimum', 'Kapasitas']],
  'kopling'        => ['label' => 'KOPLING',        'defaults' => ['Tipe']],
  'transmisi'      => ['label' => 'TRANSMISI',      'defaults' => ['Tipe', 'Rasio']],
  'kemudi'         => ['label' => 'KEMUDI',         'defaults' => ['Tipe']],
  'sumbu'          => ['label' => 'SUMBU',          'defaults' => ['Depan', 'Belakang']],
  'rem'            => ['label' => 'REM',            'defaults' => ['Utama', 'Parkir', 'Tambahan']],
  'roda_ban'       => ['label' => 'RODA & BAN',     'defaults' => ['Ukuran Ban']],
  'sistem_listrik' => ['label' => 'SISTEM LISTRIK', 'defaults' => ['Accu']],
  'tangki_solar'   => ['label' => 'TANGKI SOLAR',   'defaults' => ['Kapasitas (liter)']],
  'dimensi'        => ['label' => 'DIMENSI',        'defaults' => ['Panjang (mm)', 'Lebar (mm)', 'Tinggi (mm)', 'Wheelbase (mm)', 'Jarak terendah (mm)']],
  'suspensi'       => ['label' => 'SUSPENSI',       'defaults' => ['Depan', 'Belakang']],
  'berat_chassis'  => ['label' => 'BERAT CHASSIS',  'defaults' => ['Berat Kosong', 'Berat Maksimum']],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

  <h2 class="mb-4">Tambah Produk Baru</h2>
  <form method="POST" enctype="multipart/form-data">

    <!-- Pilihan Series -->
    <div class="mb-3">
      <label class="form-label">Series</label>
      <select name="series_id" class="form-select" required>
        <option value="">-- Pilih Series --</option>
        <?php while ($row = $series_result->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_series']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Pilihan Varian -->
    <div class="mb-3">
      <label class="form-label">Varian</label>
      <select name="varian" class="form-select" required>
        <option value="All">All (semua varian)</option>
        <option value="Cargo">Cargo</option>
        <option value="Dump">Dump</option>
        <option value="Mixer">Mixer</option>
      </select>
    </div>

    <!-- Nama Produk -->
    <div class="mb-3">
      <label class="form-label">Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>

    <!-- Deskripsi -->
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4"></textarea>
    </div>

    <!-- Gambar utama -->
    <div class="mb-3">
      <label class="form-label">Gambar Utama</label>
      <input type="file" name="gambar" class="form-control" accept="image/*">
    </div>

    <!-- Karoseri (multi gambar) -->
    <div class="mb-3">
      <label class="form-label">Karoseri (Gambar)</label>
      <input type="file" name="karoseri_gambar[]" class="form-control" accept="image/*" multiple>
      <div class="form-text">Bisa upload lebih dari 1 gambar.</div>
    </div>

    <!-- Accordion Spesifikasi -->
    <h4 class="mt-4 mb-3">Spesifikasi Produk</h4>
    <div class="accordion" id="accordionSpec">
      <?php $i=0; foreach ($spec_groups as $key => $group): $i++; ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?= $i ?>">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>">
            <?= $group['label'] ?>
          </button>
        </h2>
        <div id="collapse<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accordionSpec">
          <div class="accordion-body">
            <table class="table table-bordered">
              <?php foreach ($group['defaults'] as $field): ?>
              <tr>
                <th style="width:40%"><?= $field ?></th>
                <td><input type="text" name="spesifikasi[<?= $key ?>][<?= $field ?>]" class="form-control"></td>
              </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Tombol Submit -->
    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>

  </form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
