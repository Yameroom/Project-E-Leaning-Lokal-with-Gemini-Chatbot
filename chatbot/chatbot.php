<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pertanyaan = $_POST["question"];

    // Jalankan file Python dan ambil output-nya
    $command = escapeshellcmd("python chatbot/chatbot_gemini.py " . escapeshellarg($pertanyaan));
    $jawaban = shell_exec($command);

    // Output sebagai JSON
    echo json_encode(["response" => $jawaban]);
}
?>
