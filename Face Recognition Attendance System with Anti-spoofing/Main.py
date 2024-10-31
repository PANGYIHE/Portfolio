import cv2
import os
import pickle
import face_recognition
import numpy as np
import cvzone
import firebase_admin
from firebase_admin import credentials
from firebase_admin import db
from firebase_admin import storage
from datetime import datetime, timedelta
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
s.setsockopt(socket.SOL_SOCKET,socket.SO_SNDBUF,1000000)

server_ip = "127.0.0.1"
server_port = 6666

cred = credentials.Certificate("ServiceAccountKey.json")
firebase_admin.initialize_app(cred, {
    'databaseURL':"https://facerecognitionattendanc-e41e6-default-rtdb.asia-southeast1.firebasedatabase.app/",
    'storageBucket':"facerecognitionattendanc-e41e6.appspot.com"
})

#---------------- Face Encode Generator -----------------------

print("---------------------------- Generating Face Encoding File... --------------------------------")

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

encodeListKnown = findEncodings(imgList)
encodeListKnownWithIds = [encodeListKnown, studentIds]

# Save the encodings and student IDs to a pickle file
print("Encoding Complete")
file = open("EncodeFile.p", 'wb')
pickle.dump(encodeListKnownWithIds, file)
file.close()
print("Encode File Saved")

#-----------------------------------------------------

print("\n-------------------- Starting Face Recognition Attendance System... ------------------------")

currentClass = db.reference(f'currentClass/').get()
print('Current Class: ' + currentClass + '\n')

cap = cv2.VideoCapture(0)
cap.set(3, 640)
cap.set(4, 480)

imgBackground = cv2.imread('Resources/background.png')

# Importing the mode images into a list
folderModePath = 'Resources/Modes'
modePathList = os.listdir(folderModePath)
imgModeList = []
for path in modePathList:
    imgModeList.append(cv2.imread(os.path.join(folderModePath, path)))

#print(len(imgModeList))

# Load the encoding file
print("Loading Encode File...")
file = open('EncodeFile.p', 'rb')
encodeListKnownWithIds = pickle.load(file)
file.close()
encodeListKnown, studentIds = encodeListKnownWithIds
# print(studentIds)
print("Encode File Loaded")

modeType = 0
counter = 0
id = -1
imgStudent = []

#---------------- Anti Spoofing -----------------------

from ultralytics import YOLO
import math
import time

confidence = 0.8
 
model = YOLO("../models/l_version_1_300.pt")
 
classNames = ["fake", "real"]

prev_frame_time = 0
new_frame_time = 0
 
while True:
    new_frame_time = time.time()
    ret, img = cap.read()
    results = model(img, stream=True)
    for r in results:
        boxes = r.boxes
        for box in boxes:
            # Confidence
            conf = math.ceil((box.conf[0] * 100)) / 100
            # Class Name
            cls = int(box.cls[0])
 
            if conf>confidence:

#-----------------------------------------------------

                imgS = cv2.resize(img,(0,0), None, 0.25, 0.25)
                imgS = cv2.cvtColor(imgS, cv2.COLOR_BGR2RGB)

                faceCurrentFrame = face_recognition.face_locations(imgS)
                encodeCurrentFrame = face_recognition.face_encodings(imgS, faceCurrentFrame)

                imgBackground[162:162+480,55:55+640] = img
                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]

                if faceCurrentFrame:
                    for encodeFace, faceLoc in zip(encodeCurrentFrame, faceCurrentFrame):
                        matches = face_recognition.compare_faces(encodeListKnown, encodeFace)
                        faceDis = face_recognition.face_distance(encodeListKnown, encodeFace)
                        # print("matches", matches)
                        # print("faceDis", faceDis)

                        matchIndex = np.argmin(faceDis)
                        # print("Match Index", matchIndex)

                        if matches[matchIndex]:
                            # print("Known Face Detected")
                            # print(studentIds[matchIndex])
                            if classNames[cls] == 'real':
                                color = (0,255,0)
                                print("Real person")
                            else:
                                color = (0,0,255)
                                print("Fake person")
                            y1, x2, y2, x1 = faceLoc
                            y1, x2, y2, x1 = y1*4, x2*4, y2*4, x1*4
                            bbox = 55+x1,162+y1, x2-x1,y2-y1
                            imgBackground = cvzone.cornerRect(imgBackground, bbox, colorC=color, colorR=color)
                            id = studentIds[matchIndex]
                            if counter == 0:
                                #cvzone.putTextRect(imgBackground,"Loading",(275,400))
                                #cv2.imshow("Face Attendance", imgBackground)
                                #cv2.waitKey(1)
                                counter = 1
                                modeType = 1
                    
                    if counter!=0 and classNames[cls] == 'real':

                        if counter ==1:
                            # Get the data
                            studentInfo = db.reference(f'{currentClass}/{id}').get()
                            print(studentInfo)
                            # Get the image from the storage
                            blob = bucket.get_blob(f'Images/{id}.png')
                            array = np.frombuffer(blob.download_as_string(), np.uint8)
                            imgStudent = cv2.imdecode(array, cv2.COLOR_BGRA2BGR)
                            # Update data of attendance
                            # Check if last_attendance_time is an empty string or None
                            if studentInfo['last_attendance_time'] == "" or studentInfo['last_attendance_time'] is None:
                                # Set current time if last_attendance_time is empty
                                datetimeObject = datetime.now() - timedelta(days=1)
                            else:
                                # Parse the existing last_attendance_time if it's not empty
                                datetimeObject = datetime.strptime(studentInfo['last_attendance_time'], "%Y-%m-%d %H:%M:%S")
                            secondsElapsed = (datetime.now()-datetimeObject).total_seconds()
                            #print(secondsElapsed)
                            if secondsElapsed > 60:
                                ref = db.reference(f'{currentClass}/{id}')
                                studentInfo['total_attendance'] += 1
                                ref.child('total_attendance').set(studentInfo['total_attendance'])
                                ref.child('last_attendance_time').set(datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
                            else:
                                modeType = 3
                                counter = 0
                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]

                        if modeType != 3:

                            if 10<counter<20:
                                modeType = 2

                            imgBackground[44:44+633,808:808+414] = imgModeList[modeType]

                            if counter<=10:

                                cv2.putText(imgBackground,str(studentInfo['total_attendance']),(861,125),
                                            cv2.FONT_HERSHEY_COMPLEX,1,(255,255,255),1)
                                cv2.putText(imgBackground,str(studentInfo['classs']),(1006,550),
                                            cv2.FONT_HERSHEY_COMPLEX,0.5,(255,255,255),1)
                                cv2.putText(imgBackground,str(id),(1006,493),
                                            cv2.FONT_HERSHEY_COMPLEX,0.5,(255,255,255),1)
                                cv2.putText(imgBackground,str(studentInfo['faculty']),(910,625),
                                            cv2.FONT_HERSHEY_COMPLEX,0.6,(100,100,100),1)
                                cv2.putText(imgBackground,str(studentInfo['grade']),(1025,625),
                                            cv2.FONT_HERSHEY_COMPLEX,0.6,(100,100,100),1)
                                cv2.putText(imgBackground,str(studentInfo['year']),(1125,625),
                                            cv2.FONT_HERSHEY_COMPLEX,0.6,(100,100,100),1)

                                (w,h), _ = cv2.getTextSize(studentInfo['name'], cv2.FONT_HERSHEY_COMPLEX,1,1)
                                offset = (414 - w) // 2
                                cv2.putText(imgBackground,str(studentInfo['name']),(808 + offset,445),
                                            cv2.FONT_HERSHEY_COMPLEX,1,(50,50,50),1)

                                imgBackground[175:175 + 216, 909:909 + 216] = imgStudent

                            counter+=1

                            if counter>=20:
                                counter = 0
                                modeType = 0
                                studentInfo = []
                                imgStudent = []
                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]
                else:
                    modeType = 0
                    counter = 0

                # cv2.imshow("Webcam", img)
                cv2.imshow("Face Attendance", imgBackground)

                ret, buffer = cv2.imencode(".jpg", img, [int(cv2.IMWRITE_JPEG_QUALITY),30])

                x_as_bytes = pickle.dumps(buffer)

                s.sendto((x_as_bytes),(server_ip,server_port))

                if cv2.waitKey(5) & 0xFF == 27:
                    break