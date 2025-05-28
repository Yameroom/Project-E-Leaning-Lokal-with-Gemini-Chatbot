<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID materi tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

// Ambil data materi dulu untuk mengetahui nama file
$query = "SELECT nama_file FROM materi WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Materi tidak ditemukan!";
    exit;
}

$data = mysqli_fetch_assoc($result);
$nama_file = $data['nama_file'];

// Hapus data materi dari database
$sql = "DELETE FROM materi WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    // Hapus file fisik di folder materi/
    $file_path = "materi/" . $nama_file;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    // Redirect ke dashboard admin dengan pesan sukses
    header("Location: admin_dashboard.php?msg=hapus_sukses");
    exit;
} else {
    echo "Gagal menghapus materi: " . $conn->error;
}
?>
