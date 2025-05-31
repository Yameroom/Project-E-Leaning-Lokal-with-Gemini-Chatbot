<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'koneksi.php';
$query = "SELECT * FROM materi ORDER BY tanggal_upload DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Dashboard Admin</h2>
    <a href="admin_upload.php">+ Upload Materi Baru</a>
    <a href="admin_logout.php" style="float:right;">Logout</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Materi</th>
            <th>Mapel</th>
            <th>Deskripsi</th>
            <th>Tanggal Upload</th>
            <th>Aksi</th>
        </tr>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_materi']) ?></td>
                <td><?= htmlspecialchars($row['mapel']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td><?= $row['tanggal_upload'] ?></td>
                <td>
                    <a href="admin_edit_form.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="admin_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Belum ada materi.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
