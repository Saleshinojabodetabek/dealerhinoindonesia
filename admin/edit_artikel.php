<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include 'config.php';

$id = (int)($_GET['id'] ?? 0);
if($id<=0) header("Location: artikel.php");

$res = $conn->query("SELECT * FROM artikel WHERE id=$id");
if(!$res || $res->num_rows==0) header("Location: artikel.php");

$artikel = $res->fetch_assoc();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul = $conn->real_escape_string($_POST['judul'] ?? '');
    $konten = $conn->real_escape_string($_POST['konten'] ?? '');
    $sql = "UPDATE artikel SET judul='$judul', konten='$konten' WHERE id=$id";
    if($conn->query($sql)){
        header("Location: artikel.php?success=1");
        exit();
    } else $error = "Gagal update artikel: ".$conn->error;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
<div class="card shadow">
<div class="card-header bg-success text-white"><h4>Edit Artikel</h4></div>
<div class="card-body">
<?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="post">
<div class="mb-3">
<label class="form-label">Judul</label>
<input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($artikel['judul']) ?>">
</div>
<div class="mb-3">
<label class="form-label">Konten</label>
<textarea name="konten" class="form-control" rows="5" required><?= htmlspecialchars($artikel['konten']) ?></textarea>
</div>
<div class="d-flex gap-2">
<a href="artikel.php" class="btn btn-secondary">Batal</a>
<button type="submit" class="btn btn-success">Update Artikel</button>
</div>
</form>
</div>
</div>
</div>
</body>
</html>
