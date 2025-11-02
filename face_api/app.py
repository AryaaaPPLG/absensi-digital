from flask import Flask, request, jsonify
import face_recognition
import numpy as np
import os, cv2, base64, requests
from io import BytesIO
from PIL import Image
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# =============================
# KONFIGURASI
# =============================
FACE_DIR = "faces"
os.makedirs(FACE_DIR, exist_ok=True)

LARAVEL_API = "http://127.0.0.1:8000/api"  # ganti sesuai server Laravel kamu

# =============================
# UTILITAS
# =============================
def base64_to_image(base64_str):
    """Konversi base64 ke image numpy array"""
    try:
        base64_data = base64_str.split(",")[1] if "," in base64_str else base64_str
        img_data = base64.b64decode(base64_data)
        img = Image.open(BytesIO(img_data)).convert("RGB")
        return np.array(img)
    except Exception as e:
        raise ValueError(f"Base64 image invalid: {e}")


def save_face_encoding(user_id, encoding):
    """Simpan encoding wajah ke file .npy"""
    path = os.path.join(FACE_DIR, f"user_{user_id}.npy")
    np.save(path, encoding)
    return path


def load_face_encoding(user_id):
    """Ambil encoding wajah dari file .npy"""
    path = os.path.join(FACE_DIR, f"user_{user_id}.npy")
    if os.path.exists(path):
        return np.load(path)
    return None


# =============================
# REGISTER FACE
# =============================
@app.route('/api/register-face', methods=['POST'])
def register_face():
    try:
        data = request.get_json()
        username = data.get('username')
        image_b64 = data.get('image')
        user_id = data.get('user_id')

        if not username or not image_b64 or not user_id:
            return jsonify({'error': 'Data tidak lengkap'}), 400

        image = base64_to_image(image_b64)
        encodings = face_recognition.face_encodings(image)

        if not encodings:
            return jsonify({'error': 'Wajah tidak terdeteksi'}), 400

        encoding = encodings[0]

        # Simpan secara lokal agar cepat
        save_face_encoding(user_id, encoding)

        # Encode ke base64 biar juga dikirim ke Laravel
        encoding_b64 = base64.b64encode(encoding.tobytes()).decode('utf-8')

        # Kirim ke Laravel API
        res = requests.post(f"{LARAVEL_API}/face-encoding/save", json={
            'user_id': user_id,
            'encoding': encoding_b64
        })

        if res.status_code == 200:
            return jsonify({'message': 'Wajah berhasil diregistrasi', 'username': username}), 200
        else:
            return jsonify({'error': 'Gagal menyimpan ke Laravel', 'detail': res.text}), 500

    except Exception as e:
        return jsonify({'error': str(e)}), 500


# =============================
# RECOGNIZE FACE (VERIFIKASI)
# =============================
@app.route('/api/recognize-face', methods=['POST'])
def recognize_face():
    try:
        data = request.get_json()
        image_b64 = data.get('image')
        user_id = data.get('user_id')

        if not image_b64 or not user_id:
            return jsonify({'error': 'Data tidak lengkap'}), 400

        # Cek apakah ada file wajah di lokal
        known_encoding = load_face_encoding(user_id)

        # Kalau tidak ada, ambil dari Laravel
        if known_encoding is None:
            res = requests.get(f"{LARAVEL_API}/face-encoding/{user_id}")
            if res.status_code != 200:
                return jsonify({'error': 'Wajah belum terdaftar'}), 404

            known_encoding_b64 = res.json().get('encoding')
            known_encoding = np.frombuffer(base64.b64decode(known_encoding_b64), dtype=np.float64)
            save_face_encoding(user_id, known_encoding)

        # Deteksi wajah dari gambar baru
        image = base64_to_image(image_b64)
        encodings = face_recognition.face_encodings(image)
        if not encodings:
            return jsonify({'error': 'Wajah tidak terdeteksi'}), 400

        # Bandingkan wajah
        distance = face_recognition.face_distance([known_encoding], encodings[0])[0]
        tolerance = 0.6

        if distance <= tolerance:
            return jsonify({
                'message': 'Wajah terverifikasi',
                'verified': True,
                'distance': float(distance)
            }), 200
        else:
            return jsonify({
                'error': 'Wajah tidak cocok',
                'verified': False,
                'distance': float(distance)
            }), 401

    except Exception as e:
        return jsonify({'error': str(e)}), 500


# =============================
# RUN SERVER
# =============================
if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)
