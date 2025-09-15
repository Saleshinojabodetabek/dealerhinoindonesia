<?php
include '../config.php';

$series_id = $_GET['series_id'] ?? null;
$varian    = $_GET['varian'] ?? 'ALL';
$search    = $_GET['search'] ?? '';

$where = [];
$params = [];
$types = '';

if ($series_id) {
    $where[] = "p.series_id = ?";
    $params[] = $series_id;
    $types   .= "i";
}

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

$sql = "SELECT p.id, p.nama_produk, p.gambar, p.slug
        FROM produk p
        $whereSql
        ORDER BY p.id DESC";

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
