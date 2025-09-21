<?php
/**
 * WebP Auto Loader
 * Ganti semua <img> jadi <picture> dengan .webp + fallback + lazy load
 */
function convertImgToWebp($html) {
    return preg_replace_callback(
        '/<img([^>]+)src=["\']([^"\']+\.(jpe?g|png))["\']([^>]*)>/i',
        function ($matches) {
            $before = trim($matches[1]); // atribut sebelum src
            $src    = $matches[2];       // URL gambar asli
            $ext    = strtolower($matches[3]); // ekstensi (jpg/jpeg/png)
            $after  = trim($matches[4]); // atribut setelah src

            // Buat versi WebP (ubah ekstensi)
            $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $src);

            // Tambah lazy load kalau belum ada
            if (!preg_match('/loading=/i', $before . $after)) {
                $after .= ' loading="lazy"';
            }

            // Hindari <img> duplikat atribut spasi ganda
            $before = $before ? $before . ' ' : '';
            $after  = $after ? ' ' . $after : '';

            // Bangun kembali dengan <picture>
            return '<picture>'
                . '<source srcset="' . htmlspecialchars($webp, ENT_QUOTES) . '" type="image/webp">'
                . '<source srcset="' . htmlspecialchars($src, ENT_QUOTES) . '" type="image/' . $ext . '">'
                . '<img ' . $before . 'src="' . htmlspecialchars($src, ENT_QUOTES) . '"' . $after . '>'
                . '</picture>';
        },
        $html
    );
}
