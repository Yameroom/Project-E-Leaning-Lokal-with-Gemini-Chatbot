CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

CREATE TABLE materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_materi VARCHAR(100) NOT NULL,
    mapel VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP
);