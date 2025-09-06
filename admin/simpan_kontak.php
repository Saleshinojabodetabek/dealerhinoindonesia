<?php
include 'config.php'; // karena config.php ada di folder yang sama (admin/)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['name']);
    $telepon = $conn->real_escape_string($_POST['phone']);
    $pesan = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO kontak (nama, telepon, pesan) 
            VALUES ('$nama', '$telepon', '$pesan')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Pesan berhasil dikirim!');
                window.location.href = '../contact.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
