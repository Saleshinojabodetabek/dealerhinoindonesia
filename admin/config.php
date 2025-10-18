<?php
$host = "localhost"; 
$user = "u429834259_dealerhinoidn"; 
$pass = "NatanaelH1no0504@@"; 
$db   = "u429834259_dealerhinoidn";

$conn = new mysqli($host, $user, $pass, $db);

// cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
