import cv2
import face_recognition
import pickle
import firebase_admin
import numpy as np
from firebase_admin import credentials, db, storage
from io import BytesIO

# Initialize Firebase
cred = credentials.Certificate("ServiceAccountKey.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': "https://facerecognitionattendanc-e41e6-default-rtdb.asia-southeast1.firebasedatabase.app/",
    'storageBucket': "facerecognitionattendanc-e41e6.appspot.com"
})

# Get a reference to Firebase storage
bucket = storage.bucket()

# List all image files in the 'Images' folder on Firebase
blobs = bucket.list_blobs(prefix='Images/')

imgList = []
studentIds = []

# Iterate through each blob (image file) in the folder
for blob in blobs:
    if blob.name.endswith(".png") or blob.name.endswith(".jpg") or blob.name.endswith(".jpeg"):
        # Get the student ID from the file name (e.g., 'Images/1234.png' -> '1234')
        studentId = blob.name.split('/')[-1].split('.')[0]
        studentIds.append(studentId)
        
        # Download the image as bytes
        image_bytes = blob.download_as_bytes()

        # Convert bytes to a numpy array
        np_arr = np.frombuffer(image_bytes, np.uint8)

        # Decode the image array to OpenCV format
        img = cv2.imdecode(np_arr, cv2.IMREAD_COLOR)

        # Append the image to the imgList
        imgList.append(img)

print(studentIds)

# Function to find encodings of images
def findEncodings(imagesList):
    encodeList = []
    for img in imagesList:
        # Convert the image to RGB (required by face_recognition library)
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        encode = face_recognition.face_encodings(img)[0]
        encodeList.append(encode)

    return encodeList

print("Encoding Started ...")
encodeListKnown = findEncodings(imgList)
encodeListKnownWithIds = [encodeListKnown, studentIds]

# Save the encodings and student IDs to a pickle file
print("Encoding Complete")
file = open("EncodeFile.p", 'wb')
pickle.dump(encodeListKnownWithIds, file)
file.close()
print("File Saved")