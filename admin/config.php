<?php
$host = "localhost"; 
$user = "u166903321_dealerhinoidn"; 
$pass = "NatanaelH1no0504@@"; 
$db   = "u166903321_dealerhinoidn";

$conn = new mysqli($host, $user, $pass, $db);

// cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
