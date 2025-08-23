<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include 'config.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul = $conn->real_escape_string($_POST['judul'] ?? '');
    $konten = $conn->real_escape_string($_POST['konten'] ?? '');
    $tanggal = date('Y-m-d H:i:s');

    $sql = "INSERT INTO artikel (judul,konten,tanggal) VALUES ('$judul','$konten','$tanggal')";
    if($conn->query($sql)){
        header("Location: artikel.php?success=1");
        exit();
    } else {
        $error = "Gagal menyimpan artikel: ".$conn->error;
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
<div class="card-header bg-success text-white"><h4>Tambah Artikel Baru</h4></div>
<div class="card-body">
<?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="post">
<div class="mb-3">
<label class="form-label">Judul</label>
<input type="text" name="judul" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Konten</label>
<textarea name="konten" class="form-control" rows="5" required></textarea>
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
