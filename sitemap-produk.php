<?php
// Hindari BOM dan output lain
header("Content-Type: application/xml; charset=UTF-8");
error_reporting(0);

// Koneksi
$host = "localhost";
$user = "u166903321_dealerhinoidn";
$pass = "NatanaelH1no0504@@";
$db   = "u166903321_dealerhinoidn";

$conn = new mysqli($host, $user, $pass, $db);

$base_url = "https://dealerhinoindonesia.com";

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// Fungsi cetak
function printUrl($loc, $lastmod, $changefreq = "weekly", $priority = "0.8") {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

$today = date("Y-m-d");

// Halaman statis
$pages = [
    "/hino300.php",
    "/hino500.php",
    "/hinobus.php",
    "/product-detail-hino300.php",
    "/product-detail-hino500.php",
    "/product-detail-hinobus.php"
];

foreach ($pages as $p) {
    printUrl("$base_url$p", $today, "weekly", "0.9");
}

// Produk dinamis
$res = $conn->query("SHOW TABLES LIKE 'produk'");
if ($res && $res->num_rows > 0) {
    $q = $conn->query("SELECT slug, updated_at FROM produk ORDER BY id DESC");
    if ($q) {
        while ($row = $q->fetch_assoc()) {
            $slug = $row['slug'];
            $lastmod = !empty($row['updated_at'])
                ? date("Y-m-d", strtotime($row["updated_at"]))
                : $today;

            printUrl("$base_url/produk/$slug", $lastmod);
        }
    }
}

echo "</urlset>";
$conn->close();
