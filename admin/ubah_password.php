<?php
session_start();
include 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['admin'];
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Ambil data admin dari database
    $query = $conn->prepare("SELECT * FROM admin WHERE username=?");
    $query->bind_param("s", $username);
    $query->execute();
    $admin = $query->get_result()->fetch_assoc();

    // Verifikasi password lama
    if (!password_verify($password_lama, $admin['password'])) {
        $error = "Password lama salah!";
    } elseif ($password_baru !== $konfirmasi_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Update password baru
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE admin SET password=? WHERE username=?");
        $update->bind_param("ss", $password_hash, $username);
        $update->execute();

        $success = "Password berhasil diubah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ubah Password Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
  <div class="card shadow p-4" style="width:400px;">
    <h4 class="text-center mb-3">Ubah Password</h4>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Password Lama</label>
        <input type="password" name="password_lama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password Baru</label>
        <input type="password" name="password_baru" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Konfirmasi Password Baru</label>
        <input type="password" name="konfirmasi_password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
    </form>
    <div class="text-center mt-3">
      <a href="index.php">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
