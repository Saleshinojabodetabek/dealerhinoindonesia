<?php
include '../config.php';

$varian = $_GET['varian'] ?? 'ALL';
$search = $_GET['search'] ?? '';

$where = [];
$params = [];
$types = '';

if ($varian !== 'ALL') {
    $where[] = "p.varian = ?";
    $params[] = $varian;
    $types   .= "s";
}

if (!empty($search)) {
    $where[] = "p.nama_produk LIKE ?";
    $params[] = "%" . $search . "%";
    $types   .= "s";
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Ambil data produk + join ke series
$sql = "SELECT p.id, p.nama_produk, p.gambar, s.nama_series
        FROM produk p
        JOIN series s ON p.series_id = s.id
        $whereSql
        ORDER BY s.id, p.id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Kelompokkan berdasarkan series
$produk = [];
while ($row = $result->fetch_assoc()) {
    $series = $row['nama_series'];
    unset($row['nama_series']); // hapus biar rapih
    $produk[$series][] = $row;
}

header('Content-Type: application/json');
echo json_encode($produk, JSON_PRETTY_PRINT);
