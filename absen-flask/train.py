import os
import cv2
import numpy as np
import pickle
from sklearn.neighbors import KNeighborsClassifier

face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

X = []
y = []

dataset_path = 'dataset'

for person_name in os.listdir(dataset_path):
    person_path = os.path.join(dataset_path, person_name)
    if not os.path.isdir(person_path):
        continue

    for img_name in os.listdir(person_path):
        img_path = os.path.join(person_path, img_name)
        img = cv2.imread(img_path)

        if img is None:
            continue

        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        faces = face_cascade.detectMultiScale(gray, 1.3, 5)

        for (x, y1, w, h) in faces:
            face = gray[y1:y1+h, x:x+w]
            face_resized = cv2.resize(face, (100, 100))
            X.append(face_resized.flatten())
            y.append(person_name)
            break

knn = KNeighborsClassifier(n_neighbors=3)
knn.fit(X, y)

with open('model.pkl', 'wb') as f:
    pickle.dump(knn, f)

print("✅ Model trained and saved as model.pkl")
