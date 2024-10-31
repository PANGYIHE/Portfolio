import firebase_admin
from firebase_admin import credentials
from firebase_admin import db

cred = credentials.Certificate("ServiceAccountKey.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': "https://facerecognitionattendanc-e41e6-default-rtdb.asia-southeast1.firebasedatabase.app/"
})

ref = db.reference('/')
ref.update({"currentClass": "BCM3233"})

ref1 = ref.child('BCM3233')
data1 = {
    "CD21074": {
        "id": "CD21074",
        "name": "PANG YI HE",
        "faculty": "FKOM",
        "classs": "BCM3233",
        "total_attendance": 10,
        "grade": "G",
        "year": 4,
        "last_attendance_time": "2024-9-1 10:05:30"
    },
    "DRFERDA": {
        "id": "DRFERDA",
        "name": "DR. FERDA ERNAWAN",
        "faculty": "FKOM",
        "classs": "BCM3233",
        "total_attendance": 9,
        "grade": "G",
        "year": 4,
        "last_attendance_time": "2024-9-3 12:05:30"
    }
}

for key, value in data1.items():
    ref1.child(key).set(value)

ref2 = ref.child('BCM3103')
data2 = {
    "CD21074": {
        "id": "CD21074",
        "name": "PANG YI HE",
        "faculty": "FKOM",
        "classs": "BCM3103",
        "total_attendance": 10,
        "grade": "G",
        "year": 4,
        "last_attendance_time": "2024-9-1 10:05:30"
    },
    "DRLIEW": {
        "id": "DRLIEW",
        "name": "DR. LIEW SIAU CHUIN",
        "faculty": "FKOM",
        "classs": "BCM3103",
        "total_attendance": 8,
        "grade": "G",
        "year": 4,
        "last_attendance_time": "2024-9-5 14:05:30"
    }
}

for key, value in data2.items():
    ref2.child(key).set(value)