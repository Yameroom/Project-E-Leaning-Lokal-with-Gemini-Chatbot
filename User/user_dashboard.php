<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <style>
        /* Reset dan dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
        }
        a {
            display: inline-block;
            margin: 10px 15px;
            text-decoration: none;
            color: #3498db;
            font-weight: 600;
            font-size: 18px;
            padding: 10px 20px;
            border: 2px solid #3498db;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        a:hover {
            background-color: #3498db;
            color: white;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.6);
        }
        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
            }
            a {
                font-size: 16px;
                padding: 8px 15px;
                margin: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat datang di Dashboard!</h2>
        <a href="../index.php">Lihat Materi</a>
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>
