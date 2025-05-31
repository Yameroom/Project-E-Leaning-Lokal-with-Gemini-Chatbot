<?php
// admin_upload.php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Feedback message
$message = "";

// Handle POST request (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_materi = $_POST['nama_materi'];
    $mapel = $_POST['mapel'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_upload = date('Y-m-d H:i:s');

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file']['name'];
        $tmp_name = $_FILES['file']['tmp_name'];
        $folder = "materi/";

        if (move_uploaded_file($tmp_name, $folder . $file)) {
            $stmt = $conn->prepare("INSERT INTO materi (nama_materi, nama_file, mapel, deskripsi, tanggal_upload) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama_materi, $file, $mapel, $deskripsi, $tanggal_upload);

            if ($stmt->execute()) {
                $message = "<p style='color:green;'>Materi berhasil diupload.</p>";
            } else {
                $message = "<p style='color:red;'>Gagal menyimpan ke database: {$stmt->error}</p>";
            }
            $stmt->close();
        } else {
            $message = "<p style='color:red;'>Gagal upload file ke server.</p>";
        }
    } else {
        $message = "<p style='color:red;'>File tidak valid.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Materi</title>
</head>
<body>
    <h2>Upload Materi</h2>

    <?= $message ?>

    <form method="POST" action="admin_upload.php" enctype="multipart/form-data">
        <label>Nama Materi:</label><br>
        <input type="text" name="nama_materi" required><br><br>

        <label>Mata Pelajaran:</label><br>
        <input type="text" name="mapel" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="4" cols="40"></textarea><br><br>

        <label>Upload File PDF:</label><br>
        <input type="file" name="file" accept=".pdf" required><br><br>

        <button type="submit">Upload Materi</button>
        <a href="admin_dashboard.php">Batal</a>
    </form>
</body>
</html>
