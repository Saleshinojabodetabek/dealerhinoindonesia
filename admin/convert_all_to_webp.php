<?php
// Konfigurasi
$rootDir = __DIR__ . "/uploads";
$quality  = 80; // kualitas webp (0-100)

// Fungsi konversi ke WebP
function convertToWebP($srcPath, $destPath, $quality = 80) {
    $info = getimagesize($srcPath);
    if (!$info) return false;

    $mime = $info['mime'];
    switch ($mime) {
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($srcPath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($srcPath);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        default:
            return false; // skip format lain
    }

    $result = imagewebp($image, $destPath, $quality);
    imagedestroy($image);
    return $result;
}

// Fungsi scan folder
function scanDirRecursive($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $files[] = $file->getPathname();
    }
    return $files;
}

// Eksekusi
$files = scanDirRecursive($rootDir);
$count = 0;
foreach ($files as $file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) continue;

    $webpFile = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);

    // Jika file WebP sudah ada, skip
    if (file_exists($webpFile)) {
        echo "Skip (sudah ada): " . basename($webpFile) . "<br>";
        continue;
    }

    // Konversi ke WebP
    if (convertToWebP($file, $webpFile, $quality)) {
        echo "OK: " . basename($file) . " → " . basename($webpFile) . "<br>";
        $count++;

        // Hapus file asli setelah konversi sukses
        if (unlink($file)) {
            echo "🗑️ Dihapus: " . basename($file) . "<br>";
        } else {
            echo "⚠️ Gagal menghapus: " . basename($file) . "<br>";
        }

    } else {
        echo "GAGAL: " . basename($file) . "<br>";
    }
}

echo "<hr>Konversi selesai. Total file baru: $count";
