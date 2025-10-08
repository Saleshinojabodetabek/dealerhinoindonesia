<?php
// ===== Nonaktifkan error agar XML tidak rusak =====
error_reporting(0);
@ini_set('display_errors', 0);

// ===== Koneksi database =====
$host = "localhost";
$user = "u868657420_root";
$pass = "Natanael110405";
$db   = "u868657420_db_dealer_hino";
$conn = new mysqli($host, $user, $pass, $db);

header("Content-Type: application/xml; charset=utf-8");

// ===== Jika koneksi gagal =====
if ($conn->connect_error) {
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"></urlset>";
    exit;
}

// ===== Cetak header XML =====
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// ===== Fungsi bantu =====
function printUrl($loc, $lastmod, $changefreq = "monthly", $priority = "0.7") {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

// ===== Base URL =====
$base_url = "https://dealerhinoindonesia.com";

// ===== Halaman utama artikel =====
printUrl("$base_url/artikel.php", date('Y-m-d'), "weekly", "0.9");

// ===== Ambil artikel dari database =====
if ($result = $conn->query("SHOW TABLES LIKE 'artikel'")) {
    if ($result->num_rows > 0) {
        $artikel = $conn->query("SELECT slug, tanggal FROM artikel ORDER BY id DESC");
        if ($artikel && $artikel->num_rows > 0) {
            while ($row = $artikel->fetch_assoc()) {
                $slug = trim($row['slug']);
                if ($slug !== "") {
                    $lastmod = !empty($row['tanggal'])
                        ? date('Y-m-d', strtotime($row['tanggal']))
                        : date('Y-m-d');
                    printUrl("$base_url/artikel/$slug", $lastmod);
                }
            }
        }
    }
}

// ===== Tutup XML & koneksi =====
echo "</urlset>";
$conn->close();
exit;
?>