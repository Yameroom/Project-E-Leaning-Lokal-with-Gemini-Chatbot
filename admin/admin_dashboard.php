<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

$query = "SELECT * FROM materi ORDER BY tanggal_upload DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        /* Reset dan style dasar */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 20px;
            color: #333;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        a {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
            margin-right: 15px;
        }

        a:hover {
            color: #3498db;
        }

        /* Tombol Upload Materi Baru */
        a[href="admin_upload.php"] {
            background-color: #2980b9;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a[href="admin_upload.php"]:hover {
            background-color: #3498db;
        }

        /* Tombol Logout */
        a[href="../logout.php"] {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            float: right;
            transition: background-color 0.3s ease;
        }

        a[href="../logout.php"]:hover {
            background-color: #c0392b;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f2f6fc;
        }

        tr:hover {
            background-color: #dbe9f9;
        }

        /* Link hapus konfirmasi */
        a[onclick] {
            color: #e74c3c;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <h2>Dashboard Admin</h2>
    <a href="admin_upload.php">+ Upload Materi Baru</a>
    <a href="../logout.php" style="float:right;">Logout</a>

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
            <tr><td colspan="6" style="text-align:center;">Belum ada materi.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
