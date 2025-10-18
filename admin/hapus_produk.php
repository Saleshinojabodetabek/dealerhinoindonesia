<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // ambil data produk dulu
    $res = $conn->query("SELECT * FROM produk WHERE id=$id");
    $produk = $res->fetch_assoc();

    if ($produk) {
        $upload_dir = "uploads/produk";

        // hapus file gambar utama
        if (!empty($produk['gambar']) && file_exists($upload_dir.$produk['gambar'])) {
            unlink($upload_dir.$produk['gambar']);
        }

        // hapus file gambar karoseri
        if (!empty($produk['karoseri_gambar']) && file_exists($upload_dir.$produk['karoseri_gambar'])) {
            unlink($upload_dir.$produk['karoseri_gambar']);
        }

        // hapus spesifikasi produk
        $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id=$id");

        // hapus produk
        $conn->query("DELETE FROM produk WHERE id=$id");
    }
}

header("Location: produk.php?deleted=1");
exit();
?>
