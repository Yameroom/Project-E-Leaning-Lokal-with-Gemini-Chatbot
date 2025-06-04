<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

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
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Materi</title>
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #21618c;
            text-decoration: underline;
        }

        /* Container */
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        h3 {
            margin-bottom: 25px;
            color: #2c3e50;
            text-align: center;
        }

        /* Form styles */
        form label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            margin-top: 15px;
            color: #34495e;
        }
        form input[type="text"],
        form textarea,
        form input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease;
            background-color: #fafafa;
            font-family: inherit;
            resize: vertical;
        }
        form input[type="text"]:focus,
        form textarea:focus,
        form input[type="file"]:focus {
            outline: none;
            border-color: #3498db;
            background-color: #fff;
        }

        textarea {
            min-height: 100px;
        }

        /* Buttons */
        .btn-group {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        button, .btn-cancel {
            cursor: pointer;
            padding: 12px 28px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            min-width: 120px;
            text-align: center;
        }

        button {
            background-color: #3498db;
            color: white;
        }
        button:hover {
            background-color: #2980b9;
        }

        .btn-cancel {
            background-color: #e74c3c;
            color: white;
            display: inline-block;
            line-height: normal;
            text-decoration: none;
        }
        .btn-cancel:hover {
            background-color: #c0392b;
            text-decoration: none;
            color: white;
        }

        /* Error message */
        .error {
            color: #e74c3c;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            .btn-group {
                flex-direction: column;
            }
            button, .btn-cancel {
                width: 100%;
                min-width: unset;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <div style="text-align:center;">
            <a href="admin_dashboard.php" class="btn-cancel" style="padding: 10px 20px; display: inline-block;">Kembali ke Dashboard</a>
        </div>
    <?php else: ?>
        <h3>Edit Materi</h3>
        <form method="POST" action="admin_update.php" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <label for="nama_materi">Nama Materi:</label>
            <input type="text" id="nama_materi" name="nama_materi" value="<?= htmlspecialchars($data['nama_materi']) ?>" required>

            <label for="mapel">Mata Pelajaran:</label>
            <input type="text" id="mapel" name="mapel" value="<?= htmlspecialchars($data['mapel']) ?>" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>

            <label for="file">Ganti File (PDF):</label>
            <input type="file" id="file" name="file" accept=".pdf">

            <div class="btn-group">
                <button type="submit">Simpan Perubahan</button>
                <a href="admin_dashboard.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    <?php endif; ?>
    </div>
</body>
</html>
