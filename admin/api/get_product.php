<?php
include '../config.php';

$varian = $_GET['varian'] ?? 'ALL';
$search = $_GET['search'] ?? '';

$where = [];
$params = [];
$types = '';

if ($varian !== 'ALL') {
    $where[] = "varian = ?";
    $params[] = $varian;
    $types   .= "s";
}

if (!empty($search)) {
    $where[] = "nama_produk LIKE ?";
    $params[] = "%" . $search . "%";
    $types   .= "s";
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Ambil data produk
$sql = "SELECT id, nama_produk, gambar FROM produk $whereSql ORDER BY id DESC";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$produk = [];
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}

header('Content-Type: application/json');
echo json_encode($produk);
