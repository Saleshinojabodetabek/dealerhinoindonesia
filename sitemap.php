<?php
// ======= CONFIG DATABASE =======
$host = "localhost";
$user = "u868657420_root";
$pass = "Natanael110405";
$db   = "u868657420_db_dealer_hino";

// ======= KONEKSI DATABASE =======
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ======= HEADER UNTUK XML =======
header("Content-Type: application/xml; charset=utf-8");

// ======= BASE URL WEBSITE =======
$base_url = "https://dealerhinoindonesia.com";

// ======= FUNGSI BUAT TAG URL =======
function addUrl($loc, $lastmod, $changefreq = "weekly", $priority = "0.8") {
    return "
  <url>
    <loc>$loc</loc>
    <lastmod>$lastmod</lastmod>
    <changefreq>$changefreq</changefreq>
    <priority>$priority</priority>
  </url>";
}

// ======= MULAI XML =======
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<?php
// ======= HALAMAN UTAMA =======
echo addUrl("$base_url/", date('Y-m-d'), "daily", "1.0");

// ======= HALAMAN STATIS =======
$static_pages = [
    "/hino300.php",
    "/hino500.php",
    "/hinobus.php",
    "/blog.php",
    "/tentang-kami.php",
    "/kontak.php"
];
foreach ($static_pages as $page) {
    echo addUrl("$base_url$page", date('Y-m-d'));
}

// ======= PRODUK DETAIL =======
$produk_query = $conn->query("SELECT slug, updated_at FROM produk ORDER BY id DESC");
if ($produk_query && $produk_query->num_rows > 0) {
    while ($row = $produk_query->fetch_assoc()) {
        $slug = htmlspecialchars($row['slug']);
        $lastmod = !empty($row['updated_at']) ? date('Y-m-d', strtotime($row['updated_at'])) : date('Y-m-d');
        echo addUrl("$base_url/produk/$slug", $lastmod, "monthly", "0.8");
    }
}

// ======= ARTIKEL BLOG =======
$artikel_query = $conn->query("SELECT slug, tanggal FROM artikel ORDER BY id DESC");
if ($artikel_query && $artikel_query->num_rows > 0) {
    while ($row = $artikel_query->fetch_assoc()) {
        $slug = htmlspecialchars($row['slug']);
        $lastmod = !empty($row['tanggal']) ? date('Y-m-d', strtotime($row['tanggal'])) : date('Y-m-d');
        echo addUrl("$base_url/artikel/$slug", $lastmod, "monthly", "0.7");
    }
}
?>

</urlset>
<?php
$conn->close();
?>
