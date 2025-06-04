<?php
session_start();
include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Tanpa hash
        if ($password === $user['password']) {
            if ($user['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                header('Location: admin/admin_dashboard.php');
                exit();
            } else {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                header('Location: user/user_dashboard.php');
                exit();
            }
        }
    }

    $error = 'Username atau password salah!';
}

if (isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #2c3e50;
            font-weight: 700;
            font-size: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"] {
            padding: 14px 16px;
            margin-bottom: 22px;
            border: 1.8px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #2980b9;
            box-shadow: 0 0 8px rgba(41, 128, 185, 0.4);
        }

        button {
            margin-top: 20px;
            padding: 14px 0;
            background-color: #2980b9;
            color: white;
            font-weight: 600;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(41, 128, 185, 0.5);
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1f5a90;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .register-link {
            margin-top: 28px;
            font-size: 15px;
            color: #34495e;
        }

        .register-link a {
            color: #2980b9;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #1f5a90;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 420px) {
            .login-container {
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
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar Sekarang</a>
        </div>
    </div>
</body>
</html>
