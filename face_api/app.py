from flask import Flask, request, jsonify
import face_recognition
import numpy as np
import os, cv2, base64
from io import BytesIO
from PIL import Image
from flask_cors import CORS
from datetime import datetime

app = Flask(__name__)
CORS(app)

# menambah alamat penyimpanan wajah
FACE_DIR = "faces"
os.makedirs(FACE_DIR, exist_ok=True)
# mengonversi wajah menjadi base64 agar bisa dideteksi
def base64_to_image(base64_str):
    """Konversi base64 ke image numpy array"""
    try:
        base64_data = base64_str.split(",")[1] if "," in base64_str else base64_str
        img_data = base64.b64decode(base64_data)
        img = Image.open(BytesIO(img_data))
        return np.array(img)
    except Exception as e:
        raise ValueError(f"Base64 image invalid: {e}")

# =============================
#  REGISTER FACE
# =============================
@app.route('/api/register-face', methods=['POST'])
def register_face():
    username = request.form.get('username')
    file = request.files.get('image')

    if not username or not file:
        return jsonify({'error': 'Username dan gambar wajib dikirim'}), 400

    img_path = os.path.join(FACE_DIR, f"{username}.jpg")
    file.save(img_path)
    image = face_recognition.load_image_file(img_path)
    encodings = face_recognition.face_encodings(image)

    print(f"[REGISTER] Jumlah wajah terdeteksi: {len(encodings)}")

    if not encodings:
        os.remove(img_path)
        return jsonify({'error': 'Wajah tidak terdeteksi'}), 400

    np.save(os.path.join(FACE_DIR, f"{username}.npy"), encodings[0])
    print(f"[REGISTER] Wajah {username} berhasil disimpan ke {img_path}")

    return jsonify({'message': 'Wajah berhasil diregistrasi', 'username': username}), 200

# =============================
#  RECOGNIZE FACE
# =============================
@app.route('/api/recognize-face', methods=['POST'])
def recognize_face():
    data = request.get_json(force=True, silent=True)
    if not data or 'image' not in data:
        return jsonify({'error': 'Gambar wajib dikirim'}), 400

    try:
        image = base64_to_image(data['image'])
        encodings = face_recognition.face_encodings(image)

        print(f"[SCAN] Jumlah wajah terdeteksi: {len(encodings)}")

        if not encodings:
            return jsonify({'error': 'Wajah tidak terdeteksi'}), 400

        face_encoding = encodings[0]
        best_match = None
        lowest_distance = 1.0
        tolerance = 0.6

        for filename in os.listdir(FACE_DIR):
            if filename.endswith(".npy"):
                username = filename.replace(".npy", "")
                known_encoding = np.load(os.path.join(FACE_DIR, filename))
                distance = face_recognition.face_distance([known_encoding], face_encoding)[0]

                print(f"[SCAN] Jarak dengan {username}: {distance:.3f}")

                if distance < lowest_distance:
                    lowest_distance = distance
                    best_match = username

        if best_match and lowest_distance <= tolerance:
            print(f"[SCAN] Match ditemukan: {best_match} (distance={lowest_distance:.3f})")
            return jsonify({
                'message': 'Absensi berhasil dicatat',
                'username': best_match,
                'time': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            }), 200
        else:
            print("[SCAN] Tidak ada wajah cocok.")
            return jsonify({'error': 'Wajah tidak dikenali'}), 404

    except Exception as e:
        print("[ERROR]", str(e))
        return jsonify({'error': str(e)}), 500

# =============================
#  RUN SERVER
# =============================
if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000)
