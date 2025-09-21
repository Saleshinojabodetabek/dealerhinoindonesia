<?php
/**
 * WebP Auto Loader
 * Mengganti semua <img> jadi .webp + loading="lazy"
 */

function convertImgToWebp($html) {
    // Cari semua <img> di HTML
    return preg_replace_callback(
        '/<img([^>]+)src=["\']([^"\']+\.(jpg|jpeg|png))["\']([^>]*)>/i',
        function ($matches) {
            $before = $matches[1]; // atribut sebelum src
            $src    = $matches[2]; // URL gambar asli
            $ext    = $matches[3]; // ekstensi asli
            $after  = $matches[4]; // atribut setelah src

            // Buat versi WebP
            $webp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);

            // Pastikan ada lazy load
            if (!preg_match('/loading=/i', $before . $after)) {
                $after .= ' loading="lazy"';
            }

            // Kembalikan dalam bentuk <picture> agar ada fallback
            return '<picture>'
                . '<source srcset="' . $webp . '" type="image/webp">'
                . '<source srcset="' . $src . '" type="image/' . $ext . '">'
                . '<img ' . trim($before) . ' src="' . $src . '"' . $after . '>'
                . '</picture>';
        },
        $html
    );
}
