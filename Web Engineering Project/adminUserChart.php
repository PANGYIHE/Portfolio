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
    
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://kit.fontawesome.com/bfb784e94e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.30.0/dist/apexcharts.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="style/styles.css" rel="stylesheet">

	<title>Insights Report</title>
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
			<th class="tht" style="width:30%; font-size: 50px; text-align: right;"><a href="adminHome.php">FKOM KIOSK</a></th>
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
                <p style="font-size: 30px; padding:0px 5px; height:30px"><b>Administrator Dashboard</b></p>
            </div>
          </div>
		  <div class="bottom">
            <div class="box">
                <ul>
					<li><a href="adminHome.php">Food Vendor Approval</a></li>
					<li><a href="adminVendorList.php">Food Vendor List</a></li>
					<li><a href="adminUserList.php">Registered User List</a></li>
					<li><a href="adminAddUser.php">Create New User</a></li>
					<li><a class="active" href="#">Insights Report</a></li>
				</ul>
			
			<?php
			$link = mysqli_connect("localhost","root","","kiosksys") or die("Couldn't connect");
			$query = "SELECT * FROM administrator" or die(mysqli_connect_error());
			$result = mysqli_query($link, $query);
			$query1 = "SELECT * FROM foodvendor INNER JOIN administrator ON foodvendor.AdminID = administrator.AdminID" or die(mysqli_connect_error());
			$result1 = mysqli_query($link, $query1);
			$query2 = "SELECT * FROM registereduser" or die(mysqli_connect_error());
			$result2 = mysqli_query($link, $query2);

			$adminCount = 0;
			$foodVendorCount = 0;
			$userCount = 0;
			$total = 0;

			if (mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					$adminCount++;
				}
			}
			if (mysqli_num_rows($result1) > 0){
				while($row = mysqli_fetch_assoc($result1)){
					$foodVendorCount++;
				}
			}
			if (mysqli_num_rows($result2) > 0){
				while($row = mysqli_fetch_assoc($result2)){
					$userCount++;
				}
			}
				
			$total = $adminCount + $foodVendorCount + $userCount;
			
			mysqli_close($link);
			?>	
		
			 <br><div class="row mb-3">
        <div class="col-lg-3">
            <div class="card bg-primary mb-2">
                <div class="card-body text-white">
                    <p class="h3 text-muted">Total Account</p>
                    <p class="h2"><?php echo $total; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-danger mb-2">
                <div class="card-body text-white">
                    <p class="h3 text-muted">Administrator</p>
                    <p class="h2"><?php echo $adminCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body text-white">
                    <p class="h3 text-muted">Food Vendor</p>
                    <p class="h2"><?php echo $foodVendorCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-success mb-2">
                <div class="card-body text-white">
                    <p class="h3 text-muted">Registered User</p>
                    <p class="h2"><?php echo $userCount; ?></p>
                </div>
            </div>
        </div>
			</div>
			
		<div class="row mb-3">
            <!-- Chart 1 -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header h4" style="background-color: #66CDAA;">User Type Polar Area Chart</div>
                    <div class="card-body">
                        <div id="userTypeChart"></div>
                    </div>
                </div>
            </div>
            <!-- Chart 2 -->
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-header h4" style="background-color: #66CDAA;">Total User Table</div>
                    <div class="card-body">
					<table class="table table-bordered">
                        <tr>
							<th style="background-color: #f2f2f2;">User Type</th>
							<th style="background-color: #f2f2f2;">Number</th>
						</tr>
						<tr>
							<th>Administrator</th>
							<th><?php echo $adminCount ?></th>
						</tr>
						<tr>
							<th>Food Vendor</th>
							<th><?php echo $foodVendorCount ?></th>
						</tr>
						<tr>
							<th>Registered User</th>
							<th><?php echo $userCount ?></th>
						</tr>
						<tr>
							<th>Total</th>
							<th><?php echo $total ?></th>
						</tr>
					</table>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="row mb-3">
            <!-- Chart 1 -->
			<div class="col-lg-6">
                <div class="card">
                    <div class="card-header h4" style="background-color: #66CDAA;">User Type Radial Chart</div>
                    <div class="card-body">
                        <div id="radialChart"></div>
                    </div>
                </div>
            </div>
			<!-- Chart 2 -->
             <div class="col-lg-6">
                <div class="card">
                    <div class="card-header h4" style="background-color: #66CDAA;">User Type Treemap Chart</div>
                    <div class="card-body">
                        <div id="treeMapChart"></div>
                    </div>
                </div>
            </div>
        </div>

		<script>

		// Data
        var adminCount = <?php echo $adminCount; ?>;
        var foodVendorCount = <?php echo $foodVendorCount; ?>;
        var userCount = <?php echo $userCount; ?>;
		var total = <?php echo $total; ?>;
		
	//Radial Bar Chart
	var options = {	
		series: [adminCount, foodVendorCount, userCount],
		chart: {
			type: 'radialBar',
		},
		plotOptions: {
			radialBar: {
				dataLabels: {
					total: {
						show: true,
						label: 'Total',
						formatter: function (w) {
							return total;
						}
					}
				}
			}
		},
		labels: ['Administrator', 'Food Vendor', 'Registered User (Staff and Student)'],
		colors: ['rgba(205, 92, 92, 0.8)', 'rgba(255, 215, 0, 0.8)', 'rgba(50, 205, 50, 0.8)'],
	};

	var chart = new ApexCharts(document.querySelector("#radialChart"), options);
	chart.render();



        // ApexCharts code for Polar Area Chart
        var options = {
            series: [adminCount, foodVendorCount, userCount],
            chart: {
                type: 'polarArea',
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            labels: ['Administrator', 'Food Vendor', 'Registered User (Staff and Student)'],
            responsive: [{
                breakpoint: 200,
                options: {
                    chart: {
                        height: 280,
						type: 'polarArea'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors: ['rgba(205, 92, 92, 0.8)', 'rgba(255, 215, 0, 0.8)', 'rgba(50, 205, 50, 0.8)'],
        };

        var chart = new ApexCharts(document.querySelector("#userTypeChart"), options);
        chart.render();
		
		// Tree Map Chart 
		var options = {
		series: [
			{
			data: [
				{ x: 'Administrator', y: <?php echo $adminCount; ?> },
				{ x: 'Food Vendor', y: <?php echo $foodVendorCount; ?> },
				{ x: 'Registered User (Staff and Student)', y: <?php echo $userCount; ?> }
			]
			}
		],
		legend: {
			show: false
		},
		chart: {
			height: 280,
			type: 'treemap'
		},
		colors: [
			'#CD5C5C', 
			'#FFD700', 
			'#32CD32'  
		],
		plotOptions: {
			treemap: {
			distributed: true,
			enableShades: false
			}
		}
		};

		// Create and render the chart
		var treeMapChart = new ApexCharts(document.querySelector("#treeMapChart"), options);
		treeMapChart.render();	
		</script>
            </div>
          </div>
       </div>

    </main>
</body>
</html>