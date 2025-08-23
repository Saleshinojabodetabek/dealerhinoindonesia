<?php
include "koneksi.php";

// proses simpan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $series_id    = (int) $_POST['series_id'];
    $kategori_id  = (int) $_POST['kategori_id'];
    $nama_produk  = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi    = $conn->real_escape_string($_POST['deskripsi']);

    // upload gambar utama
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . "." . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
    }

    // simpan produk
    $conn->query("INSERT INTO produk (series_id, kategori_id, nama_produk, deskripsi, gambar) 
                  VALUES ($series_id, $kategori_id, '$nama_produk', '$deskripsi', '$gambar')");
    $produk_id = $conn->insert_id;

    // simpan spesifikasi
    if (!empty($_POST['spesifikasi_nama'])) {
        foreach ($_POST['spesifikasi_nama'] as $i => $nama) {
            $nama  = $conn->real_escape_string($nama);
            $nilai = $conn->real_escape_string($_POST['spesifikasi_nilai'][$i]);
            if ($nama && $nilai) {
                $conn->query("INSERT INTO produk_spesifikasi (produk_id, nama, nilai) 
                              VALUES ($produk_id, '$nama', '$nilai')");
            }
        }
    }

    // simpan relasi karoseri
    if (!empty($_POST['karoseri'])) {
        foreach ($_POST['karoseri'] as $karoseri_id) {
            $kid = (int)$karoseri_id;
            $conn->query("INSERT INTO produk_karoseri (produk_id, karoseri_id) VALUES ($produk_id, $kid)");
        }
    }

    echo "<div class='alert alert-success'>Produk berhasil ditambahkan!</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

  <h2 class="mb-4">Tambah Produk</h2>
  <form method="POST" enctype="multipart/form-data">

    <!-- Series -->
    <div class="mb-3">
      <label class="form-label">Series</label>
      <select name="series_id" class="form-select" required>
        <option value="">-- Pilih Series --</option>
        <?php
        $series = $conn->query("SELECT * FROM series ORDER BY nama");
        while($s = $series->fetch_assoc()):
        ?>
          <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['nama']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Kategori -->
    <div class="mb-3">
      <label class="form-label">Kategori</label>
      <select name="kategori_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <?php
        $kategori = $conn->query("SELECT * FROM kategori ORDER BY nama");
        while($k = $kategori->fetch_assoc()):
        ?>
          <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama']); ?></option>
        <?php endwhile; ?>
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

    <!-- Upload Gambar -->
    <div class="mb-3">
      <label class="form-label">Gambar Produk</label>
      <input type="file" name="gambar" class="form-control">
    </div>

    <!-- Pilihan Karoseri -->
    <div class="mb-3">
      <label class="form-label d-block">Pilih Karoseri</label>
      <?php
      $karoseri = $conn->query("SELECT * FROM karoseri ORDER BY nama");
      while($kr = $karoseri->fetch_assoc()):
      ?>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="karoseri[]" 
                 value="<?= $kr['id']; ?>" id="karoseri<?= $kr['id']; ?>">
          <label class="form-check-label" for="karoseri<?= $kr['id']; ?>">
            <?= htmlspecialchars($kr['nama']); ?>
          </label>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Spesifikasi -->
    <div id="spesifikasi-area">
      <label class="form-label d-block">Spesifikasi</label>
      <div class="row mb-2">
        <div class="col"><input type="text" name="spesifikasi_nama[]" class="form-control" placeholder="Nama Spesifikasi"></div>
        <div class="col"><input type="text" name="spesifikasi_nilai[]" class="form-control" placeholder="Nilai"></div>
      </div>
    </div>
    <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="tambahSpesifikasi()">+ Tambah Spesifikasi</button>

    <button type="submit" class="btn btn-primary">Simpan Produk</button>
  </form>

  <script>
    function tambahSpesifikasi() {
      const area = document.getElementById('spesifikasi-area');
      const row = document.createElement('div');
      row.className = 'row mb-2';
      row.innerHTML = `
        <div class="col"><input type="text" name="spesifikasi_nama[]" class="form-control" placeholder="Nama Spesifikasi"></div>
        <div class="col"><input type="text" name="spesifikasi_nilai[]" class="form-control" placeholder="Nilai"></div>
      `;
      area.appendChild(row);
    }
  </script>

</body>
</html>
