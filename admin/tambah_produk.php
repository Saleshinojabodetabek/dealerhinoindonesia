<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Definisi grup spesifikasi
$spec_groups = [
  'performa'     => ['label' => 'PERFORMA'],
  'model_mesin'  => ['label' => 'MODEL MESIN'],
  'kopling'      => ['label' => 'KOPLING'],
  'transmisi'    => ['label' => 'TRANSMISI'],
  'kemudi'       => ['label' => 'KEMUDI'],
  'sumbu'        => ['label' => 'SUMBU'],
  'rem'          => ['label' => 'REM'],
  'roda_ban'     => ['label' => 'RODA & BAN'],
  'Sistim_Listrik_accu' => ['label' => 'SISTIM LISTRIK ACCU'],
  'Tangki_Solar' => ['label' => 'TANGKI SOLAR'],
  'Dimensi'      => ['label' => 'DIMENSI'],
  'Suspensi'     => ['label' => 'SUSPENSI'],
  'Berat_Chasis' => ['label' => 'BERAT CHASIS'],
];

$id = intval($_GET['id'] ?? 0);

// ambil data produk
$produk = $conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();
if (!$produk) {
    die("Produk tidak ditemukan");
}

// ambil spesifikasi produk
$specs = [];
$res = $conn->query("SELECT * FROM produk_spesifikasi WHERE produk_id=$id ORDER BY grup, sort_order");
while ($row = $res->fetch_assoc()) {
    $slug = strtolower(str_replace(" ", "_", $row['grup'])); // samakan slug
    $specs[$slug][] = $row;
}

// simpan perubahan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir);

    $gambar = $produk['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time()."_".basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir.$gambar);
    }

    $karoseri_gambar = $produk['karoseri_gambar'];
    if (!empty($_FILES['karoseri_gambar']['name'])) {
        $karoseri_gambar = "karoseri_".time()."_".basename($_FILES['karoseri_gambar']['name']);
        move_uploaded_file($_FILES['karoseri_gambar']['tmp_name'], $upload_dir.$karoseri_gambar);
    }

    // update produk
    $conn->query("UPDATE produk SET 
        series_id='$series_id', varian='$varian', 
        nama_produk='$nama_produk', deskripsi='$deskripsi', 
        gambar='$gambar', karoseri_gambar='$karoseri_gambar'
        WHERE id=$id
    ");

    // hapus spesifikasi lama
    $conn->query("DELETE FROM produk_spesifikasi WHERE produk_id=$id");

    // simpan ulang spesifikasi
    foreach ($spec_groups as $slug => $meta) {
        $labels = $_POST['spec'][$slug]['label'] ?? [];
        $values = $_POST['spec'][$slug]['value'] ?? [];
        for ($i=0; $i<count($labels); $i++) {
            $label = trim($labels[$i]);
            $nilai = trim($values[$i]);
            if ($label==='' && $nilai==='') continue;
            $order = $i+1;
            $grup  = $meta['label'];
            $conn->query("INSERT INTO produk_spesifikasi 
                (produk_id, grup, label, nilai, sort_order) 
                VALUES ($id, '$grup', '".$conn->real_escape_string($label)."', '".$conn->real_escape_string($nilai)."', $order)");
        }
    }

    header("Location: produk.php?updated=1");
    exit;
}
?>
