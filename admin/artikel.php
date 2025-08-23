<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background:#f8f9fa; }
.sidebar { height:100vh; background:#0d6efd; color:white; padding-top:20px; position:fixed; width:220px; text-align:center; }
.sidebar img { max-width:180px; margin-bottom:20px; }
.sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; margin:4px 0; text-align:left; transition: background .2s; }
.sidebar a:hover, .sidebar a.active { background:#0b5ed7; border-radius:6px; }
.content { margin-left:220px; padding:20px; }
.dashboard-header { background: linear-gradient(90deg,#0d6efd,#0b5ed7); color:white; padding:20px; border-radius:12px; margin-bottom:25px; }
</style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4"><img src="../images/logo3.png" alt="Logo Hino"></div>
    <a href="index.php">Dashboard</a>
    <a href="artikel.php" class="active">Artikel</a>
    <a href="produk.php">Produk</a>
    <a href="pesan.php">Pesan Customer</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="dashboard-header">
    <h2>📝 Kelola Artikel</h2>
    <p>Tambah, edit, hapus, dan lihat artikel.</p>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="tambah_artikel.php" class="btn btn-success">+ Tambah Artikel</a>
</div>

<table class="table table-bordered table-striped">
<thead class="table-primary">
<tr>
    <th>ID</th>
    <th>Judul</th>
    <th>Tanggal</th>
    <th style="width:260px">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM artikel ORDER BY id DESC";
$res = $conn->query($sql);
if($res && $res->num_rows>0){
    while($row = $res->fetch_assoc()){
        $id = (int)$row['id'];
        $judul = htmlspecialchars($row['judul']);
        $tanggal = htmlspecialchars($row['tanggal']);
        echo "<tr>
            <td>$id</td>
            <td>$judul</td>
            <td>$tanggal</td>
            <td>
                <a href='edit_artikel.php?id=$id' class='btn btn-warning btn-sm me-1'>Edit</a>
                <a href='hapus_artikel.php?id=$id' class='btn btn-danger btn-sm me-1' onclick=\"return confirm('Yakin hapus artikel ini?');\">Hapus</a>
                <a href='detail_artikel.php?id=$id' class='btn btn-info btn-sm'>Detail</a>
            </td>
        </tr>";
    }
}else{
    echo "<tr><td colspan='4' class='text-center'>Belum ada artikel</td></tr>";
}
?>
</tbody>
</table>
</div>
</body>
</html>
