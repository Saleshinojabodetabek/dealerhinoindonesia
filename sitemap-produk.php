<?php
// Pastikan tidak ada spasi sebelum tag PHP pertama
ini_set('display_errors', 0);
error_reporting(0);

header("Content-Type: application/xml; charset=UTF-8");

$host = "localhost";
$user = "u166903321_dealerhinoidn";
$pass = "NatanaelH1no0504@@";
$db   = "u166903321_dealerhinoidn";

$conn = new mysqli($host, $user, $pass, $db);

$base_url = "https://dealerhinoindonesia.com";

// Tidak pakai ob_clean() lagi karena merusak XML

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// Function aman untuk mencetak URL XML
function printUrl($loc, $lastmod, $changefreq = "weekly", $priority = "0.8") {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

$today = date('Y-m-d');


// =======================================================
// 1️⃣ Halaman Produk Statis
// =======================================================
$pages = [
    "/hino300.php",
    "/hino500.php",
    "/hinobus.php",
    "/product-detail-hino300.php",
    "/product-detail-hino500.php",
    "/product-detail-hinobus.php",
];

foreach ($pages as $p) {
    printUrl("$base_url$p", $today, "weekly", "0.9");
}


// =======================================================
// 2️⃣ Produk Dinamis
// =======================================================
$res = $conn->query("SHOW TABLES LIKE 'produk'");

if ($res && $res->num_rows > 0) {

    $q = $conn->query("SELECT slug, updated_at FROM produk ORDER BY id DESC");

    if ($q) {
        while ($row = $q->fetch_assoc()) {

            $slug = $row['slug'];
            $lastmod = !empty($row['updated_at'])
                ? date('Y-m-d', strtotime($row['updated_at']))
                : $today;

            printUrl("$base_url/produk/$slug", $lastmod);
        }
    }
}

echo "</urlset>";

$conn->close();
?>
