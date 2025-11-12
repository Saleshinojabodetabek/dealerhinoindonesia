<?php
/**
 * FIXER: Bersihkan gambar duplikat dan ubah referensi ke .webp
 * Aman dijalankan berkali-kali — tidak hapus webp dan tidak ubah layout.
 */

$root = __DIR__;
$extToDelete = ['jpg', 'jpeg', 'png'];
$found = 0;
$deleted = 0;
$skipped = 0;

function scanDirRecursive($dir, $extToDelete) {
    global $found, $deleted, $skipped;

    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($path)) {
            scanDirRecursive($path, $extToDelete);
        } else {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $extToDelete)) {
                $found++;
                $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
                if (file_exists($webpPath)) {
                    // Hapus file non-webp jika sudah ada versi webp
                    unlink($path);
                    $deleted++;
                } else {
                    $skipped++;
                }
            }
        }
    }
}

scanDirRecursive($root, $extToDelete);

echo "✅ Selesai!\n";
echo "Total file gambar lama ditemukan: $found\n";
echo "Dihapus (karena sudah ada .webp): $deleted\n";
echo "Dibiarkan (belum ada .webp-nya): $skipped\n\n";

// Perbaiki link di file .php dan .html
echo "🔄 Mengupdate referensi gambar di file PHP & HTML...\n";

function replaceImageRefs($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        if (in_array($ext, ['php', 'html'])) {
            $content = file_get_contents($file->getPathname());
            $newContent = preg_replace('/\.(jpg|jpeg|png)(["\'\)])/i', '.webp$2', $content);
            if ($newContent !== $content) {
                file_put_contents($file->getPathname(), $newContent);
                echo "✔ Diperbaiki: " . $file->getPathname() . "\n";
            }
        }
    }
}

replaceImageRefs($root);

echo "\n🎉 Semua link gambar sekarang mengarah ke versi .webp!\n";
?>
