<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    exit("<span style='color:red;'>Akses ditolak!</span>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama = $_POST['nama_materi'];
    $mapel = $_POST['mapel'];
    $deskripsi = $_POST['deskripsi'];

    $stmt_check = $conn->prepare("SELECT * FROM materi WHERE id = ?");
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows === 0) {
        exit("<span style='color:red;'>Materi tidak ditemukan.</span>");
    }

    // File upload baru
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file'];

        if ($file['type'] !== 'application/pdf') {
            exit("<span style='color:red;'>File harus berupa PDF.</span>");
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid('materi_', true) . '.' . $ext;
        $target = "materi/" . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            exit("<span style='color:red;'>Gagal upload file.</span>");
        }

        $update = $conn->prepare("UPDATE materi SET nama_materi=?, mapel=?, deskripsi=?, nama_file=? WHERE id=?");
        $update->bind_param("ssssi", $nama, $mapel, $deskripsi, $newFileName, $id);
    } else {
        // Tanpa file
        $update = $conn->prepare("UPDATE materi SET nama_materi=?, mapel=?, deskripsi=? WHERE id=?");
        $update->bind_param("sssi", $nama, $mapel, $deskripsi, $id);
    }

    if ($update->execute()) {
        echo "<span style='color:green;'>Materi berhasil diperbarui!</span>";
    } else {
        echo "<span style='color:red;'>Gagal mengupdate: " . htmlspecialchars($update->error) . "</span>";
    }

} else {
    echo "<span style='color:red;'>Permintaan tidak valid.</span>";
}
