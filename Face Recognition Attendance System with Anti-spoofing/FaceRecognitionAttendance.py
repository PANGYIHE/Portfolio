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

bucket = storage.bucket()

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

currentClass = db.reference(f'currentClass/').get()
currentSection = db.reference(f'currentSection/').get()
print('Current Class: ' + currentClass)
print('Current Section: ' + currentSection + '\n')

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

while True:
    success, img = cap.read()

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
                y1, x2, y2, x1 = faceLoc
                y1, x2, y2, x1 = y1*4, x2*4, y2*4, x1*4
                bbox = 55+x1,162+y1, x2-x1,y2-y1
                imgBackground = cvzone.cornerRect(imgBackground, bbox, rt=0)
                id = studentIds[matchIndex]
                if counter ==0:
                    #cvzone.putTextRect(imgBackground,"Loading",(275,400))
                    cv2.imshow("Face Attendance", imgBackground)
                    cv2.waitKey(1)
                    counter = 1
                    modeType = 1
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
    
    # cv2.imshow("Webcam", img)
    cv2.imshow("Face Attendance", imgBackground)
    cv2.waitKey(1)