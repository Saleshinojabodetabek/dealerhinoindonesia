<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // --- Verifikasi reCAPTCHA ---
    $recaptchaSecret = "6LcA4uQrAAAAADohWlB8BZcjVSYU0Pbx7nmjjezc";
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
        // --- Proses login jika reCAPTCHA lolos ---
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = $conn->prepare("SELECT * FROM admin WHERE username=?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['admin'] = $result['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Verifikasi reCAPTCHA gagal. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
  <div class="card shadow p-4" style="width:350px;">
    <h3 class="text-center">Login Admin</h3>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <div class="g-recaptcha" data-sitekey="6LcA4uQrAAAAAJsF0P5ymoeIstRipWwh5IQD2hBC"></div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>
