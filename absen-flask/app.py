from flask import Flask, request, jsonify
import face_recognition
import numpy as np
import os
import base64
from io import BytesIO
from PIL import Image

app = Flask(__name__)

DATASET_DIR = "dataset"  


@app.route('/', methods=['GET'])
def home():
    return 'Flask face recognition API is running!'

def get_face_encodings(folder_path):
    encodings = []
    for filename in os.listdir(folder_path):
        path = os.path.join(folder_path, filename)
        image = face_recognition.load_image_file(path)
        face_locations = face_recognition.face_locations(image)
        if not face_locations:
            continue
        encoding = face_recognition.face_encodings(image, face_locations)[0]
        encodings.append(encoding)
    return encodings

@app.route('/recognize', methods=['POST'])
def recognize():
    print('🟡 Menerima request dari Laravel')

    data = request.json
    nama = data.get('nama_pegawai')
    base64_image = data.get('foto')

    print(f'📨 Data diterima: nama={nama}, foto_valid={bool(base64_image)}')

    if not nama or not base64_image:
        print('❌ Data tidak lengkap')
        return jsonify({'status': 'gagal', 'message': 'Data tidak lengkap'}), 400

    # Decode base64 image
    try:
        img_data = base64.b64decode(base64_image.split(',')[-1])
        img = Image.open(BytesIO(img_data))
        img_np = np.array(img.convert('RGB'))
        print('✅ Gambar berhasil diproses')
    except Exception as e:
        print(f'❌ Gagal memproses gambar: {str(e)}')
        return jsonify({'status': 'gagal', 'message': f'Gagal memproses gambar: {str(e)}'}), 400

    # Encode wajah
    try:
        face_locations = face_recognition.face_locations(img_np)
        if not face_locations:
            print('❌ Wajah tidak terdeteksi')
            return jsonify({'status': 'gagal', 'message': 'Wajah tidak terdeteksi'}), 400
        uploaded_encoding = face_recognition.face_encodings(img_np, face_locations)[0]
        print('✅ Wajah berhasil diencode')
    except Exception as e:
        print(f'❌ Gagal mengenali wajah: {str(e)}')
        return jsonify({'status': 'gagal', 'message': 'Gagal mengenali wajah'}), 400

    folder_path = os.path.join(DATASET_DIR, nama)
    if not os.path.exists(folder_path):
        print(f'❌ Folder dataset tidak ditemukan: {folder_path}')
        return jsonify({'status': 'gagal', 'message': 'Pegawai tidak ditemukan di dataset'}), 404

    known_encodings = get_face_encodings(folder_path)
    print(f'ℹ️ Jumlah dataset encoding ditemukan: {len(known_encodings)}')

    matches = face_recognition.compare_faces(known_encodings, uploaded_encoding, tolerance=3.5)

    if True in matches:
        print('✅ Wajah cocok')
        return jsonify({'status': 'berhasil', 'message': 'Wajah cocok', 'is_matched': True})
    else:
        print('❌ Wajah tidak cocok')
        return jsonify({'status': 'gagal', 'message': 'Wajah tidak cocok', 'is_matched': False})

if __name__ == '__main__':
    app.run(debug=True)
