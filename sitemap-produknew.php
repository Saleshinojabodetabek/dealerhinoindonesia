<?php
// ===========================================
// 🔐 FINAL SITEMAP PRODUK (SUPER SAFE MODE)
// ===========================================

// Disable error output (prevent XML broken)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/sitemap-error.log');

// Set XML header
header("Content-Type: application/xml; charset=UTF-8");

// Prepare XML buffer
$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
$xml .= 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

$base  = "https://dealerhinoindonesia.com";
$today = date('Y-m-d');

// ===========================================
// 🟢 1. Static page URLs
// ===========================================
$static = [
  "/hino300.php",
  "/hino500.php",
  "/hinobus.php",
  "/product-detail-hino300.php",
  "/product-detail-hino500.php",
  "/product-detail-hinobus.php",
];

// Tambahkan static URL
foreach ($static as $p) {
  $xml .= "  <url>\n";
  $xml .= "    <loc>$base$p</loc>\n";
  $xml .= "    <lastmod>$today</lastmod>\n";
  $xml .= "    <changefreq>weekly</changefreq>\n";
  $xml .= "    <priority>0.9</priority>\n";
  $xml .= "  </url>\n";
}

// ===========================================
// 🟢 2. Dynamic Produk dari Database
// ===========================================
try {

  $conn = new mysqli(
    "localhost",
    "u166903321_dealerhinoidn",
    "NatanaelH1no0504@@",
    "u166903321_dealerhinoidn"
  );

  if (!$conn->connect_error) {

    // Cek apakah tabel produk ada
    $check = $conn->query("SHOW TABLES LIKE 'produk'");
    if ($check && $check->num_rows > 0) {

      $q = $conn->query("SELECT slug FROM produk ORDER BY id DESC");

      if ($q) {
        while ($r = $q->fetch_assoc()) {

          // Sanitize slug for XML safety
          $slug_raw = trim($r['slug']);
          if (!$slug_raw) continue;

          // Cegah karakter berbahaya di slug
          $slug = htmlspecialchars($slug_raw, ENT_XML1 | ENT_QUOTES, 'UTF-8');

          // Validasi tanggal
          $lastmod = $today;


          // Append dynamic URL
          $xml .= "  <url>\n";
          $xml .= "    <loc>$base/produk/$slug</loc>\n";
          $xml .= "    <lastmod>$lastmod</lastmod>\n";
          $xml .= "    <changefreq>weekly</changefreq>\n";
          $xml .= "    <priority>0.8</priority>\n";
          $xml .= "  </url>\n";
        }
      }
    }
    $conn->close();
  }
} catch (Throwable $e) {
  error_log("SITEMAP ERROR: " . $e->getMessage());
}

// Close XML
$xml .= "</urlset>";

// Output final XML
echo $xml;
exit;
