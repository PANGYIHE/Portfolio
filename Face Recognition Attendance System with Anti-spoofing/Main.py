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
        # Get the student ID from the file name
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

currentClass = db.reference(f'CurrentClass/classCode').get()
currentSection = db.reference(f'CurrentClass/classSection').get()
print('Current Class: ' + currentClass)
print('Current Section: ' + currentSection + '\n')

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
fakePerson = True

#---------------- Anti Spoofing -----------------------

from ultralytics import YOLO
import math

confidence = 0.8
 
model = YOLO("../models/best.pt") #
 
classNames = ["fake", "real"] #THIS IS FOR 
 
while True:
    success, img = cap.read()
    imgBackground[162:162+480,55:55+640] = img
    imgBackground[44:44+633,808:808+414] = imgModeList[modeType]

    results = model(img, stream=True)
    for r in results:
        boxes = r.boxes
        for box in boxes:
            # Bounding Box
            x1, y1, x2, y2 = box.xyxy[0]
            x1, y1, x2, y2 = int(x1), int(y1), int(x2), int(y2)
            # cv2.rectangle(img,(x1,y1),(x2,y2),(255,0,255),3)
            w, h = x2 - x1, y2 - y1
            bbox = 55+x1,162+y1, x2-x1,y2-y1
            # Confidence
            conf = math.ceil((box.conf[0] * 100)) / 100
            # Class Name
            cls = int(box.cls[0])
 
            if conf>confidence:
                if classNames[cls] == 'real':
                    color = (0,255,0)
                    print("Real person")
                elif classNames[cls] == 'fake':
                    color = (0,0,255)
                    print("Fake person")
                imgBackground = cvzone.cornerRect(imgBackground, bbox, colorC=color, colorR=color) 
                               
                
#-----------------------------------------------------
            
                # Crop the image to the bounding box
                cropped_img = img[y1:y2, x1:x2]

                # Resize the cropped image
                imgS = cv2.resize(cropped_img, (0, 0), None, 0.25, 0.25)
                imgS = cv2.cvtColor(imgS, cv2.COLOR_BGR2RGB)

                faceCurrentFrame = face_recognition.face_locations(imgS)
                encodeCurrentFrame = face_recognition.face_encodings(imgS, faceCurrentFrame)

                if faceCurrentFrame:
                    for encodeFace, faceLoc in zip(encodeCurrentFrame, faceCurrentFrame):
                        matches = face_recognition.compare_faces(encodeListKnown, encodeFace)
                        faceDis = face_recognition.face_distance(encodeListKnown, encodeFace)
                        # print("matches", matches)
                        # print("faceDis", faceDis)

                        matchIndex = np.argmin(faceDis)
                        # print("Match Index", matchIndex)

                        if matches[matchIndex]:
                            id = studentIds[matchIndex]
                            if counter == 0 and classNames[cls] == 'real':
                                #cvzone.putTextRect(imgBackground,"Loading",(275,400))
                                counter = 1
                                modeType = 1
                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]
                            elif counter == 0 and classNames[cls] == 'fake':
                                counter = 0
                                modeType = 4
                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]

                    if counter!=0:

                        if counter ==1:
                            # Get the data
                            studentInfo = db.reference(f'ClassList/{currentClass}/{id}').get()
                            # print(studentInfo)
                            # Get the image from the storage
                            blob = bucket.get_blob(f'Images/{id}.png')
                            array = np.frombuffer(blob.download_as_string(), np.uint8)
                            imgStudent = cv2.imdecode(array, cv2.COLOR_BGRA2BGR)
                            # Update data of attendance
                            # Check if last_attendance_time is an empty string or None
                            if currentSection == "Lecturer":
                                if studentInfo['lect_last_attendance'] == "" or studentInfo['lect_last_attendance'] is None:
                                # Set current time if last_attendance_time is empty
                                    datetimeObject = datetime.now() - timedelta(days=1)
                                else:
                                    datetimeObject = datetime.strptime(studentInfo['lect_last_attendance'], "%Y-%m-%d %H:%M:%S")
                            elif currentSection == "Lab":
                                if studentInfo['lab_last_attendance'] == "" or studentInfo['lab_last_attendance'] is None:
                                # Set current time if last_attendance_time is empty
                                    datetimeObject = datetime.now() - timedelta(days=1)
                                else:
                                    datetimeObject = datetime.strptime(studentInfo['lab_last_attendance'], "%Y-%m-%d %H:%M:%S")
                            secondsElapsed = (datetime.now()-datetimeObject).total_seconds()
                            # print(secondsElapsed)
                            if secondsElapsed > 60:
                                if currentSection == "Lecturer":
                                    ref = db.reference(f'ClassList/{currentClass}/{id}')
                                elif currentSection == "Lab":
                                    ref = db.reference(f'ClassList/{currentClass}/{id}')
                            else:
                                modeType = 3
                                counter = 0
                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]

                        if modeType != 3:

                            if 10<counter<17:
                                modeType = 2

                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]
                                counter+=1

                            if counter<=10:

                                if currentSection == "Lecturer":
                                    cv2.putText(imgBackground,str(studentInfo['lect_attendance']),(820,172),
                                                cv2.FONT_HERSHEY_DUPLEX,0.75,(255,255,255),1)
                                    cv2.putText(imgBackground,str(studentInfo['lectSec']),(1003,525),
                                            cv2.FONT_HERSHEY_DUPLEX,0.5,(255,255,255),1)
                                elif currentSection == "Lab":
                                    cv2.putText(imgBackground,str(studentInfo['lab_attendance']),(820,172),
                                                cv2.FONT_HERSHEY_DUPLEX,0.75,(255,255,255),1)
                                    cv2.putText(imgBackground,str(studentInfo['labSec']),(1003,526),
                                            cv2.FONT_HERSHEY_DUPLEX,0.5,(255,255,255),1)
                                cv2.putText(imgBackground,str(studentInfo['classCode']),(1003,469),
                                            cv2.FONT_HERSHEY_DUPLEX,0.5,(255,255,255),1)
                                cv2.putText(imgBackground,str(studentInfo['id']),(1003,412),
                                            cv2.FONT_HERSHEY_DUPLEX,0.5,(255,255,255),1)

                                (w,h), _ = cv2.getTextSize(studentInfo['name'], cv2.FONT_HERSHEY_DUPLEX,1,1)
                                offset = (414 - w) // 2
                                cv2.putText(imgBackground,str(studentInfo['name']),(808 + offset,368),
                                            cv2.FONT_HERSHEY_DUPLEX,1,(50,50,50),1)

                                imgBackground[99:99 + 216, 907:907 + 216] = imgStudent

                                counter = 2
                                # Callback function for mouse events 
                                def click_event(event, x, y, flags, param): 
                                    global counter
                                    global ref
                                    if event == cv2.EVENT_LBUTTONDOWN: 
                                        # Check if the click is within the button area 
                                        if 893 < x < 1000 and 597 < y < 634:
                                            print("Yes")
                                            counter = 11
                                            if currentSection == "Lecturer":
                                                studentInfo['lect_attendance'] += 1
                                                ref.child('lect_attendance').set(studentInfo['lect_attendance'])
                                                ref.child('lect_last_attendance').set(datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
                                            elif currentSection == "Lab":
                                                studentInfo['lab_attendance'] += 1
                                                ref.child('lab_attendance').set(studentInfo['lab_attendance'])
                                                ref.child('lab_last_attendance').set(datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
                                        elif 1029 < x < 1136 and 597 < y < 634: 
                                            print("No")
                                            modeType = 0
                                            imgBackground[44:44+633,808:808+414] = imgModeList[modeType]
                                            counter = 0

                                # Draw a semi-transparent rectangle (button)
                                # overlay = imgBackground.copy()  # Copy the original image for overlay
                                # cv2.rectangle(overlay, (893, 597), (1000, 634), (255, 0, 0), -1)  # Draw the solid rectangle on the overlay
                                # cv2.rectangle(overlay, (1029, 597), (1136, 634), (255, 0, 0), -1)

                                # alpha = 0.5  # Transparency factor
                                # cv2.addWeighted(overlay, alpha, imgBackground, 1 - alpha, 0, imgBackground)  # Blend the overlay with the background

                                # Set the mouse callback 
                                cv2.namedWindow('Face Attendance')
                                cv2.setMouseCallback('Face Attendance', click_event)

                            if counter>=17:
                                counter = 0
                                modeType = 0
                                studentInfo = []
                                imgStudent = []
                                imgBackground[44:44+633,808:808+414] = imgModeList[modeType]
                else:
                    modeType = 0
                    counter = 0

        cv2.imshow("Face Attendance", imgBackground)
        cv2.waitKey(1)