<?php
/**
 * =====================================================
 *  FILE: api_secure.php
 *  LOKASI: /admin/
 *  FUNGSI: Melindungi semua file API dari akses langsung
 *  DIBUAT UNTUK: dealerhinoindonesia.com
 * =====================================================
 */

// === DOMAIN RESMI YANG DIPERBOLEHKAN ===
$allowed_domain = 'dealerhinoindonesia.com';

// Ambil header referer & origin (jika ada)
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$origin  = $_SERVER['HTTP_ORIGIN'] ?? '';
$remote_addr = $_SERVER['REMOTE_ADDR'] ?? '';

// === CEK: Akses langsung dari browser (tanpa referer/origin) ===
if (empty($referer) && empty($origin)) {
    http_response_code(403);
    exit('403 Forbidden - Direct access not allowed');
}

// === CEK: Referer atau Origin tidak cocok dengan domain yang diizinkan ===
if (
    (!empty($referer) && !preg_match("/$allowed_domain$/", parse_url($referer, PHP_URL_HOST))) &&
    (!empty($origin) && !preg_match("/$allowed_domain$/", parse_url($origin, PHP_URL_HOST)))
) {
    http_response_code(403);
    exit('403 Forbidden - Invalid request origin');
}

// === OPTIONAL: Batasi hanya IP lokal server untuk cron/internal ===
// if ($remote_addr !== $_SERVER['SERVER_ADDR']) {
//     http_response_code(403);
//     exit('403 Forbidden - External IP denied');
// }

// === HEADER TAMBAHAN KEAMANAN ===
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// === Jika sampai di sini, berarti lolos validasi ===
// Tidak perlu echo apa-apa di sini.
?>
