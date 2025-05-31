<?php
include 'koneksi.php';

$success = '';
$error = '';

// Proses form saat disubmit
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // Validasi
    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "Semua kolom harus diisi.";
    } elseif ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $check = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username sudah terdaftar.";
        } else {
            // Simpan admin baru
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$username', '$hash')");
            if ($insert) {
                $success = "Akun admin berhasil dibuat!";
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Admin</title>
</head>
<body>
    <h2>Register Admin</h2>
    <a href="admin_login.php">← Kembali ke Login</a><br><br>

    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="confirm" required><br><br>

        <input type="submit" name="register" value="Daftar Admin">
    </form>
</body>
</html>
