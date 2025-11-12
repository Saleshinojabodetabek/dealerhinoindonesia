<?php
/**
 * 🔧 PERBAIKI PATH GAMBAR SETELAH KONVERSI KE WEBP
 * ------------------------------------------------
 * Skrip ini akan:
 * - Scan semua file .php di folder website
 * - Ganti semua "src='*.jpg|png'" menjadi "src='*.webp'"
 */

set_time_limit(0);
$root = __DIR__;

function scanPhpFiles($dir) {
    $files = [];
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $path = "$dir/$f";
        if (is_dir($path)) {
            $files = array_merge($files, scanPhpFiles($path));
        } elseif (preg_match('/\.php$/i', $f)) {
            $files[] = $path;
        }
    }
    return $files;
}

$files = scanPhpFiles($root);
$total = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $newContent = preg_replace('/(\bsrc=["\'][^"\']+)\.(?:jpe?g|png)(["\'])/i', '$1.webp$2', $content);

    if ($newContent !== $content) {
        file_put_contents($file, $newContent);
        echo "✅ Path diperbarui di: $file\n";
        $total++;
    }
}

echo "\nSelesai! Total file diperbarui: $total\n";
?>
