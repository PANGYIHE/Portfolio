import firebase_admin
from firebase_admin import credentials
from firebase_admin import db

cred = credentials.Certificate("ServiceAccountKey.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': "https://facerecognitionattendanc-e41e6-default-rtdb.asia-southeast1.firebasedatabase.app/"
})

ref = db.reference('/')
ref.update({"currentClass": "BCM3233"})

refC = db.reference('ClassList/')

ref1 = refC.child('BCM3233')
data1 = {
    "CD21074": {
        "id": "CD21074",
        "name": "PANG YI HE",
        "faculty": "FKOM",
        "classs": "BCM3233",
        "lectSec": "01",
        "labSec": "01B",
        "lect_attendance": 10,
        "lab_attendance": 9,
        "lect_last_attendance": "2024-9-1 10:05:30",
        "lab_last_attendance": "2024-9-1 10:05:30"
    },
    "CD21068": {
        "id": "CD21068",
        "name": "SOON WEI YE",
        "faculty": "FTKEE",
        "classs": "BCM3233",
        "lectSec": "02",
        "labSec": "02C",
        "lect_attendance": 9,
        "lab_attendance": 9,
        "lect_last_attendance": "2024-9-3 12:05:30",
        "lab_last_attendance": "2024-9-1 10:05:30"
    }
}

for key, value in data1.items():
    ref1.child(key).set(value)

ref2 = refC.child('BCM3103')
data2 = {
    "CD21074": {
        "id": "CD21074",
        "name": "PANG YI HE",
        "faculty": "FKOM",
        "classs": "BCM3103",
        "lectSec": "01",
        "labSec": "01A",
        "lect_attendance": 8,
        "lab_attendance": 9,
        "lect_last_attendance": "2024-9-1 10:05:30",
        "lab_last_attendance": "2024-9-1 10:05:30"
    },
    "CD21068": {
        "id": "CD21068",
        "name": "SOON WEI YE",
        "faculty": "FTKEE",
        "classs": "BCM3103",
        "lectSec": "02",
        "labSec": "02B",
        "lect_attendance": 7,
        "lab_attendance": 9,
        "lect_last_attendance": "2024-9-3 12:05:30",
        "lab_last_attendance": "2024-9-1 10:05:30"
    }
}

for key, value in data2.items():
    ref2.child(key).set(value)