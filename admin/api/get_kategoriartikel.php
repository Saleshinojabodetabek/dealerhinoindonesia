<?php
header('Content-Type: application/json; charset=utf-8');
include "../config.php"; // koneksi database

// Query ambil kategori artikel
$sql = "SELECT id, nama FROM kategori_artikel ORDER BY nama ASC";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Query gagal: " . $conn->error]);
    exit;
}

$kategori = [];

// Loop hasil query
while ($row = $result->fetch_assoc()) {
    $kategori[] = [
        "id"   => $row["id"],
        "nama" => $row["nama"]
    ];
}

// Output JSON
echo json_encode($kategori, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
