<?php
/**
 * KONVERSI SEMUA GAMBAR KE WEBP + HAPUS DUPLIKAT
 * ----------------------------------------------
 * - Scan semua subfolder (images, uploads, dsb)
 * - Convert JPG/JPEG/PNG -> WEBP
 * - Hapus file non-WEBP jika sudah dikonversi
 * - Hapus file duplikat berdasarkan isi file
 */

set_time_limit(0);
ini_set('memory_limit', '1024M');

$root = __DIR__;
$allowedExt = ['jpg', 'jpeg', 'png'];
$converted = 0;
$deleted = 0;

echo "🚀 Memulai konversi gambar ke WebP...\n\n";

function scanFiles($dir) {
    $result = [];
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = "$dir/$file";
        if (is_dir($path)) {
            $result = array_merge($result, scanFiles($path));
        } else {
            $result[] = $path;
        }
    }
    return $result;
}

function isDuplicate($file1, $file2) {
    return md5_file($file1) === md5_file($file2);
}

$allFiles = scanFiles($root);
$hashes = [];

foreach ($allFiles as $file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    // Skip non-image files
    if (!in_array($ext, array_merge($allowedExt, ['webp']))) continue;

    // Check duplicate by hash
    $hash = md5_file($file);
    if (isset($hashes[$hash])) {
        // Sudah ada file dengan isi yang sama
        if ($ext !== 'webp') {
            unlink($file);
            echo "🗑️  Duplikat dihapus: $file\n";
            $deleted++;
        }
        continue;
    }
    $hashes[$hash] = $file;

    // Jika bukan WebP, konversi
    if (in_array($ext, $allowedExt)) {
        $image = null;
        if ($ext === 'png') {
            $image = imagecreatefrompng($file);
        } else {
            $image = imagecreatefromjpeg($file);
        }

        if ($image) {
            $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
            if (imagewebp($image, $webpPath, 80)) {
                imagedestroy($image);
                unlink($file);
                $converted++;
                echo "✅ Berhasil convert: $file -> $webpPath\n";
            } else {
                echo "⚠️ Gagal convert: $file\n";
            }
        }
    }
}

echo "\nSelesai ✅\n";
echo "Total dikonversi: $converted file\n";
echo "Total dihapus: $deleted file\n";
?>
