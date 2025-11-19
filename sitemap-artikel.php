<?php
// ===== Error Handling (aman untuk XML) =====
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/sitemap_error.log');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Header XML
header('Content-Type: application/xml; charset=utf-8');

// Output header XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Fungsi print URL
function printUrl($loc, $lastmod, $changefreq = 'monthly', $priority = '0.7') {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

// Base domain (benar)
$base_url = 'https://dealerhinoindonesia.com';

// 1️⃣ Halaman index artikel
printUrl("$base_url/artikel.php", date('Y-m-d'), 'weekly', '0.9');

try {
    // Koneksi database
    $conn = new mysqli(
        "localhost",
        "u166903321_dealerhinoidn",
        "NatanaelH1no0504@@",
        "u166903321_dealerhinoidn"
    );
    $conn->set_charset('utf8mb4');

    // Cek apakah tabel 'artikel' ada
    $res = $conn->query("SHOW TABLES LIKE 'artikel'");
    if ($res && $res->num_rows > 0) {

        $stmt = $conn->prepare("SELECT slug, tanggal FROM artikel ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $slug = trim($row['slug']);
            if ($slug === '') continue;

            $lastmod = !empty($row['tanggal'])
                ? date('Y-m-d', strtotime($row['tanggal']))
                : date('Y-m-d');

            // URL benar untuk artikel detail
            $url = $base_url . '/artikel/' . $slug;

            printUrl($url, $lastmod, 'weekly', '0.8');
        }

        $stmt->close();
    }

    $conn->close();

} catch (Throwable $e) {
    error_log("sitemap-artikel.php error: " . $e->getMessage());
}

// Tutup XML
echo "</urlset>\n";
exit;
