<?php
include 'admin/config.php';

$id = intval($_GET['id']);
$query = "SELECT * FROM produk WHERE id = $id";
$result = $conn->query($query);
$produk = $result->fetch_assoc();
?>

<h1><?= $produk['nama_produk']; ?></h1>
<img src="uploads/<?= $produk['gambar']; ?>" alt="<?= $produk['nama_produk']; ?>">
<p><?= nl2br($produk['deskripsi']); ?></p>

<h2>Spesifikasi</h2>
<p><?= nl2br($produk['spesifikasi']); ?></p>

<?php if (!empty($produk['karoseri_gambar'])): ?>
    <h2>Karoseri</h2>
    <img src="uploads/<?= $produk['karoseri_gambar']; ?>" alt="Karoseri <?= $produk['nama_produk']; ?>">
<?php endif; ?>
