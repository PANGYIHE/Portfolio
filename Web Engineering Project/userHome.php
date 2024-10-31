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
    <title>Home</title>
</head>
<?php 
            
    $id = $_SESSION['id'];
    $query = mysqli_query($con,"SELECT*FROM registereduser WHERE UserID='$id'");

    while($result = mysqli_fetch_assoc($query)){
        $res_Uname = $result['UserName'];
		$res_UQR = $result['UserQRCode'];
        $res_id = $result['UserID'];
		$res_pic = $result['UserProfile'];
    }
             
?>
<body>
	<table class="topTable" style="width:100%">
		<tr>
			<th class="tht" style="width:30%; font-size: 50px; text-align: right"><a href="userHome.php">FKOM KIOSK</a></th>
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
                <p style="font-size: 30px; padding:25px"><b>Registered User Dashboard</b></p>
            </div>
            <div class="box">
				<p><b>Your QR Code: </b><?php echo $res_UQR ?></p>
            </div>
          </div>
		  <div class="bottom">
            <div class="box">
                <p>content</p> 
            </div>
          </div>
       </div>

    </main>
</body>
</html>