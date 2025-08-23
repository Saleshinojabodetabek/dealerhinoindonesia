<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

/** Daftar grup spesifikasi */
$spec_groups = [
  'performa'     => ['label' => 'PERFORMA',     'defaults' => ['Kecepatan maksimum (km/h)', 'Daya tanjak']],
  'model_mesin'  => ['label' => 'MODEL MESIN',  'defaults' => ['Model', 'Tipe', 'Tenaga maksimum', 'Torsi maksimum', 'Kapasitas']],
  'kopling'      => ['label' => 'KOPLING',      'defaults' => ['Tipe']],
  'transmisi'    => ['label' => 'TRANSMISI',    'defaults' => ['Tipe', 'Rasio']],
  'kemudi'       => ['label' => 'KEMUDI',       'defaults' => ['Tipe']],
  'sumbu'        => ['label' => 'SUMBU',        'defaults' => ['Depan', 'Belakang']],
  'rem'          => ['label' => 'REM',          'defaults' => ['Utama', 'Parkir', 'Tambahan']],
  'roda_ban'     => ['label' => 'RODA & BAN',   'defaults' => ['Ukuran Ban']],
  'Sistim_Listrik_accu' => ['label' => 'SISTIM LISTRIK ACCU', 'defaults' => ['Accu (V-Ah)']],
  'Tangki_Solar' => ['label' => 'TANGKI SOLAR', 'defaults' => ['Kapasitas']],
  'Dimensi'      => ['label' => 'DIMENSI',      'defaults' => ['Dimensi']],
  'Suspensi'     => ['label' => 'SUSPENSI',     'defaults' => ['Depan & Belakang']],
  'Berat_Chasis' => ['label' => 'BERAT CHASIS', 'defaults' => ['Depan & Belakang']],
];

// Proses simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $series_id   = $conn->real_escape_string($_POST['series_id']);
    $varian      = $conn->real_escape_string($_POST['varian']);
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    // upload gambar utama
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // upload gambar karoseri
    $karoseri_gambar = null;
    if (!empty($_FILES['karoseri_gambar']['name'])) {
        $karoseri_gambar = "karoseri_" . time() . "_" . preg_replace('/\s+/', '_', basename($_FILES['karoseri_gambar']['name']));
        move_uploaded_file($_FILES['karoseri_gambar']['tmp_name'], $upload_dir . $karoseri_gambar);
    }

    $sql = "INSERT INTO produk (series_id, varian, nama_produk, deskripsi, gambar, karoseri_gambar)
            VALUES ('$series_id', '$varian', '$nama_produk', '$deskripsi', '$gambar', '$karoseri_gambar')";
    if ($conn->query($sql)) {
        $produk_id = $conn->insert_id;

        // simpan spesifikasi
        foreach ($spec_groups as $slug => $meta) {
            $labels = $_POST['spec'][$slug]['label'] ?? [];
            $values = $_POST['spec'][$slug]['value'] ?? [];
            for ($i = 0; $i < count($labels); $i++) {
                $label = trim($labels[$i] ?? '');
                $nilai = trim($values[$i] ?? '');
                if ($label === '' && $nilai === '') continue;
                $labelEsc = $conn->real_escape_string($label);
                $nilaiEsc = $conn->real_escape_string($nilai);
                $order    = $i + 1;
                $grup     = $conn->real_escape_string($meta['label']);
                $conn->query("INSERT INTO produk_spesifikasi (produk_id, grup, label, nilai, sort_order)
                              VALUES ($produk_id, '$grup', '$labelEsc', '$nilaiEsc', $order)");
            }
        }
        header("Location: produk.php?success=1");
        exit();
    } else {
        $error = "Gagal menyimpan produk: " . $conn->error;
    }
}
?>
