<?php
// ===== Log & error handling =====
// Tampilkan error OFF agar tidak merusak XML, tapi aktifkan logging ke file
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/sitemap_error.log');

// Biar mysqli lempar Exception pada error
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Header XML
header('Content-Type: application/xml; charset=utf-8');

// Cetak header XML (selalu)
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// fungsi bantu untuk mencetak <url>
function printUrl($loc, $lastmod, $changefreq = 'monthly', $priority = '0.7') {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

// base
$base_url = 'https://dealerhinoindonesia.com';

// selalunya kita cetak halaman index artikel
printUrl("$base_url/artikel.php", date('Y-m-d'), 'weekly', '0.9');

try {
    // koneksi DB (sesuaikan bila host bukan 'localhost')
    $host = "localhost"; 
    $user = "u429834259_dealerhinoidn"; 
    $pass = "NatanaelH1no0504@@"; 
    $db   = "u429834259_dealerhinoidn";

    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');

    // cek tabel artikel ada
    $res = $conn->query("SHOW TABLES LIKE 'artikel'");
    if ($res && $res->num_rows > 0) {
        // gunakan prepared statement untuk safety
        $stmt = $conn->prepare("SELECT slug, tanggal FROM artikel ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $slug_raw = isset($row['slug']) ? trim($row['slug']) : '';
            // fallback: jika slug kosong, buatkan dari judul bila perlu (opsional)
            if ($slug_raw === '') continue;
            $lastmod = !empty($row['tanggal']) ? date('Y-m-d', strtotime($row['tanggal'])) : date('Y-m-d');
            $url = rtrim($base_url, '/') . '/artikel/' . $slug_raw;
            printUrl($url, $lastmod, 'monthly', '0.6');
        }
        $stmt->close();
    }
    $conn->close();
} catch (Throwable $e) {
    // catat error ke file log tanpa memecah XML output
    error_log("sitemap-artikel.php caught: " . $e->getMessage());
    // tidak mencetak pesan error ke output XML (agar tetap valid)
}

// pastikan selalu menutup urlset (penting)
echo "</urlset>\n";
exit;