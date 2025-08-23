<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];

// hapus produk
$conn->query("DELETE FROM produk WHERE id=$id");

header("Location: produk.php");
exit();
?>
