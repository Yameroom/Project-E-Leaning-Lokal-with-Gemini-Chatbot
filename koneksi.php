<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "e_learning_local";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
