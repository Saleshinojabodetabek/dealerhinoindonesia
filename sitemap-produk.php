<?php
error_reporting(0);
ob_clean();
header("Content-Type: application/xml; charset=utf-8");

$host = "localhost"; 
$user = "u166903321_dealerhinoidn"; 
$pass = "NatanaelH1no0504@@"; 
$db   = "u166903321_dealerhinoidn";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) exit;

$base_url = "https://dealerhinoindonesia.com";

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

function printUrl($loc, $lastmod, $changefreq = "weekly", $priority = "0.8") {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>$lastmod</lastmod>\n";
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

// ====== Halaman utama & produk utama ======
$pages = [
    "/index.php",
    "/hino300.php",
    "/hino500.php",
    "/hinobus.php",
    "/product-detail-hino300.php",
    "/product-detail-hino500.php",
    "/product-detail-hinobus.php",
    "/contact.php",
];
foreach ($pages as $p) {
    printUrl("$base_url$p", date('Y-m-d'));
}

// ====== Produk dinamis ======
if ($result = $conn->query("SHOW TABLES LIKE 'produk'")) {
    if ($result->num_rows > 0) {
        $q = $conn->query("SELECT slug, updated_at FROM produk ORDER BY id DESC");
        while ($row = $q->fetch_assoc()) {
            $slug = $row['slug'];
            $lastmod = !empty($row['updated_at']) ? date('Y-m-d', strtotime($row['updated_at'])) : date('Y-m-d');
            printUrl("$base_url/produk/$slug", $lastmod, "monthly", "0.8");
        }
    }
}

echo "</urlset>";
$conn->close();
ob_end_flush();
?>
