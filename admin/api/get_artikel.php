<?php
include "../config.php";
header('Content-Type: application/json; charset=utf-8');

// Ambil parameter
$search   = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : null;
$kategori = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : null;
$page     = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage  = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 6;

if ($page < 1) $page = 1;
if ($perPage < 1) $perPage = 6;
$offset = ($page - 1) * $perPage;

// Query dasar
$query = "FROM artikel a 
          LEFT JOIN kategori_artikel k ON a.kategori_id = k.id 
          WHERE 1=1";

// Tambahkan filter search
if (!empty($search)) {
    $searchLike = "%" . $search . "%";
    $query .= " AND (a.judul LIKE '$searchLike' OR a.konten LIKE '$searchLike' OR a.isi LIKE '$searchLike')";
}

// Filter kategori
if (!empty($kategori)) {
    $query .= " AND k.nama = '$kategori'";
}

// Hitung total data
$totalResult = $conn->query("SELECT COUNT(*) as total " . $query);
$total = $totalResult->fetch_assoc()['total'];

// Ambil data artikel dengan limit
$sql = "SELECT a.id, a.slug, a.judul, a.isi, a.konten, a.gambar, a.tanggal, k.nama AS kategori  
        " . $query . " 
        ORDER BY a.id DESC 
        LIMIT $perPage OFFSET $offset";

$result = $conn->query($sql);

$artikel = [];
while ($row = $result->fetch_assoc()) {
    $row['gambar'] = !empty($row['gambar']) 
        ? 'https://dealerhinoindonesia.com/admin/uploads/artikel/' . $row['gambar'] 
        : null;
    $artikel[] = $row;
}

// Response JSON
echo json_encode([
    "page" => $page,
    "perPage" => $perPage,
    "total" => (int)$total,
    "totalPages" => ceil($total / $perPage),
    "data" => $artikel
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
