<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID materi tidak ditemukan!";
    exit;
}

$id = (int)$_GET['id']; // casting ke int supaya aman

// Ambil data materi dulu untuk mengetahui nama file
$query = "SELECT file FROM materi WHERE id = $id"; // pastikan kolom file di DB namanya 'file'
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    echo "Materi tidak ditemukan!";
    exit;
}

$data = mysqli_fetch_assoc($result);
$nama_file = $data['file'];

// Hapus data materi dari database
$sql = "DELETE FROM materi WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    // Hapus file fisik di folder materi/
    $file_path = "../materi/" . $nama_file; // pastikan pathnya benar
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    // Redirect ke dashboard admin dengan pesan sukses
    header("Location: admin_dashboard.php?msg=hapus_sukses");
    exit;
} else {
    echo "Gagal menghapus materi: " . mysqli_error($conn);
}
?>
