<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <title>Registration</title>
</head>
<body>
	<table class="topTable" style="width:100%">
		<tr>
			<th class="tht" style="width:30%; font-size: 50px; text-align: right"><a href="">FKOM KIOSK</a></th>
			<th class="tht" style="text-align: left; padding-left: 40px"><img src="UMPSA logo.png" alt="UMPSA Logo" width="101" height="75"></th>
			<th class="tht" style="width:10%"></th>
			<th class="tht" style="width:10%"></th>
			<th class="tht" style="width:16%"></th>
		</tr>
	</table>
	  
    <div class="container">
		<div class="box form-box">

        <?php 
         
         include("php/config.php");
         if(isset($_POST['submit'])){
            $username = $_POST['username'];
			$email = $_POST['email'];
            $password = $_POST['password'];
			$userType = $_POST['userType'];
			$vendorQR = '';
			$userQR = '';
			$userStatus = 'Pending';
			$userProfile = '';

		if (!empty($_FILES['userProfile']['name'])) {
			$file_name = $_FILES['userProfile']['name'];
			$temp_name = $_FILES['userProfile']['tmp_name'];
			$file_type = $_FILES['userProfile']['type'];

			// Specify the folder where you want to save the uploaded profile pictures
			$folder = 'profilePictures/';

			// Create a unique name for the uploaded file
			$userProfile = $folder . $username . '_' . $file_name;

			// Move the uploaded file to the specified folder
			move_uploaded_file($temp_name, $userProfile);
		}
		
		$verify_query_admin = mysqli_query($con, "SELECT AdminName FROM administrator WHERE AdminName='$username'");
		$verify_query_vendor = mysqli_query($con, "SELECT VendorName FROM foodvendor WHERE VendorName='$username'");
		$verify_query_user = mysqli_query($con, "SELECT UserName FROM registereduser WHERE UserName='$username'");

		if (mysqli_num_rows($verify_query_admin) > 0 || mysqli_num_rows($verify_query_vendor) > 0 || mysqli_num_rows($verify_query_user) > 0) {
            echo "<div class='message'>
					<p>This username is used, Try another One Please!</p>
					</div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        
		}elseif($userType == 'Food Vendor'){
			$highestVendorIDQuery = mysqli_query($con, "SELECT MAX(SUBSTRING(VendorID, 2)) AS maxNumericID FROM foodvendor");
			$row = mysqli_fetch_assoc($highestVendorIDQuery);
			$highestNumericID = $row['maxNumericID'];

			$newNumericID = $highestNumericID + 1;

			$newVendorID = 'V' . str_pad($newNumericID, 4, '0', STR_PAD_LEFT);
			
            mysqli_query($con,"INSERT INTO foodvendor(VendorID, VendorName, VendorEmail, VendorPassword, VendorQRCode, ApprovalStatus, VendorProfile, AdminID) VALUES ('$newVendorID', '$username', '$email', '$password', '$vendorQR', '$userStatus', '$userProfile', 'A0001')") or die("Error Occured");

			$vendorQR = '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $username . '&size=80x80">';

			mysqli_query($con, "UPDATE foodvendor SET VendorQRCode = '$vendorQR' WHERE VendorName = '$username'");
		
            echo "<b style='text-align: center'>Your QR Code:<br><br>$vendorQR</b>
				  <br>
				  <div class='message'>
                    <p>Registration successfully!</p>
                  </div> <br>";
            echo "<a href='index.php'><button class='btn'>Login Now</button>";
        }elseif($userType == 'Registered User'){
			$highestUserIDQuery = mysqli_query($con, "SELECT MAX(SUBSTRING(UserID, 2)) AS maxNumericID FROM registereduser");
			$row = mysqli_fetch_assoc($highestUserIDQuery);
			$highestNumericID = $row['maxNumericID'];

			$newNumericID = $highestNumericID + 1;

			$newUserID = 'U' . str_pad($newNumericID, 4, '0', STR_PAD_LEFT);

            mysqli_query($con,"INSERT INTO registereduser(UserID, UserName, UserEmail, UserPassword, UserQRCode, UserProfile) VALUES ('$newUserID', '$username', '$email', '$password', '$userQR', '$userProfile')") or die("Error Occured");

			$userQR = '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $username . '&size=80x80">';

			// Update the database with the generated QR code
			mysqli_query($con, "UPDATE registereduser SET UserQRCode = '$userQR' WHERE UserName = '$username'");
		
            echo "<b style='text-align: center'>Your QR Code:<br><br>$userQR</b>
				  <br>
				  <div class='message'>
                    <p>Registration successfully!</p>
                  </div> <br>";
            echo "<a href='index.php'><button class='btn'>Login Now</button>";
        }

        }else{
         
        ?>

            <header><h2>Registration</h2></header>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="field input">
                    <label for="username"><b>Username</b></label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
				
				<div class="field input">
                    <label for="email"><b>Email Address</b></label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>
				
                <div class="field input">
                    <label for="password"><b>Password</b></label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
					<input type="checkbox" onclick="showPass()"><label style="position:absolute; padding:3px 5px; font-size:14px;">Show Password</label><br><br>

				<label for="userProfile"><b>Profile Picture</b></label><br>
				<div style="padding-top:5px; padding-bottom:15px">
					<img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; padding: 10px 0px"><br>
					<input type="file" name="userProfile" id="userProfile" onchange="previewImage(this);"><br>
				</div>
					
				<label for="userType"><b>User Type</b></label><br>
				<div class="radio">
					<input type="radio" name="userType" value="Food Vendor" required> Food Vendor &emsp;
					<input type="radio" name="userType" value="Registered User" required> Registered User (Student or Staff)
				</div>
		
                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <div class="links">
                    Already have an account? <a href="index.php">Log In</a>
                </div>
            </form>
		</div>
		<?php } ?>
     </div>
	  
	<script>
	function showPass() {
		var x = document.getElementById("password");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}
	
	function previewImage(input) {
            var preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
	</script>
</body>
</html>