# Flask Face Recognition API (Windows + Dlib)

API sederhana berbasis Flask yang dapat mengenali wajah menggunakan `face_recognition`, `dlib`, dan `OpenCV`.

---

## 📦 Requirements

- Python 3.10 (64-bit)
- pip
- Flask
- face_recognition
- opencv-python
- scikit-learn
- dlib (dengan .whl file untuk Windows)
- gunicorn

---

## 🧠 Installasi

### 1. Buat Virtual Environment (Opsional tapi disarankan)

```bash
python -m venv venv
venv\Scripts\activate

### 2. Install gunicorn untuk deploy di linux
