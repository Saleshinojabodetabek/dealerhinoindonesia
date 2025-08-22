<?php
$host = "localhost"; 
$user = "u868657420_dealerhinojkt"; 
$pass = "Natanael110405"; 
$db   = "u868657420_dealerhinojkt";

$conn = new mysqli($host, $user, $pass, $db);

// cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
