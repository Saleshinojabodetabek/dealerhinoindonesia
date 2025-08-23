<?php
include 'config.php';

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit();
}

$id = (int)$_GET['id'];

// Ambil data produk
$qProduk = $conn->query("SELECT p.*, s.nama_series 
    FROM produk p
    LEFT JOIN series s ON s.id = p.series_id
    WHERE p.id = $id");
$produk = $qProduk->fetch_assoc();

if (!$produk) {
    echo "<h3>Produk tidak ditemukan.</h3>";
    exit();
}

// Ambil spesifikasi
$qSpec = $conn->query("SELECT grup, label, nilai 
    FROM produk_spesifikasi 
    WHERE produk_id = $id 
    ORDER BY grup, sort_order");

// Kelompokkan spesifikasi per grup
$specGroups = [];
while ($row = $qSpec->fetch_assoc()) {
    $specGroups[$row['grup']][] = [
        'label' => $row['label'],
        'nilai' => $row['nilai']
    ];
}

// Ambil karoseri terkait
$qKaroseri = $conn->query("SELECT k.nama, k.slug, k.series 
    FROM produk_karoseri pk
    JOIN karoseri k ON k.id = pk.karoseri_id
    WHERE pk.produk_id = $id
    ORDER BY k.series, k.nama");
$karoseriList = [];
while ($row = $qKaroseri->fetch_assoc()) {
    $karoseriList[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= htmlspecialchars($produk['nama_produk']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .karoseri-thumb {
            width: 80px;
            height: auto;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 4px;
            background: #fff;
        }
    </style>
</head>
<body class="bg-light">
<div class="container my-5">

    <a href="produk.php" class="btn btn-secondary mb-4">← Kembali ke daftar</a>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= htmlspecialchars($produk['nama_produk']); ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Series:</strong> <?= htmlspecialchars($produk['nama_series']); ?></p>
            <p><strong>Varian:</strong> <?= htmlspecialchars($produk['varian']); ?></p>
            <?php if ($produk['gambar']): ?>
                <img src="../uploads/produk/<?= htmlspecialchars($produk['gambar']); ?>" 
                     alt="<?= htmlspecialchars($produk['nama_produk']); ?>" 
                     style="max-width:300px;height:auto;" 
                     class="img-thumbnail mb-3">
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
        </div>
    </div>

    <!-- Karoseri -->
    <?php if (!empty($karoseriList)): ?>
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Karoseri Terkait</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($karoseriList as $kr): ?>
                <div class="col-6 col-md-3 mb-3 text-center">
                    <img src="../uploads/karoseri/<?= htmlspecialchars($kr['slug']); ?>.png"
                         alt="<?= htmlspecialchars($kr['nama']); ?>" 
                         class="karoseri-thumb mb-2">
                    <div><?= htmlspecialchars($kr['nama']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Spesifikasi -->
    <?php if (!empty($specGroups)): ?>
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Spesifikasi Produk</h5>
        </div>
        <div class="card-body">
            <?php foreach ($specGroups as $group => $items): ?>
                <h6 class="mt-3"><?= htmlspecialchars($group); ?></h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Parameter</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $spec): ?>
                        <tr>
                            <td><?= htmlspecialchars($spec['label']); ?></td>
                            <td><?= htmlspecialchars($spec['nilai']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
