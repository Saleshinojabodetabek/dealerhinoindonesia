<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
include 'config.php';

$id = (int)($_GET['id']??0);
if($id>0){
    // Hapus file gambar
    $res = $conn->query("SELECT gambar FROM artikel WHERE id=$id");
    if($res && $res->num_rows>0){
        $row = $res->fetch_assoc();
        if($row['gambar'] && file_exists("uploads/artikel/".$row['gambar'])){
            unlink("uploads/artikel/".$row['gambar']);
        }
    }
    // Hapus artikel
    $conn->query("DELETE FROM artikel WHERE id=$id");
}

header("Location: artikel.php");
exit();
