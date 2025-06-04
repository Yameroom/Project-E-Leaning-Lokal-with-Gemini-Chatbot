<?php
session_start();
include 'koneksi.php'; // gunakan koneksi dari file koneksi.php

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $username     = trim($_POST['username'] ?? '');
    $kelas        = trim($_POST['kelas'] ?? '');
    $password     = trim($_POST['password'] ?? '');

    // Validasi sederhana
    if ($nama_lengkap === '' || $username === '' || $kelas === '' || $password === '') {
        $error = 'Semua field wajib diisi!';
    } else {
        // Cek username sudah ada atau belum
        $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            // Simpan user baru TANPA hash password
            $stmt = $conn->prepare("INSERT INTO user (username, nama_lengkap, kelas, password, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssss", $username, $nama_lengkap, $kelas, $password);
            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan <a href="login.php">login</a>.';
            } else {
                $error = 'Registrasi gagal. Silakan coba lagi.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Register User</title>
    <style>
        /* Reset dan styling body */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #81c784 0%, #66bb6a 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Container form */
        .register-container {
            background: #fff;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #2e7d32;
            font-weight: 700;
            font-size: 28px;
        }

        /* Form dan input */
        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"] {
            padding: 14px 16px;
            margin-bottom: 22px;
            border: 1.8px solid #a5d6a7;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #388e3c;
            box-shadow: 0 0 8px rgba(56, 142, 60, 0.4);
        }

        /* Button register */
        button {
            margin-top: 10px;
            padding: 14px 0;
            background-color: #388e3c;
            color: white;
            font-weight: 600;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(56, 142, 60, 0.6);
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2e7d32;
        }

        /* Pesan error dan success */
        .error {
            color: #d32f2f;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .success {
            color: #2e7d32;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        /* Link login */
        .login-link {
            margin-top: 28px;
            font-size: 15px;
            color: #1b5e20;
        }

        .login-link a {
            color: #388e3c;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #2e7d32;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 440px) {
            .register-container {
                padding: 30px 20px;
            }
            h2 {
                font-size: 24px;
            }
            input[type="text"],
            input[type="password"] {
                font-size: 15px;
            }
            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register User</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="kelas" placeholder="Kelas" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Masuk</a>
        </div>
    </div>
</body>
</html>
