<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Ambil ID produk dari URL
if (!isset($_GET['id'])) {
    die("ID produk tidak ditemukan.");
}
$id = intval($_GET['id']);

// Ambil data produk berdasarkan ID
$sql = "SELECT * FROM produk WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Produk tidak ditemukan.");
}

$produk = $result->fetch_assoc();

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $series_id   = $_POST['series_id'];

    // cek jika upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../uploads/";
        $gambar = time() . "_" . basename($_FILES['gambar']['name']);
        $target_file = $target_dir . $gambar;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Hapus gambar lama jika ada
            if (!empty($produk['gambar']) && file_exists("../uploads/" . $produk['gambar'])) {
                unlink("../uploads/" . $produk['gambar']);
            }
            $sql_update = "UPDATE produk SET nama_produk='$nama_produk', series_id='$series_id', gambar='$gambar' WHERE id=$id";
        } else {
            echo "<div class='alert alert-danger'>Upload gambar gagal.</div>";
        }
    } else {
        $sql_update = "UPDATE produk SET nama_produk='$nama_produk', series_id='$series_id' WHERE id=$id";
    }

    if (isset($sql_update) && $conn->query($sql_update)) {
        echo "<div class='alert alert-success'>Produk berhasil diperbarui. <a href='kelola_produk.php'>Kembali</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal update produk: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">Edit Produk</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Series</label>
      <select name="series_id" class="form-select" required>
        <option value="">-- Pilih Series --</option>
        <?php
        $sql_series = "SELECT * FROM series ORDER BY nama_series";
        $result_series = $conn->query($sql_series);
        while ($row = $result_series->fetch_assoc()) {
            $selected = ($row['id'] == $produk['series_id']) ? "selected" : "";
            echo "<option value='{$row['id']}' $selected>{$row['nama_series']}</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Gambar Produk</label><br>
      <?php if (!empty($produk['gambar']) && file_exists("../uploads/" . $produk['gambar'])): ?>
        <img src="../uploads/<?= $produk['gambar'] ?>" width="120" class="mb-2"><br>
      <?php endif; ?>
      <input type="file" name="gambar" class="form-control">
      <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="kelola_produk.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
