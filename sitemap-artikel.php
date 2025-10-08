<?php
error_reporting(0);
ob_clean();
header("Content-Type: application/xml; charset=utf-8");

$host = "localhost";
$user = "u868657420_root";
$pass = "Natanael110405";
$db   = "u868657420_db_dealer_hino";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) exit;

$base_url = "https://dealerhinoindonesia.com";

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

function printUrl($loc, $lastmod, $changefreq = "monthly", $priority = "0.7") {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

// ====== Halaman Artikel Utama ======
printUrl("$base_url/artikel.php", date('Y-m-d'), "weekly", "0.9");

// ====== Artikel Dinamis ======
if ($result = $conn->query("SHOW TABLES LIKE 'artikel'")) {
    if ($result->num_rows > 0) {
        $q = $conn->query("SELECT slug, tanggal FROM artikel ORDER BY id DESC");
        while ($row = $q->fetch_assoc()) {
            $slug = $row['slug'];
            $lastmod = !empty($row['tanggal']) ? date('Y-m-d', strtotime($row['tanggal'])) : date('Y-m-d');
            printUrl("$base_url/artikel/$slug", $lastmod);
        }
    }
}

echo "</urlset>";
$conn->close();
ob_end_flush();
?>
