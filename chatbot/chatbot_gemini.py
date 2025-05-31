from flask import Flask, request, jsonify
from flask_cors import CORS
import google.generativeai as genai
import traceback
import fitz  # PyMuPDF
import os
import re

app = Flask(__name__)
CORS(app)

genai.configure(api_key="Your API key")

model = genai.GenerativeModel('gemini-2.0-flash')

MATERI_FOLDER = "../materi"

def baca_isi_pdf(file_path):
    try:
        with fitz.open(file_path) as doc:
            text = ""
            for page in doc:
                text += page.get_text()
            return text.strip()
    except Exception as e:
        print(f"Error membaca PDF {file_path}: {e}")
        return ""

def cari_pdf_relevan(prompt):
    prompt_lower = prompt.lower()
    for filename in os.listdir(MATERI_FOLDER):
        if filename.endswith(".pdf") and any(word in filename.lower() for word in prompt_lower.split()):
            return os.path.join(MATERI_FOLDER, filename)
    return None

def rapikan_jawaban(text):
    # Hilangkan spasi di awal dan akhir tiap baris
    lines = [line.strip() for line in text.splitlines()]

    # Gabungkan baris yang terputus tapi bukan akhir paragraf (misal baris tidak kosong dan tidak diakhiri titik)
    paragraf_baru = []
    buffer = ""
    for line in lines:
        if line == "":
            if buffer:
                paragraf_baru.append(buffer)
                buffer = ""
            paragraf_baru.append("")
        else:
            if buffer:
                # Jika baris sebelumnya tidak berakhir dengan tanda titik atau tanda baca paragraf, gabungkan
                if not re.search(r"[.!?…:;]$", buffer):
                    buffer += " " + line
                else:
                    paragraf_baru.append(buffer)
                    buffer = line
            else:
                buffer = line
    if buffer:
        paragraf_baru.append(buffer)

    cleaned_text = "\n".join(paragraf_baru)

    # Normalisasi baris kosong: 3+ enter menjadi 2 enter
    cleaned_text = re.sub(r"\n{3,}", "\n\n", cleaned_text)

    # Pastikan nomor daftar seperti "1.", "2.", dst diikuti satu spasi dan tidak ada spasi berlebih
    cleaned_text = re.sub(r"(\d+)\.\s*", r"\1. ", cleaned_text)

    return cleaned_text.strip()

@app.route("/chat", methods=["POST"])
def chat():
    try:
        data = request.json
        prompt = data.get("prompt", "")

        if not prompt:
            return jsonify({"error": "Prompt wajib diisi."}), 400

        path_pdf = cari_pdf_relevan(prompt)
        materi_ditemukan = path_pdf is not None

        if materi_ditemukan:
            isi_pdf = baca_isi_pdf(path_pdf)
            prompt_dengan_context = f"""
Berikan jawaban dari pertanyaan berikut berdasarkan materi berikut ini:

Pertanyaan:
{prompt}

Materi (diambil dari file {os.path.basename(path_pdf)}):
\"\"\"
{isi_pdf}
\"\"\"

Berikan jawaban dengan format yang rapi dan mudah dibaca, gunakan penomoran yang konsisten jika ada daftar, hindari spasi atau indentasi berlebihan.

Jika tidak relevan, jawab seadanya dan beri tahu bahwa kamu menjawab berdasarkan pemahaman umum.
            """.strip()
        else:
            prompt_dengan_context = f"""
Jawablah pertanyaan berikut ini berdasarkan pengetahuan umum karena materi dari guru belum tersedia:

{prompt}

Berikan jawaban dengan format yang rapi dan mudah dibaca, gunakan penomoran yang konsisten jika ada daftar, hindari spasi atau indentasi berlebihan.
            """.strip()

        response = model.generate_content(prompt_dengan_context)
        jawaban = response.text.strip()

        # Rapikan hasil jawaban dari Gemini
        jawaban = rapikan_jawaban(jawaban)

        if not materi_ditemukan:
            jawaban += "\n\n⚠️ *Catatan: Materi mengenai topik ini belum diunggah oleh guru dan bersifat umum. Hubungi guru jika diperlukan.*"

        return jsonify({"response": jawaban})

    except Exception as e:
        traceback.print_exc()
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True)
