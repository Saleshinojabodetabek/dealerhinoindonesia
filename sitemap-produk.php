<?php
// MATIKAN semua output yang tidak kita kendalikan
while (ob_get_level()) { ob_end_clean(); }

header("Content-Type: application/xml; charset=UTF-8");
header("X-Robots-Tag: noindex");

// Koneksi database
$host = "localhost";
$user = "u166903321_dealerhinoidn";
$pass = "NatanaelH1no0504@@";
$db   = "u166903321_dealerhinoidn";

$conn = @new mysqli($host, $user, $pass, $db);

$base = "https://dealerhinoindonesia.com";
$today = date('Y-m-d');

// Gunakan buffer manual (tidak pakai echo)
$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Halaman statis
$static = [
    "/hino300.php",
    "/hino500.php",
    "/hinobus.php",
    "/product-detail-hino300.php",
    "/product-detail-hino500.php",
    "/product-detail-hinobus.php"
];

foreach ($static as $p) {
    $xml .= "  <url>\n";
    $xml .= "    <loc>$base$p</loc>\n";
    $xml .= "    <lastmod>$today</lastmod>\n";
    $xml .= "    <changefreq>weekly</changefreq>\n";
    $xml .= "    <priority>0.9</priority>\n";
    $xml .= "  </url>\n";
}

// Produk dinamis
if ($conn && !$conn->connect_error) {
    $check = $conn->query("SHOW TABLES LIKE 'produk'");
    if ($check && $check->num_rows) {
        $q = $conn->query("SELECT slug, updated_at FROM produk ORDER BY id DESC");
        while ($r = $q->fetch_assoc()) {

            $slug = htmlspecialchars($r['slug'], ENT_XML1);
            $last = $r['updated_at'] ? date("Y-m-d", strtotime($r['updated_at'])) : $today;

            $xml .= "  <url>\n";
            $xml .= "    <loc>$base/produk/$slug</loc>\n";
            $xml .= "    <lastmod>$last</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }
    }
}

$xml .= "</urlset>";

// KIRIM RAW XML langsung
echo $xml;

// STOP eksekusi untuk mencegah Hostinger menambah HTML
exit;
