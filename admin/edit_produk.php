<?php
include "header.php";
include "sidebar.php";
include "config.php";

// Ambil ID produk dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID produk tidak ditemukan.");
}

// Ambil data produk dari database
$result = $conn->query("SELECT * FROM produk WHERE id = $id");
$produk = $result->fetch_assoc();
if (!$produk) {
    die("Produk tidak ditemukan.");
}

// Ambil data karoseri yang sudah dipilih
$karoseri_terpilih = [];
$res_karoseri = $conn->query("SELECT karoseri_id FROM produk_karoseri WHERE produk_id = $id");
while ($row = $res_karoseri->fetch_assoc()) {
    $karoseri_terpilih[] = $row['karoseri_id'];
}

// Ambil data spesifikasi produk
$spec_data = [];
$res_spec = $conn->query("SELECT * FROM produk_spesifikasi WHERE produk_id = $id");
while ($row = $res_spec->fetch_assoc()) {
    $spec_data[$row['spec_group']][] = $row;
}

// Ambil data series
$series = $conn->query("SELECT * FROM series ORDER BY nama_series");

// Ambil data karoseri
$karoseri = $conn->query("SELECT * FROM karoseri ORDER BY nama_karoseri");

// Daftar grup spesifikasi (harus sama dengan tambah_produk.php)
$spec_groups = [
    "dimensions" => [
        "title" => "Dimensions & Weight",
        "defaults" => [
            "Panjang Keseluruhan", "Lebar Keseluruhan", "Tinggi Keseluruhan",
            "Jarak Sumbu Roda", "Jarak Pijak Roda Depan", "Jarak Pijak Roda Belakang",
            "Jarak Terendah Ke Tanah", "Seating Capacity", "Curb Weight", "Gross Vehicle Weight"
        ]
    ],
    "engine" => [
        "title" => "Engine",
        "defaults" => [
            "Model", "Tipe", "Tenaga Maksimum", "Torsi Maksimum",
            "Sistem Bahan Bakar", "Isi Silinder", "Diameter x Langkah", "Jumlah Silinder"
        ]
    ],
    "transmission" => [
        "title" => "Transmission",
        "defaults" => [
            "Tipe", "Perbandingan Gigi", "Gigi 1", "Gigi 2", "Gigi 3", "Gigi 4",
            "Gigi 5", "Gigi 6", "Gigi 7", "Gigi 8", "Gigi 9", "Gigi 10", "Gigi 11", "Gigi 12"
        ]
    ],
    "chassis" => [
        "title" => "Chassis",
        "defaults" => [
            "Steering", "Suspensi Depan", "Suspensi Belakang", "Rem Depan", "Rem Belakang",
            "Sistem Rem Tambahan", "Ukuran Ban Depan", "Ukuran Ban Belakang"
        ]
    ],
    "capacity" => [
        "title" => "Capacity",
        "defaults" => [
            "Tangki Bahan Bakar", "Kapasitas Oli Mesin", "Kapasitas Radiator", "Kapasitas Kopling"
        ]
    ]
];

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);
    $karoseri_id = $_POST['karoseri_id'] ?? [];

    // Upload gambar utama
    $gambar = $produk['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        $gambar = time() . "_" . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // Update produk
    $conn->query("UPDATE produk SET 
        series_id='$series_id', 
        varian='$varian', 
        nama_produk='$nama_produk', 
        deskripsi='$deskripsi', 
        gambar='$gambar' 
        WHERE id=$id
    ");

    // Update karoseri (hapus dulu, lalu insert baru)
    $conn->query("DELETE FROM produk_karoseri WHERE produk_id=$id");
    foreach ($karoseri_id as $kid) {
        $conn->query("INSERT INTO produk_karoseri (produk_id, karoseri_id) VALUES ($id, $kid)");
    }

    // Update spesifikasi (hapus dulu, lalu insert baru)
    $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id=$id");
    foreach ($spec_groups as $slug => $group) {
        if (isset($_POST['spec'][$slug])) {
            $labels = $_POST['spec'][$slug]['label'];
            $values = $_POST['spec'][$slug]['value'];
            foreach ($labels as $i => $label) {
                $label = trim($label);
                $value = trim($values[$i]);
                if ($label && $value) {
                    $conn->query("INSERT INTO produk_spesifikasi (produk_id, spec_group, label, value) 
                                  VALUES ($id, '$slug', '".$conn->real_escape_string($label)."', '".$conn->real_escape_string($value)."')");
                }
            }
        }
    }

    header("Location: produk.php");
    exit;
}
?>

<div class="container mt-4">
    <h2>Edit Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Series</label>
            <select name="series_id" class="form-control" required>
                <?php while ($s = $series->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>" <?= $produk['series_id']==$s['id'] ? 'selected' : '' ?>>
                        <?= $s['nama_series'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Varian</label>
            <select name="varian" class="form-control" required>
                <option value="4x2" <?= $produk['varian']=='4x2' ? 'selected' : '' ?>>4x2</option>
                <option value="4x4" <?= $produk['varian']=='4x4' ? 'selected' : '' ?>>4x4</option>
                <option value="6x2" <?= $produk['varian']=='6x2' ? 'selected' : '' ?>>6x2</option>
                <option value="6x4" <?= $produk['varian']=='6x4' ? 'selected' : '' ?>>6x4</option>
                <option value="8x4" <?= $produk['varian']=='8x4' ? 'selected' : '' ?>>8x4</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Gambar Produk</label><br>
            <img src="uploads/<?= $produk['gambar'] ?>" width="150"><br>
            <input type="file" name="gambar" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label>Pilih Karoseri</label><br>
            <?php while ($k = $karoseri->fetch_assoc()): ?>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="karoseri_id[]" value="<?= $k['id'] ?>"
                           class="form-check-input" <?= in_array($k['id'], $karoseri_terpilih) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $k['nama_karoseri'] ?></label>
                </div>
            <?php endwhile; ?>
        </div>

        <h4>Spesifikasi Produk</h4>
        <?php foreach ($spec_groups as $slug => $group): ?>
            <h5><?= $group['title'] ?></h5>
            <table class="table table-bordered" id="table-<?= $slug ?>">
                <thead>
                    <tr><th>Parameter</th><th>Nilai</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php
                    $rows = $spec_data[$slug] ?? [];
                    if (empty($rows)) {
                        foreach ($group['defaults'] as $label) {
                            echo "<tr>
                                <td><input type='text' name='spec[$slug][label][]' value='".htmlspecialchars($label)."' class='form-control'></td>
                                <td><input type='text' name='spec[$slug][value][]' class='form-control'></td>
                                <td><button type='button' class='btn btn-sm btn-danger' onclick='removeRow(this)'>Hapus</button></td>
                              </tr>";
                        }
                    } else {
                        foreach ($rows as $row) {
                            echo "<tr>
                                <td><input type='text' name='spec[$slug][label][]' value='".htmlspecialchars($row['label'])."' class='form-control'></td>
                                <td><input type='text' name='spec[$slug][value][]' value='".htmlspecialchars($row['value'])."' class='form-control'></td>
                                <td><button type='button' class='btn btn-sm btn-danger' onclick='removeRow(this)'>Hapus</button></td>
                              </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-primary" onclick="addRow('<?= $slug ?>')">Tambah Baris</button>
        <?php endforeach; ?>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="produk.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function addRow(slug) {
    const tbody = document.querySelector('#table-' + slug + ' tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="spec[${slug}][label][]" class="form-control"></td>
        <td><input type="text" name="spec[${slug}][value][]" class="form-control"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Hapus</button></td>
    `;
    tbody.appendChild(tr);
    tr.querySelector("input").focus();
}
function removeRow(btn) {
    btn.closest('tr').remove();
}
</script>

<?php include "footer.php"; ?>
