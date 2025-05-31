<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'koneksi.php';

$id = $_GET['id'] ?? 0;
$id = intval($id);
$error = '';
$data = null;

// Ambil data materi
$query = "SELECT * FROM materi WHERE id = $id";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    $error = "Data tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Materi</title>
</head>
<body>

<?php if ($error): ?>
    <p style="color: red;"><?= $error ?></p>
    <a href="admin_dashboard.php">Kembali ke Dashboard</a>
<?php else: ?>
    <h3>Edit Materi</h3>
    <form method="POST" action="admin_update.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Nama Materi:</label><br>
        <input type="text" name="nama_materi" value="<?= htmlspecialchars($data['nama_materi']) ?>" required><br><br>

        <label>Mapel:</label><br>
        <input type="text" name="mapel" value="<?= htmlspecialchars($data['mapel']) ?>" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea><br><br>

        <label>Ganti File (PDF):</label><br>
        <input type="file" name="file"><br><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="admin_dashboard.php">Batal</a>
    </form>
<?php endif; ?>

</body>
</html>
