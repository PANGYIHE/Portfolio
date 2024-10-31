<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <title>Update Profile</title>
</head>

<body>
	<table class="topTable" style="width:100%">
		<tr>
			<th class="tht" style="width:30%; font-size: 50px; text-align: right"><a href="
			<?php
			if ($_SESSION['userType'] == 'Administrator') {
				echo 'adminHome.php';
			} elseif ($_SESSION['userType'] == 'Food Vendor') {
				echo 'foodVendorHome.php';
			} elseif ($_SESSION['userType'] == 'Registered User') {
				echo 'userHome.php';
			} 
			
			$id = $_SESSION['id'];
			if($_SESSION['userType'] == 'Administrator'){
				$query = mysqli_query($con,"SELECT*FROM administrator WHERE AdminID='$id' ");

				while($result = mysqli_fetch_assoc($query)){
					$res_Uname = $result['AdminName'];
					$res_Profile = $result['AdminProfile'];
				}
			}elseif($_SESSION['userType'] == 'Food Vendor'){
				$query = mysqli_query($con,"SELECT*FROM foodvendor WHERE VendorID='$id' ");

				while($result = mysqli_fetch_assoc($query)){
					$res_Uname = $result['VendorName'];
					$res_Profile = $result['VendorProfile'];
				}
			}elseif($_SESSION['userType'] == 'Registered User'){
				$query = mysqli_query($con,"SELECT*FROM registereduser WHERE UserID='$id' ");

				while($result = mysqli_fetch_assoc($query)){
					$res_Uname = $result['UserName'];
					$res_Profile = $result['UserProfile'];
				}
			}
			?>
			">FKOM KIOSK</a></th>
			<th class="tht" style="text-align: left; padding-left: 40px"><img src="UMPSA logo.png" alt="UMPSA Logo" width="101" height="75"></th>
			<th class="tht" style="width:10%; text-align: right; padding-right: 10px">
			<?php
				if ($res_Profile != NULL) {
					echo "<img src='" . $res_Profile . "' alt='Profile Picture' height='75'>";
				} else {
					echo "<img src='account.png' alt='Default Profile Picture' height='75'>";
				}
			?></th>
			<th class="tht" style="width:10%; text-align: left; font-size: 25px"><?php echo "<a href='#'>$res_Uname</a>"; ?></th>
			<th class="tht" style="width:16%; text-align: left; padding: 0px;"><a href="php/logout.php"> <button class="btn">Log Out</button> </a></th>
		</tr>
	</table>
	
    <div class="container">
        <div class="box form-box">
	<?php 
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
		$password = $_POST['password'];
		$newProfile = '';
		$newQRCode = '';
        $id = $_SESSION['id'];
		
		
		if (!empty($_FILES['newProfile']['name'])) {
			$file_name = $_FILES['newProfile']['name'];
			$temp_name = $_FILES['newProfile']['tmp_name'];
			$file_type = $_FILES['newProfile']['type'];

			// Specify the folder where you want to save the uploaded profile pictures
			$folder = 'profilePictures/';

			// Create a unique name for the uploaded file
			$newProfile = $folder . $username . '_' . $file_name;

			// Move the uploaded file to the specified folder
			move_uploaded_file($temp_name, $newProfile);
		}
		
		$verify_query_admin = mysqli_query($con, "SELECT AdminName FROM administrator WHERE AdminName='$username'");
		$verify_query_vendor = mysqli_query($con, "SELECT VendorName FROM foodvendor WHERE VendorName='$username'");
		$verify_query_user = mysqli_query($con, "SELECT UserName FROM registereduser WHERE UserName='$username'");

		if ($username != $res_Uname && mysqli_num_rows($verify_query_admin) > 0 || $username != $res_Uname && mysqli_num_rows($verify_query_vendor) > 0 || $username != $res_Uname && mysqli_num_rows($verify_query_user) > 0) {      
			echo "<div class='message'>
				<p>This username is used, Try another One Please!</p>
				</div> <br>";
			echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
			
		}elseif($_SESSION['userType'] == 'Administrator'){
			$edit_query = mysqli_query($con,"UPDATE administrator SET AdminName='$username', AdminPassword='$password' WHERE AdminID='$id' ") or die("error occurred");
			
			if ($newProfile != NULL){
				$editPic_query = mysqli_query($con,"UPDATE administrator SET AdminProfile='$newProfile' WHERE AdminID='$id' ") or die("error occurred");
			}

            if($edit_query || $editPic_query){
                echo "<div class='message'>
					<p>Profile Updated!</p>
                </div> <br>";
				echo "<a href='adminHome.php'><button class='btn'>Go Home</button>";
			}
		}elseif($_SESSION['userType'] == 'Food Vendor'){
			$email = $_POST['email'];
			$edit_query = mysqli_query($con,"UPDATE foodvendor SET VendorName='$username', VendorEmail='$email', VendorPassword='$password' WHERE VendorID='$id' ") or die("error occurred");

			if ($newProfile != NULL){
				$editPic_query = mysqli_query($con,"UPDATE foodvendor SET VendorProfile='$newProfile' WHERE VendorID='$id' ") or die("error occurred");
			}

			$vendorQR = '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $username . '&size=80x80">';

			mysqli_query($con, "UPDATE foodvendor SET VendorQRCode = '$vendorQR' WHERE VendorName = '$username'");

            if($edit_query || $editPic_query){
                echo "<div class='message'>
					<p>Profile Updated!</p>
                </div> <br>";
				echo "<a href='foodVendorHome.php'><button class='btn'>Go Home</button>";
			}
		}elseif($_SESSION['userType'] == 'Registered User'){
			$email = $_POST['email'];
			$edit_query = mysqli_query($con,"UPDATE registereduser SET UserName='$username', UserEmail='$email', UserPassword='$password' WHERE UserID='$id' ") or die("error occurred");

			if ($newProfile != NULL){
				$editPic_query = mysqli_query($con,"UPDATE registereduser SET UserProfile='$newProfile' WHERE UserID='$id' ") or die("error occurred");
			}
			
			$userQR = '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $username . '&size=80x80">';

			// Update the database with the generated QR code
			mysqli_query($con, "UPDATE registereduser SET UserQRCode = '$userQR' WHERE UserName = '$username'");
			
            if($edit_query || $editPic_query){
                echo "<div class='message'>
					<p>Profile Updated!</p>
                </div> <br>";
				echo "<a href='userHome.php'><button class='btn'>Go Home</button>";
			}
		}

	}elseif (isset($_POST['delete'])) {
    $id = $_SESSION['id'];
    
		if($_SESSION['userType'] == 'Administrator'){
			$delete_query = mysqli_query($con, "DELETE FROM administrator WHERE AdminID='$id'") or die("Error occurred");

			if ($delete_query) {
				session_destroy();
				echo "<div class='message'>
						<p>Account Deleted</p>
					  </div> <br>";
				echo "<a href='index.php'><button class='btn'>Go To Login</button>";
			}		
		}elseif($_SESSION['userType'] == 'Food Vendor'){
			$delete_query = mysqli_query($con, "DELETE FROM foodvendor WHERE VendorID='$id'") or die("Error occurred");

			if ($delete_query) {
				session_destroy();
				echo "<div class='message'>
						<p>Account Deleted</p>
					  </div> <br>";
				echo "<a href='index.php'><button class='btn'>Go To Login</button>";
			}		
		}elseif($_SESSION['userType'] == 'Registered User'){
			$delete_query = mysqli_query($con, "DELETE FROM registereduser WHERE UserID='$id'") or die("Error occurred");

			if ($delete_query) {
				session_destroy();
				echo "<div class='message'>
						<p>Account Deleted</p>
					  </div> <br>";
				echo "<a href='index.php'><button class='btn'>Go To Login</button>";
			}		
		}
    }elseif (isset($_POST['deletePicture'])) {
    $id = $_SESSION['id'];
    
		if($_SESSION['userType'] == 'Administrator'){
			$deletePicture_query = mysqli_query($con, "UPDATE administrator SET AdminProfile = NULL WHERE AdminID='$id'") or die("Error occurred");

			if ($deletePicture_query) {
				echo "<div class='message'>
						<p>Profile Picture Deleted</p>
					  </div> <br>";
				echo "<a href='edit.php'><button class='btn'>Go Back</button>";
			}		
		}elseif($_SESSION['userType'] == 'Food Vendor'){
			$deletePicture_query = mysqli_query($con, "UPDATE foodvendor SET VendorProfile = NULL WHERE VendorID='$id'") or die("Error occurred");

			if ($deletePicture_query) {
				echo "<div class='message'>
						<p>Profile Picture Deleted</p>
					  </div> <br>";
				echo "<a href='edit.php'><button class='btn'>Go Back</button>";
			}		
		}elseif($_SESSION['userType'] == 'Registered User'){
			$deletePicture_query = mysqli_query($con, "UPDATE registereduser SET UserProfile = NULL WHERE UserID = '$id'") or die("Error occurred");

			if ($deletePicture_query) {
				echo "<div class='message'>
						<p>Profile Picture Deleted</p>
					  </div> <br>";
				echo "<a href='edit.php'><button class='btn'>Go Back</button>";
			}		
		}
	}else{

        $id = $_SESSION['id'];
        if($_SESSION['userType'] == 'Administrator'){
			$query = mysqli_query($con,"SELECT*FROM administrator WHERE AdminID='$id' ");

			while($result = mysqli_fetch_assoc($query)){
				$res_Uname = $result['AdminName'];
				$res_Password = $result['AdminPassword'];
				$res_Profile = $result['AdminProfile'];
			}
		}elseif($_SESSION['userType'] == 'Food Vendor'){
			$query = mysqli_query($con,"SELECT*FROM foodvendor WHERE VendorID='$id' ");

			while($result = mysqli_fetch_assoc($query)){
				$res_Uname = $result['VendorName'];
				$res_Email = $result['VendorEmail'];
				$res_Password = $result['VendorPassword'];
				$res_Profile = $result['VendorProfile'];
				
				$query = mysqli_query($con,"SELECT * FROM foodvendor INNER JOIN administrator ON foodvendor.AdminID = administrator.AdminID");

				while($result = mysqli_fetch_assoc($query)){
					$res_AdminID = $result['AdminName'];
				}
			}
		}elseif($_SESSION['userType'] == 'Registered User'){
			$query = mysqli_query($con,"SELECT*FROM registereduser WHERE UserID='$id' ");

			while($result = mysqli_fetch_assoc($query)){
				$res_Uname = $result['UserName'];
				$res_Email = $result['UserEmail'];
				$res_Password = $result['UserPassword'];
				$res_Profile = $result['UserProfile'];
			}
		}
	?>
            <header><h2>Update Profile</h2></header>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="field input">
                    <label for="username"><b>Username</b></label>
                    <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                </div>
				
				<?php
				if($_SESSION['userType'] == 'Food Vendor'||$_SESSION['userType'] == 'Registered User'){
					echo '<div class="field input">
						<label for="email"><b>Email</b></label>
						<input type="text" name="email" id="email" value="'. $res_Email. '" autocomplete="off" required>
					</div>';
				}
				?>
				
				<div class="field input">
                    <label for="password"><b>Password</b></label>
                    <input type="text" name="password" id="password" value="<?php echo $res_Password; ?>" autocomplete="off" required>
                </div>
				
				<?php
				if($_SESSION['userType'] == 'Food Vendor'){
					echo "<b>Admin in charge: </b><br><p style='padding: 8px 2px'>•&emsp;";
					echo "$res_AdminID</p>"; 
				}
				?>
				
				<label for="usertype"><b>User Type</b></label>
				<p style="padding: 8px 2px">•&emsp;<?php echo $_SESSION['userType'] ?></p>
				
				<hr><hr><br><label for="newProfile"><b>Profile Picture </b></label><br>•&emsp;
				<?php 
				if ($res_Profile != NULL) {
					echo "<img src='" . $res_Profile . "' alt='Profile Picture' height='170'>";
				} else {
					echo "<img src='account.png' alt='Default Profile Picture' height='170'>";
				} 
				?>
				<div style="padding-top:5px; padding-bottom:5px">
				<label for="newProfile"><b>Update Profile Picture: </b></label>
				<div style="padding-top:5px; padding-bottom:5px">
					<img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; padding: 10px 0px"><br>
					<input type="file" name="newProfile" id="newProfile" onchange="previewImage(this);"><br>
				</div>
				<input type="submit" class="btn" name="deletePicture" value="Delete Profile Picture">
				</div><hr><hr>
															
                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Update" required>
					<input type="submit" class="btn" name="delete" value="Delete Account">
                </div>
                
            </form>
		<?php } ?>
        </div>
      </div>
	  <script>	
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