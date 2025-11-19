<?php
// ======================================
//  FIX HOSTINGER 500 ERROR
//  SAFE XML SITEMAP GENERATOR
// ======================================

// Hapus output buffer bila ada (hostinger yang aman)
if (function_exists('ob_end_clean')) { @ob_end_clean(); }

// Header XML
header("Content-Type: application/xml; charset=UTF-8");

// Database
$host = "localhost";
$user = "u166903321_dealerhinoidn";
$pass = "NatanaelH1no0504@@";
$db   = "u166903321_dealerhinoidn";

// Koneksi aman (tanpa strict mode, tanpa mysqli_report)
$conn = new mysqli($host, $user, $pass, $db);

// Base URL
$base = "https://dealerhinoindonesia.com";
$today = date('Y-m-d');

// Mulai XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
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
if (!$conn->connect_error) {

    $check = $conn->query("SHOW TABLES LIKE 'produk'");

    if ($check && $check->num_rows > 0) {
        $q = $conn->query("SELECT slug, updated_at FROM produk ORDER BY id DESC");

        if ($q) {
            while ($r = $q->fetch_assoc()) {
                $slug = htmlspecialchars($r['slug'], ENT_XML1);
                $last = !empty($r['updated_at']) ? date("Y-m-d", strtotime($r['updated_at'])) : $today;

                $xml .= "  <url>\n";
                $xml .= "    <loc>$base/produk/$slug</loc>\n";
                $xml .= "    <lastmod>$last</lastmod>\n";
                $xml .= "    <changefreq>weekly</changefreq>\n";
                $xml .= "    <priority>0.8</priority>\n";
                $xml .= "  </url>\n";
            }
        }
    }
}

$xml .= "</urlset>";

// Output XML
echo $xml;

exit;
