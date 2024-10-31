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
    <title>Food Vendor Approval</title>
</head>
<?php 
            
    $id = $_SESSION['id'];
    $query = mysqli_query($con,"SELECT*FROM administrator WHERE AdminID='$id'");

    while($result = mysqli_fetch_assoc($query)){
        $res_Uname = $result['AdminName'];
        $res_id = $result['AdminID'];
		$res_pic = $result['AdminProfile'];
    }
             
?>
<body>
	<table class="topTable" style="width:100%">
		<tr>
			<th class="tht" style="width:30%; font-size: 50px; text-align: right"><a href="adminHome.php">FKOM KIOSK</a></th>
			<th class="tht" style="text-align: left; padding-left: 40px"><img src="UMPSA logo.png" alt="UMPSA Logo" width="101" height="75"></th>
			<th class="tht" style="width:10%; text-align: right; padding-right: 10px">
			<?php
				if ($res_pic != NULL) {
					echo "<img src='" . $res_pic . "' alt='Profile Picture' height='75'>";
				} else {
					echo "<img src='account.png' alt='Default Profile Picture' height='75'>";
				}
			?></th>
			<th class="tht" style="width:10%; text-align: left; font-size: 25px"><a><?php echo "<a href='edit.php?Id=$res_id'>$res_Uname</a>"; ?></a></th>
			<th class="tht" style="width:16%; text-align: left; padding: 0px"><a href="php/logout.php"> <button class="btn">Log Out</button> </a></th>
		</tr>
	</table>

    <main>

       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p style="font-size: 30px; padding:5px"><b>Administrator Dashboard</b></p>
            </div>
          </div>
		  <div class="bottom">
            <div class="box">
                <ul>
					<li><a class="active" href="#">Food Vendor Approval</a></li>
					<li><a href="adminVendorList.php">Food Vendor List</a></li>
					<li><a href="adminUserList.php">Registered User List</a></li>
					<li><a href="adminAddUser.php">Create New User</a></li>
					<li><a href="adminUserChart.php">Insights Report</a></li>
				</ul>
				<br>
			<table class="botTable">
				<tr style="background-color:#d8f3ea; border: 2px solid black;">
					<td class="tdb" width="7%"><b>User ID</b></td>
					<td class="tdb" width="20%"><b>Username</b></td>
					<td class="tdb" width="35%"><b>User Email</b></td>
					<td class="tdb" width="20%"><b>User QR Code</b></td>
					<td class="tdb"><b>Action</b></td>
				</tr>
			</table>
			<?php
			$link = mysqli_connect("localhost","root","","kiosksys") or die("Couldn't connect");
			$query = "SELECT * FROM foodvendor WHERE ApprovalStatus='Pending'" or die(mysqli_connect_error());

			$result = mysqli_query($link, $query);

			if (isset($_POST['approve'])) {
				$uID = $_POST["userId"]; 

				mysqli_query($link, "UPDATE foodvendor SET ApprovalStatus = 'Approved' WHERE VendorID = '$uID'") or die("Error occurred");

				header("Location: adminHome.php");
				exit();
			}elseif (isset($_POST['reject'])) {
				$uID = $_POST["userId"];

				mysqli_query($link, "UPDATE foodvendor SET ApprovalStatus = 'Rejected' WHERE VendorID = '$uID'") or die("Error occurred");
				
				header("Location: adminHome.php");
				exit();
			}

			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$uID = $row["VendorID"];
					$uName = $row["VendorName"];
					$uEmail = $row["VendorEmail"];
					$uQR = $row["VendorQRCode"];
			?>
        <form method="post">
            <table class="botTable" width="100%">
                <tr>
                    <td class="tdb" width="7%"><?php echo $uID; ?></td>
                    <td class="tdb" width="20%"><?php echo $uName; ?></td>
                    <td class="tdb" width="35%"><?php echo $uEmail; ?></td>
                    <td class="tdb" width="20%"><?php echo $uQR; ?></td>
                    <td class="tdb">
                        <input type="hidden" name="userId" value="<?php echo $uID; ?>">
                        <input type="submit" class="btn" name="approve" value="Approve" required>
                        <input type="submit" class="btn" name="reject" value="Reject" required>
                    </td>
                </tr>
            </table>
        </form>
			<?php
				}
			} else {
				echo "<br>No food vendor is pending approval.";
			}
			?>
            </div>
          </div>
       </div>

    </main>
</body>
</html>