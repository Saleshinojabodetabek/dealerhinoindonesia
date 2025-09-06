<?php
include '../config.php'; // koneksi database

header("Content-Type: application/json; charset=UTF-8");

$query = "SELECT id, nama_produk, gambar, harga, deskripsi FROM produk ORDER BY id DESC";
$result = $conn->query($query);

$produk = [];
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}

echo json_encode($produk);
