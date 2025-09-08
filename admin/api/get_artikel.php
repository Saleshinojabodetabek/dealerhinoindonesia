<?php
include "../config.php";
header('Content-Type: application/json; charset=utf-8');

// Ambil parameter pencarian dan filter kategori
$search   = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;
$kategori = isset($_GET['kategori']) ? $conn->real_escape_string($_GET['kategori']) : null;

// Query dasar dengan JOIN ke tabel kategori_artikel
$query = "SELECT a.id, a.judul, a.isi, a.konten, a.gambar, a.tanggal, k.nama AS kategori
          FROM artikel a 
          LEFT JOIN kategori_artikel k ON a.kategori_id = k.id";

$conditions = [];

// Tambahkan kondisi jika ada search
if ($search) {
  $conditions[] = "(a.judul LIKE '$search' OR a.konten LIKE '$search' OR a.isi LIKE '$search')";
}

// Tambahkan kondisi jika ada filter kategori (berdasarkan nama kategori)
if ($kategori) {
  $conditions[] = "k.nama = '$kategori'";
}

// Gabungkan kondisi ke dalam query jika ada
if (!empty($conditions)) {
  $query .= " WHERE " . implode(" AND ", $conditions);
}

// Urutkan berdasarkan artikel terbaru
$query .= " ORDER BY a.id DESC";

$result = $conn->query($query);

$artikel = [];

// Loop hasil dan ubah URL gambar jadi lengkap
while ($row = $result->fetch_assoc()) {
  $row['gambar'] = !empty($row['gambar']) 
    ? 'https://dealerhinoindonesia.com/uploads/artikel/' . $row['gambar'] 
    : null;
  $artikel[] = $row;
}

// Kembalikan dalam format JSON
echo json_encode($artikel, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
