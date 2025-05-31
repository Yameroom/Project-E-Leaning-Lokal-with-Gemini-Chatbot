<?php
include 'koneksi.php';

// Ambil semua data materi
$query = "SELECT * FROM materi ORDER BY tanggal_upload DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Materi</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Daftar Materi Pembelajaran</h2>

    <table>
        <tr>
            <th>Nama Materi</th>
            <th>Mata Pelajaran</th>
            <th>Deskripsi</th>
            <th>Tanggal Upload</th>
            <th>File</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_materi']) ?></td>
            <td><?= htmlspecialchars($row['mapel']) ?></td>
            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
            <td><?= date('d-m-Y H:i', strtotime($row['tanggal_upload'])) ?></td>
            <td><a href="materi/<?= urlencode($row['nama_file']) ?>" target="_blank">Download</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
