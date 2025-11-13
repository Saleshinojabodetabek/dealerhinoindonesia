<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include 'config.php';
include 'function.php'; // pastikan ada createSlug() dan uniqueSlug()

$error = '';
$kategoriList = $conn->query("SELECT * FROM kategori_artikel ORDER BY nama ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul       = $conn->real_escape_string($_POST['judul'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $isi         = $conn->real_escape_string($_POST['isi'] ?? '');
    $tanggal     = date("Y-m-d H:i:s");

    if ($judul === '' || $kategori_id <= 0) {
        $error = "Judul dan kategori wajib diisi.";
    } else {
        $gambar = null;
        if (!empty($_FILES['gambar']['name'])) {
            $upload_dir = "uploads/artikel/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
            $gambar_path = $upload_dir . $gambar;

            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar_path)) {
                $error = "Gagal mengupload gambar.";
            }
        }

        if (!$error) {
            // === Generate slug SEO-friendly & unik ===
            $slug = createSlug($judul);
            $slug = uniqueSlug($conn, $slug);

            $sql = "INSERT INTO artikel (judul, slug, kategori_id, isi, gambar, tanggal)
                    VALUES ('$judul', '$slug', $kategori_id, '$isi', ".($gambar ? "'$gambar'" : "NULL").", '$tanggal')";
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

<!-- FORM HTML sama seperti sebelumnya, tidak ada perubahan -->

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family:"Segoe UI",sans-serif; background:#f8f9fa; }
.sidebar { height:100vh; background:#0d6efd; color:white; padding-top:20px; position:fixed; width:220px; text-align:center; }
.sidebar img { max-width:180px; margin-bottom:20px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; margin:4px 0; text-align:left; transition: background 0.2s; }
.sidebar a:hover, .sidebar a.active { background:#0b5ed7; border-radius:6px; }
.content { margin-left:220px; padding:20px; }
.dashboard-header { background: linear-gradient(90deg, #0d6efd, #0b5ed7); color:white; padding:20px; border-radius:12px; margin-bottom:25px; }
.btn-primary { background:#0d6efd; border:none; }
.btn-primary:hover { background:#0b5ed7; }
</style>
</head>
<body>

<div class="sidebar">
  <div class="text-center mb-4">
    <img src="../images/logo3.webp" alt="Logo Hino">
  </div>
  <a href="index.php">Dashboard</a>
  <a href="artikel.php" class="active">Artikel</a>
  <a href="produk.php">Produk</a>
  <a href="pesan.php">Pesan Customer</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="dashboard-header">
<h2>📝 Tambah Artikel Baru</h2>
<p>Isi semua data artikel melalui form ini.</p>
</div>

<div class="card shadow">
<div class="card-body">

<?php if($error): ?>
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
