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

<h1>Materi Pembelajaran</h1>

<ul>
    <?php
    $folder = "materi/";
    $files = scandir($folder);
    $no = 1;

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$no. <a href='$folder$file' target='_blank'>" . htmlspecialchars($file) . "</a></li>";
            $no++;
        }
    }
    ?>
</ul>

</body>
</html>
