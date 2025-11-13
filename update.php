<?php
/**
 * 🧩 GLOBAL WEBP REFERENCE FIXER
 * -------------------------------
 * 🔹 Scan semua file PHP/HTML di root + /admin/
 * 🔹 Ubah semua referensi gambar .webp, .webp, .webp → .webp
 * 🔹 Update langsung (overwrite)
 * 🔹 Aman untuk frontend & backend
 */

@set_time_limit(300);
@ini_set('memory_limit', '512M');

$root = __DIR__;
$totalFiles = 0;
$totalUpdated = 0;

// fungsi untuk memperbarui file
function updateReferences($file) {
    global $totalUpdated;
    $content = file_get_contents($file);
    if (!$content) return;

    // ubah semua ekstensi gambar ke .webp
    $newContent = preg_replace('/\.(jpg|jpeg|png)/i', '.webp', $content);

    if ($newContent !== $content) {
        file_put_contents($file, $newContent);
        $totalUpdated++;
        echo "<span style='color:green'>✅ Diperbarui:</span> $file<br>";
        flush(); ob_flush();
    }
}

// fungsi rekursif untuk scan folder
function scanFolder($dir) {
    global $totalFiles;

    $files = glob("$dir/*");
    foreach ($files as $f) {
        if (is_dir($f)) {
            scanFolder($f); // scan subfolder
        } else {
            if (preg_match('/\.(php|html)$/i', $f)) {
                $totalFiles++;
                updateReferences($f);
            }
        }
    }
}

echo "<h2>🔍 Memulai Update Referensi Gambar ke .webp</h2>";

// jalankan untuk root folder
scanFolder($root);

// jalankan untuk folder admin juga
$adminDir = $root . '/admin';
if (is_dir($adminDir)) {
    scanFolder($adminDir);
}

echo "<hr><b>📊 Total file dicek:</b> $totalFiles<br>";
echo "<b>✨ File yang diupdate:</b> $totalUpdated<br>";
echo "<p style='color:blue'><b>✅ Semua referensi .webp/.webp/.webp telah diganti menjadi .webp</b></p>";
?>
