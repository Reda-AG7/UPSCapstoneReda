<?php
session_start();
if(!isset($_SESSION['email'])){
	header('Location: login.php');
	exit();
}
?>
<?php 
	include_once "includes/db_inc.php";
	include_once "includes/functions_inc.php"; 
	$vms = getVMs($conn,$_GET['building']);
	
	$clusterInfos = getClusterInfos($conn,$_GET['building']);
	$totalVMs = $clusterInfos['totalVMs'];
	$totalCPUsAllocated = intval($clusterInfos['totalCPUsAllocated']);
	$totalCPUsAvailable = intval($clusterInfos['totalCPUsAvailable']);
	$totalMemoryUsed = intval($clusterInfos['totalMemoryUsed']);
	$totalMemory = intval($clusterInfos['totalMemory']);
	$totalHost = intval($clusterInfos['totalHost']);
	$type = ($totalMemory/$totalHost == 560)? "Large" : "Small";
	$totalDisk = ($type == "Large") ? 18000*$totalHost:15000*$totalHost;
	$usedDisk = 0;
	foreach(json_decode($vms) as $vm){
		$usedDisk += intval($vm->diskSpace);
	}
	$diskUsage = round($usedDisk*100/$totalDisk);
?>
<!DOCTYPE html>
<html>
<script type="text/javascript">
	var deletedVMs = ""
	var type = "<?= $type;?>";
	var vms = JSON.parse('<?=$vms;?>')
	var totalHost = "<?=$totalHost;?>"
	var totalVMs = parseInt("<?= $totalVMs;?>");
	var totalCPUsAvailable = parseInt("<?= $totalCPUsAvailable;?>");
	var totalMemoryUsed = parseInt("<?= $totalMemoryUsed;?>");
	var totalDisk = parseInt("<?=$totalDisk;?>");
	var totalCPUsAllocated = parseInt("<?= $totalCPUsAllocated;?>");
	var totalMemory = parseInt("<?= $totalMemory;?>");
	var usedDisk = parseInt("<?=$usedDisk;?>");
	console.log("CPU available : ",totalCPUsAvailable)
	console.log("Memory available : ",totalMemory)
	console.log("Disk available : ",totalDisk)
	console.log("type : "+type)
	console.log("Used CPU available : ",totalCPUsAllocated)
	console.log("Used Memory available : ",totalMemoryUsed)
	console.log("Used Disk available : ",usedDisk)
</script>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/VMs.css">
	<script src="js/table2excel.js"></script>
	<title>VMs</title>
</head>
<body>
	<div class="infos">
		<div id="infos_top">
			<img src="images/logo2.png">
			<h2>Cluster Capacity Planning Tool</h2>
			<button><img src="images/menu.png"></button>
		</div>
		<div class="dashBoard">
			<a href="dashboard.php">
			<img src="images/house.png">
			<h2>Dashboard</h2>
			</a>
		</div>
		<div class="newTask">
			<img src="images/bolt2.png">
			<h2>Start a New Task</h2>
			<button onclick="displayAddItems()"><img src="images/arrowDown.png"></button>
		</div>
		<div class="addItems">
			<button onclick="addNodes()">Add Node</button>
			<button onclick="addVMs()">Add VM</button>
		</div>
		<div id="bottom">
			<img src="images/employee_image.jfif">
			<div id="welcome">
				<h4>Welcome,</h4>
				<h3><?= $_SESSION['email'];?></h3>
			</div>
			<a href="includes/signOut_inc.php" >Log Out</a>
		</div>
	</div>
	<div class="data">
		
		<div class="container">
			<div class="top">

				<div id="infos">
					<?php 
						echo "<table class='clusterInformations'>";
							echo "<thead><td colspan='2'>Cluster Original Informations</td></thead>";
							echo "<tbody>";
								echo "<tr><td>Cluster Name</td><td>".$_GET['building']."</td></tr>";
								echo "<tr><td>Total Nodes</td><td>".$totalHost."</td></tr>";
								$type = ($totalMemory/$totalHost == 560)? "Large" : "Small";
								echo "<tr><td>Nodes Type</td><td>".$type."</td></tr>";
								echo "<tr><td>Total VMs</td><td>".$totalVMs."</td></tr>";
								$cpuUsage = round($totalCPUsAllocated*100/$totalCPUsAvailable);
								echo "<tr><td>CPUs Usage</td><td>".$cpuUsage."%</td></tr>";
								$memoryUsage = round($totalMemoryUsed*100/$totalMemory);
								echo "<tr><td>Memory Usage</td><td>".$memoryUsage."%</td></tr>";
								echo "<tr><td>Disk Usage</td><td>".$diskUsage."%</td></tr>";
								echo ($diskUsage<70 && $cpuUsage<100)? "<tr><td>Status</td><td style='color:#7BCC70'>Good</td></tr>" : "<tr><td>Status</td><tdstyle='color:#DE3163'> Bad</td></tr>";
							echo "</tbody>";
						echo "</table>";
					?>

				</div>
				<div id="infos">
					<?php 
						echo "<table class='clusterInformationsUpdated clusterInformations'>";
							echo "<thead><td colspan='2'>Cluster Updated Informations</td></thead>";
							echo "<tbody>";
								echo "<tr><td>Cluster Name</td><td>".$_GET['building']."</td></tr>";
								echo "<tr><td>Total Nodes</td><td>".$totalHost."</td></tr>";
								$type = ($totalMemory/$totalHost == 560)? "Large" : "Small";
								echo "<tr><td>Nodes Type</td><td>".$type."</td></tr>";
								echo "<tr><td>Total VMs</td><td>".$totalVMs."</td></tr>";
								$cpuUsage = round($totalCPUsAllocated*100/$totalCPUsAvailable);
								echo "<tr><td>CPUs Usage</td><td>".$cpuUsage."%</td></tr>";
								$memoryUsage = round($totalMemoryUsed*100/$totalMemory);
								echo "<tr><td>Memory Usage</td><td>".$memoryUsage."%</td></tr>";
								echo "<tr><td>Disk Usage</td><td>".$diskUsage."%</td></tr>";
								echo ($diskUsage<70 && $cpuUsage<100)? "<tr><td>Status</td><td style='color:#7BCC70'>Good</td></tr>" : "<tr><td>Status</td><tdstyle='color:#DE3163'> Bad</td></tr>";
							echo "</tbody>";
						echo "</table>";
					?>
 
				</div>
				<div id="whatIf">
					<?php 
						echo "<table class='whatif'>";
							echo "<thead><td colspan='2'>What If?</td></thead>";
							echo "<tbody>";
								echo "<tr><td colspan='2'>n-1</td></tr>";
								$newTotalHost =$totalHost-1;
								$newTotalCPUsAvailable = $totalCPUsAvailable - 32;
								$newTotalMemory = $totalMemory - (($type == 'Large')? 560:384);
								$newTotalDisk = $totalDisk - (($type == 'Large')? 18000:15000);
								$newDiskUsage = round($usedDisk*100/$newTotalDisk);
								echo "<tr><td>Total Nodes</td><td>".$newTotalHost."</td></tr>";
								$newCpuUsage = round($totalCPUsAllocated*100/$newTotalCPUsAvailable);
								echo "<tr><td>CPUs Usage</td><td>".$newCpuUsage."%</td></tr>";
								$newMemoryUsage = round($totalMemoryUsed*100/$newTotalMemory);
								echo "<tr><td>Memory Usage</td><td>".$newMemoryUsage."%</td></tr>";
								echo "<tr><td>Disk Usage</td><td>".$newDiskUsage."%</td></tr>";
								echo ($newDiskUsage<70 || $newCpuUsage<100)? "<tr><td >Status</td><td style='color:#7BCC70'>Good</td></tr>" : "<tr><td>Status</td><td style='color:#DE3163'>Bad</td><tr>";
							echo "</tbody>";
						echo "</table>";
							if ($newTotalHost >3){
								echo "<table class='whatif'>";
									echo "<thead><td colspan='2'>What If?</td></thead>";
									echo "<tbody>";
										echo "<tr><td colspan='2'>n-2</td></tr>";
									$newTotalHost -=1;
									$newTotalCPUsAvailable -= 32;
									$newTotalMemory -= (($type == 'Large')? 560:384);
									$newTotalDisk -= (($type == 'Large')? 18000:15000);
									$newDiskUsage = round($usedDisk*100/$newTotalDisk);
									echo "<tr><td>Total Nodes</td><td>".$newTotalHost."</td></tr>";
									$newCpuUsage = round($totalCPUsAllocated*100/$newTotalCPUsAvailable);
									echo "<tr><td>CPUs Usage</td><td>".$newCpuUsage."%</td></tr>";
									$newMemoryUsage = round($totalMemoryUsed*100/$newTotalMemory);
									echo "<tr><td>Memory Usa</td><td>".$newMemoryUsage."%</td></tr>";
									echo "<tr><td>Disk Usage</td><td>".$newDiskUsage."%</td></tr>";
									echo ($newDiskUsage<70 || $newCpuUsage<100)? "<tr><td>Status</td><td style='color:#7BCC70'>Good</td></tr>" : "<tr><td>Status</td><td style='color:#DE3163'>Bad</td></tr>";
									echo "</tbody>";
								echo "</table>";
							}
					?>
				</div>
			</div>
			<div id="afterTop" style="visibility: hidden;">
				<h1>AFTER ADDING X NODES</h1>
				<button onclick="closeNewStats()">X</button>
			</div>
			<div class="middle">
				<div id="buttons">
					<button onclick="update()" id="delete">Delete Selected VMs</button>
					<button onclick="location.reload()" id="reload">Reload</button>
					<button onclick="exportPDF()" id="pdf"><img src="images/pdf.png"></button>
					<button onclick="exportEXCEL()" id="excel"><img src="images/excel.png"></button>
				</div>
			</div>
			<table id="vms">
				<thead>
					<th>Select</th>
					<th>Server Name</th>
					<!-- <th>Parent Cluster</th>
					<th>Parent Center</th> -->
					<th>isPowered</th>
					<th>Guest OS</th>
					<th>IP address</th>
					<th>vCPUs</th>
					<th>Memory(GB)</th>
					<th>Disk space(GB)</th>
					<th>Hardware</th>
					<!-- <th>Tools Status</th>
					<th>Tools State</th> -->
					<th>Tools Version</th>
				</thead>
			</table>
		</div>
	</div>
	<script type="text/javascript" src="js/fillVMs.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
</body>
</html>