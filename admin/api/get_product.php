<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php';  // naik satu folder ke admin lalu ambil config.php
header("Content-Type: application/json; charset=UTF-8");

$varian = isset($_GET['varian']) ? $_GET['varian'] : 'ALL';

if ($varian == 'ALL') {
    $query = "SELECT * FROM produk ORDER BY id DESC";
} else {
    $varian = $conn->real_escape_string($varian);
    $query = "SELECT * FROM produk WHERE varian='$varian' ORDER BY id DESC";
}

$result = $conn->query($query);

$produk = [];
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}

echo json_encode($produk);
