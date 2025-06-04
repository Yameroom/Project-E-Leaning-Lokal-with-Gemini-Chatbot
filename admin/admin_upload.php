<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
include '../koneksi.php';

$alert = ""; // Variabel untuk menyimpan notifikasi JS

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_materi = $_POST['nama_materi'];
    $mapel = $_POST['mapel'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_upload = date('Y-m-d H:i:s');

    // File upload
    $folder = "../materi/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $file = basename($_FILES['file']['name']);
    $tmp_name = $_FILES['file']['tmp_name'];
    $target = $folder . $file;

    if (move_uploaded_file($tmp_name, $target)) {
        $stmt = $conn->prepare("INSERT INTO materi (nama_materi, mapel, deskripsi, tanggal_upload, file) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama_materi, $mapel, $deskripsi, $tanggal_upload, $file);
        if ($stmt->execute()) {
            $alert = "success|Materi berhasil diupload!";
        } else {
            $alert = "error|Gagal menyimpan ke database.";
        }
        $stmt->close();
    } else {
        $alert = "error|Gagal upload file ke server.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Materi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            font-size: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .cancel-link {
            display: inline-block;
            margin-left: 15px;
            color: #e74c3c;
            text-decoration: none;
            font-size: 15px;
        }

        .cancel-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }
            .container {
                padding: 20px;
            }
            button, .cancel-link {
                width: 100%;
                display: block;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Materi</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Materi:</label>
            <input type="text" name="nama_materi" required>

            <label>Mata Pelajaran:</label>
            <input type="text" name="mapel" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4"></textarea>

            <label>Upload File PDF:</label>
            <input type="file" name="file" accept=".pdf" required>

            <button type="submit">Upload Materi</button>
            <a class="cancel-link" href="admin_dashboard.php">Batal</a>
        </form>
    </div>

    <?php if ($alert): 
        list($type, $msg) = explode('|', $alert);
    ?>
    <script>
        Swal.fire({
            icon: '<?= $type ?>',
            title: '<?= $type === "success" ? "Berhasil" : "Gagal" ?>',
            text: '<?= $msg ?>'
        });
    </script>
    <?php endif; ?>
</body>
</html>
