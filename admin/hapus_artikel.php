<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include 'config.php';

$id = (int)($_GET['id'] ?? 0);
if($id>0){
    $conn->query("DELETE FROM artikel WHERE id=$id");
}
header("Location: artikel.php?deleted=1");
exit();
