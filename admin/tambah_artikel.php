<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include 'config.php';

if(!$conn->query($sql)){
    die("Gagal menyimpan artikel: ".$conn->error);
}


// Ambil kategori
$kategoriList = $conn->query("SELECT * FROM kategori_artikel ORDER BY nama");

// Proses simpan
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $judul = $conn->real_escape_string($_POST['judul'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $isi = $conn->real_escape_string($_POST['isi'] ?? '');
    $tanggal = date('Y-m-d H:i:s');

    $gambar = null;
    if(!empty($_FILES['gambar']['name'])){
        $upload_dir = "../uploads/artikel/";
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $gambar = time().'_'.preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir.$gambar);
    }

    $sql = "INSERT INTO artikel (judul, kategori_id, isi, gambar, tanggal) 
            VALUES ('$judul', $kategori_id, '$isi', ".($gambar ? "'$gambar'" : "NULL").", '$tanggal')";
    if($conn->query($sql)){
        header("Location: artikel.php?success=1");
        exit();
    } else $error = "Gagal menyimpan artikel: ".$conn->error;
}
?>
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
.card-header { background:#198754; color:white; }
</style>
</head>
<body>
<div class="sidebar">
  <div class="text-center mb-4">
    <img src="../images/logo3.png" alt="Logo Hino">
  </div>
  <a href="index.php">Dashboard</a>
  <a href="artikel.php" class="active">Artikel</a>
  <a href="produk.php">Produk</a>
  <a href="pesan.php">Pesan Customer</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="card shadow">
<div class="card-header"><h4>Tambah Artikel</h4></div>
<div class="card-body">
<?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="post" enctype="multipart/form-data">
<div class="mb-3">
<label class="form-label">Judul</label>
<input type="text" name="judul" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Kategori</label>
<select name="kategori_id" class="form-select" required>
<option value="">-- Pilih Kategori --</option>
<?php while($k=$kategoriList->fetch_assoc()): ?>
<option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="mb-3">
<label class="form-label">Isi Artikel</label>
<textarea name="isi" class="form-control" rows="5"></textarea>
</div>
<div class="mb-3">
<label class="form-label">Gambar</label>
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
