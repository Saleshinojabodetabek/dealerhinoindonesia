<?php
include '../config.php';

$varian = $_GET['varian'] ?? 'ALL';

if ($varian === 'ALL') {
    $sql = "SELECT id, nama_produk, gambar FROM produk ORDER BY id DESC";
} else {
    $sql = "SELECT id, nama_produk, gambar FROM produk WHERE varian = ? ORDER BY id DESC";
}

$stmt = $conn->prepare($sql);
if ($varian !== 'ALL') {
    $stmt->bind_param("s", $varian);
}
$stmt->execute();
$result = $stmt->get_result();

$produk = [];
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}

header('Content-Type: application/json');
echo json_encode($produk);
