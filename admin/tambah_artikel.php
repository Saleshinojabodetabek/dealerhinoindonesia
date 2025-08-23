<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$error = '';

// Ambil daftar kategori
$kategoriList = $conn->query("SELECT * FROM kategori_artikel ORDER BY nama ASC");

// Proses simpan artikel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul       = $conn->real_escape_string($_POST['judul'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $isi         = $conn->real_escape_string($_POST['isi'] ?? '');
    $tanggal     = date("Y-m-d H:i:s");

    // Validasi input
    if ($judul === '' || $kategori_id <= 0) {
        $error = "Judul dan kategori wajib diisi.";
    } else {
        // Upload gambar
        $gambar = null;
        if (!empty($_FILES['gambar']['name'])) {
            $upload_dir = "../uploads/artikel/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
            $gambar_path = $upload_dir . $gambar;

            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar_path)) {
                $error = "Gagal mengupload gambar.";
            }
        }

        // Simpan ke database jika tidak ada error
        if (!$error) {
            $sql = "INSERT INTO artikel (judul, kategori_id, isi, gambar, tanggal)
                    VALUES ('$judul', $kategori_id, '$isi', ".($gambar ? "'$gambar'" : "NULL").", '$tanggal')";
            if (!$conn->query($sql)) {
                $error = "Gagal menyimpan artikel: " . $conn->error;
            } else {
                header("Location: artikel.php?success=1");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
<div class="card shadow">
<div class="card-header bg-success text-white">
<h4 class="mb-0">Tambah Artikel Baru</h4>
</div>
<div class="card-body">

<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
<div class="mb-3">
<label class="form-label">Judul Artikel</label>
<input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>">
</div>

<div class="mb-3">
<label class="form-label">Kategori</label>
<select name="kategori_id" class="form-select" required>
<option value="">-- Pilih Kategori --</option>
<?php while ($k = $kategoriList->fetch_assoc()): 
    $selected = (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $k['id']) ? 'selected' : '';
?>
<option value="<?= $k['id'] ?>" <?= $selected ?>><?= htmlspecialchars($k['nama']) ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label class="form-label">Isi Artikel</label>
<textarea name="isi" class="form-control" rows="6"><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
</div>

<div class="mb-3">
<label class="form-label">Gambar Artikel</label>
<input type="file" name="gambar" class="form-control" accept="image/*">
</div>

<div class="d-flex gap-2">
<a href="artikel.php" class="btn btn-secondary">Batal</a>
<button type="submit" class="btn btn-success">Simpan Artikel</button>
</div>
</form>

</div>
</div>
</div>
</body>
</html>
