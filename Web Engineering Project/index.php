<?php 
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <title>Login</title>
</head>
<body class="mainbody">
	  <table class="topTable" style="width:100%">
		<tr>
			<th class="tht" style="width:64%; color: #fff; font-size: 100px; text-align: right"><a style="color:white">FKOM KIOSK</a></th>
			<th class="tht" style="text-align: left; padding-left: 40px"><img src="UMPSAback.png" alt="UMPSA Logo" width="187" height="140"></th>
		</tr>
	  </table>
	
    <div class="container">
        <div class="box form-box">
            <?php 
             
              include("php/config.php");
              if(isset($_POST['submit'])){
                $username = mysqli_real_escape_string($con,$_POST['username']);
                $password = mysqli_real_escape_string($con,$_POST['password']);
				$userType = mysqli_real_escape_string($con,$_POST['userType']);
				
				
				if ($userType == 'Administrator'){
					$result = mysqli_query($con,"SELECT * FROM administrator WHERE AdminName='$username' AND AdminPassword='$password'") or die("Select Error");
					$row = mysqli_fetch_assoc($result);
					
					if(is_array($row) && !empty($row)){
                    $_SESSION['valid'] = $row['AdminName'];
					$_SESSION['id'] = $row['AdminID'];
					$_SESSION['userType'] = 'Administrator';
					$_SESSION['profile'] = $row['AdminProfile'];
					
					header("Location: adminHome.php");
                
					}else{
						echo "<div class='message'>
							<p>Wrong Username, Password or User Type</p>
							</div> <br>";
						echo "<a href='index.php'><button class='btn'>Go Back</button>";
					}
				}elseif ($userType == 'Food Vendor'){
					$result = mysqli_query($con,"SELECT * FROM foodvendor WHERE VendorName='$username' AND VendorPassword='$password'") or die("Select Error");
					$row = mysqli_fetch_assoc($result);
					
					if (!empty($row) && $row['ApprovalStatus'] == 'Pending') {
						echo "<div class='message'>
							<p>Your account has not been approved. Your approval will be processed in 3 working days.</p>
							</div> <br>";
						echo "<a href='index.php'><button class='btn'>Go Back</button>";
					}elseif (!empty($row) && $row['ApprovalStatus'] == 'Rejected') {
						echo "<div class='message'>
							<p>Your account has been rejected. Thank you.</p>
							</div> <br>";
						echo "<a href='index.php'><button class='btn'>Go Back</button>";
					}elseif(is_array($row) && !empty($row)){
						$_SESSION['valid'] = $row['VendorName'];
						$_SESSION['id'] = $row['VendorID'];
						$_SESSION['userType'] = 'Food Vendor';
						$_SESSION['profile'] = $row['VendorProfile'];
					
						header("Location: foodVendorHome.php");
					}else{
						echo "<div class='message'>
							<p>Wrong Username, Password or User Type</p>
							</div> <br>";
						echo "<a href='index.php'><button class='btn'>Go Back</button>";
					}
				}elseif ($userType == 'Registered User'){
					$result = mysqli_query($con,"SELECT * FROM registereduser WHERE UserName='$username' AND UserPassword='$password'") or die("Select Error");
					$row = mysqli_fetch_assoc($result);
					
					if(is_array($row) && !empty($row)){
                    $_SESSION['valid'] = $row['UserName'];
					$_SESSION['id'] = $row['UserID'];
					$_SESSION['userType'] = 'Registered User';
					$_SESSION['profile'] = $row['UserProfile'];
					
					header("Location: userHome.php");
                
					}else{
						echo "<div class='message'>
							<p>Wrong Username, Password or User Type</p>
							</div> <br>";
						echo "<a href='index.php'><button class='btn'>Go Back</button>";
					}
				}
				
              }else{
				  
            ?>
            <header><h2>Login</h2></header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username"><b>Username</b></label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password"><b>Password</b></label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
					<input type="checkbox" onclick="showPass()"><label style="position:absolute; padding:3px 5px; font-size:14px;">Show Password</label><br><br>
				
				<label for="userType"><b>User Type</b></label><br>
				<div class="radio">
					<input type="radio" name="userType" value="Administrator" required> Administrator &emsp;
					<input type="radio" name="userType" value="Food Vendor" style="text-align: center" required> Food Vendor &emsp;
					<input type="radio" name="userType" value="Registered User" required> Registered User (Student or Staff)
				</div>
				
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="field">
					<a href="register.php" class="btn" style="text-decoration: none; padding:10px">Register</a>
                </div>
				
				<div class="links">
                    Skip log in and go to the home page? <a href="generalHome.php">Skip</a>
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
	</script>
</body>
</html>