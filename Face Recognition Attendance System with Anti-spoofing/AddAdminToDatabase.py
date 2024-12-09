import firebase_admin
from firebase_admin import credentials
from firebase_admin import db

cred = credentials.Certificate("ServiceAccountKey.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': "https://facerecognitionattendanc-e41e6-default-rtdb.asia-southeast1.firebasedatabase.app/"
})

ref = db.reference('/')

ref1 = ref.child('AdminAuthList')
data1 = {
    "q26VDujphDSYhg4XEEClbmVkHuw1": {
        "UID": "q26VDujphDSYhg4XEEClbmVkHuw1",
        "email": "pangyihe@gmail.com",
        "name": "PANG YI HE",
        "faculty": "FKOM",
        "adminID": "A0001",
        "phoneNo": "01112324468"
    },
    "rffjKKK3inVkLcKfGyvCK43KRjZ2": {
        "UID": "rffjKKK3inVkLcKfGyvCK43KRjZ2",
        "email": "drferda@gmail.com",
        "name": "FERDA ERNAWAN",
        "faculty": "FKOM",
        "adminID": "A0002",
        "phoneNo": "076558846"
    }
}

for key, value in data1.items():
    ref1.child(key).set(value)