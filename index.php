<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin/admin_dashboard.php');
    exit();
} elseif (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Materi Pembelajaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .chatbot-float-btn {
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 1000;
        }
        .chatbot-btn {
            background: #076EFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(7,110,255,0.08);
            transition: background 0.2s;
        }
        .chatbot-btn:hover {
            background: #2980b9;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #ecf0f1;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
        }

        a {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="chatbot-float-btn">
    <button class="chatbot-btn" onclick="window.open('chatbot/chatbot.html', '_blank')">
        💬 Chatbot Gemini
    </button>
</div>

<h1>Materi Pembelajaran</h1>

<ul>
    <?php
    $folder = "materi/";
    $files = scandir($folder);
    $no = 1;

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$no. <a href='" . htmlspecialchars($folder . $file) . "' target='_blank'>" . htmlspecialchars($file) . "</a></li>";
            $no++;
        }
    }
    ?>
</ul>

</body>
</html>