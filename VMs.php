<?php
session_start();
if(!isset($_SESSION['email'])){
	header('Location: login.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/VMs.css">
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
		<?php 
			include_once "includes/db_inc.php";
			include_once "includes/functions_inc.php"; 
			$vms = getVMs($conn,$_GET['building']);
			$clusterInfos = getClusterInfos($conn,$_GET['building']);
			$type = "";
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
		<div class="container">
			<div class="top">
				<div id="infos">
					<?php 
						echo "<table class='clusterInformations'>";
							echo "<thead><td colspan='2'>Cluster Informations</td></thead>";
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
					<button onclick="reload()" id="reload">Reload</button>
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
	<script type="text/javascript">
		//make the table dynamic
		
		vms = JSON.parse('<?= $vms;?>');
		let parent = document.getElementById('vms');
		//trs
		let tbody = document.createElement('tbody');
		for(let i=0;i<vms.length;i++){
			let tr = document.createElement('tr');

			let td0 = document.createElement('td');
			let innertd0 = document.createElement('input')
			innertd0.setAttribute('type','checkbox')
			innertd0.setAttribute('id',i)
			td0.appendChild(innertd0)
			let td = document.createElement('td');
			td.innerHTML = vms[i].serverName
			let td1 = document.createElement('td');
			td1.innerHTML = vms[i].parentCluster;
			let td2 = document.createElement('td');
			td2.innerHTML = vms[i].parentCenter;
			let td3 = document.createElement('td');
			let innerdiv = document.createElement('div');
			if(vms[i].isPowered == '1'){
				innerdiv.setAttribute('style',"height: 25px;width: 25px;background-color: #77dd77;margin: auto;border-radius: 50%;");
			}else{
				innerdiv.setAttribute('style',"height: 25px;width: 25px;background-color: #A70D2A;margin: auto;border-radius: 50%;");
			}
			td3.appendChild(innerdiv);
			let td4 = document.createElement('td');
			td4.innerHTML = vms[i].guestOS;
			let td5 = document.createElement('td');
			td5.innerHTML = vms[i].ipAddress;
			let td6 = document.createElement('td');
			td6.innerHTML = vms[i].vCPU
			let td7 = document.createElement('td');
			td7.innerHTML = vms[i].memory;
			let td8 = document.createElement('td');
			td8.innerHTML = vms[i].diskSpace;
			let td9 = document.createElement('td');
			td9.innerHTML = vms[i].hardware;
			let td10 = document.createElement('td');
			td10.innerHTML = vms[i].toolsStaus;
			let td11 = document.createElement('td');
			td11.innerHTML = vms[i].toolsState;
			let td12 = document.createElement('td');
			td12.innerHTML = vms[i].toolsVersion;
			

			tr.appendChild(td0);
			tr.appendChild(td);
			//tr.appendChild(td1);
			//tr.appendChild(td2);
			tr.appendChild(td3);
			tr.appendChild(td4);
			tr.appendChild(td5);
			tr.appendChild(td6);
			tr.appendChild(td7);
			tr.appendChild(td8);
			tr.appendChild(td9);
			//tr.appendChild(td10);
			//tr.appendChild(td11);
			tr.appendChild(td12);
			tbody.appendChild(tr);
		}
		// table.appendChild(tbody);
		parent.appendChild(tbody);
		
	</script>
	<script type="text/javascript">
		let middle = document.querySelector('.middle')
		function update(){
			let checknewStats = document.getElementById("newStats")
			if(checknewStats){
				checknewStats.remove()
			}
			
			s = ""
			let totalVMs = parseInt("<?= $totalVMs;?>");
			let totalCPUsAvailable = parseInt("<?= $totalCPUsAvailable;?>");
			let totalMemoryUsed = parseInt("<?= $totalMemoryUsed;?>");
			let totalDisk = parseInt("<?=$totalDisk;?>");
			let totalCPUsAllocated = parseInt("<?= $totalCPUsAllocated;?>");
			let totalMemory = parseInt("<?= $totalMemory;?>");
			let usedDisk = parseInt("<?=$usedDisk;?>");
			// console.log("totalVMs:",totalVMs)
			// console.log("totalCPUsAvailable:",totalCPUsAvailable)
			// console.log("totalMemoryUsed:",totalMemoryUsed)
			// console.log("usedDisk:",usedDisk)
			let checkBoxes = document.querySelectorAll('input[type="checkbox"]')
			checkBoxes.forEach(checkBox =>{
				if(checkBox.checked){
					totalVMs--
					totalCPUsAllocated -= vms[checkBox.id].vCPU
					totalMemoryUsed -= vms[checkBox.id].memory
					usedDisk -= vms[checkBox.id].diskSpace
					s+=vms[checkBox.id].serverName+","
				}
			})
			let tbl = document.createElement('table')
			tbl.setAttribute('id','newStats')
			let thd = document.createElement('thead')
			let th1 = document.createElement('th')
			th1.setAttribute('width','10%')
			th1.innerHTML = "Total VMs"
			let th10 = document.createElement('th')
			th10.setAttribute('width','30%')
			th10.innerHTML = "Deleted VMs"
			let th2 = document.createElement('th')
			th2.setAttribute('width','15%')
			th2.innerHTML = "CPUs Usage"
			let th3 = document.createElement('th')
			th3.setAttribute('width','15%')
			th3.innerHTML = "Memory Usag4"
			let th4 = document.createElement('th')
			th4.setAttribute('width','15%')
			th4.innerHTML = "Disk Usage"
			let th5 = document.createElement('th')
			th5.setAttribute('width','15%')
			th5.innerHTML = "Status"
			thd.appendChild(th1)
			thd.appendChild(th10)
			thd.appendChild(th2)
			thd.appendChild(th3)
			thd.appendChild(th4)
			thd.appendChild(th5)

			let tbd = document.createElement('tbody')
			let trr = document.createElement('tr')
			let tb1 = document.createElement('td')
			tb1.setAttribute('width','10%')
			tb1.innerHTML = totalVMs
			let tb10 = document.createElement('td')
			tb10.setAttribute('width','30%')
			let p = document.createElement('p')
			p.innerHTML = s
			tb10.appendChild(p)
			let tb2 = document.createElement('td')
			tb2.setAttribute('width','15%')
			tb2.innerHTML = Math.round(totalCPUsAllocated*100/totalCPUsAvailable)+'%'
			let tb3 = document.createElement('td')
			tb3.setAttribute('width','15%')
			tb3.innerHTML = Math.round(totalMemoryUsed*100/totalMemory)+'%'
			let tb4 = document.createElement('td')
			tb4.setAttribute('width','15%')
			tb4.innerHTML = Math.round(usedDisk*100/totalDisk)+'%'
			let tb5 = document.createElement('td')
			tb5.setAttribute('width','15%')
			tb5.innerHTML = "Status"
			trr.appendChild(tb1)
			trr.appendChild(tb10)
			trr.appendChild(tb2)
			trr.appendChild(tb3)
			trr.appendChild(tb4)
			trr.appendChild(tb5)
			tbd.appendChild(trr)
			tbl.appendChild(thd)
			tbl.appendChild(tbd)

			//let par = document.querySelector(".middle")
			middle.appendChild(tbl)
		}
		function displayAddItems(){
			let p = document.querySelector('.addItems')
			if(window.getComputedStyle(p).visibility === "hidden"){
				p.style.visibility = 'visible'
			}
			else{
				p.style.visibility = 'hidden'
			}
		}
		function unselectAll(){
			let checkBoxes = document.querySelectorAll('input[type="checkbox"]')
			checkBoxes.forEach(checkBox =>{
				checkBox.checked = false
			})
		}
		function reload(){
			let checknewStats2 = document.getElementById("newStats")
			unselectAll()
			if(checknewStats2){
				//console.log(checknewStats2)
				middle.removeChild(checknewStats2)
			}
		}
	</script>
	<script type="text/javascript">
		//console.log("clicked")
		function addNodes(){
			let type = "<?=$type;?>"
			window.scrollTo(0, 0);
			let totalHost = "<?= $totalHost;?>"
			let fath = document.createElement('div')
			fath.setAttribute('class','father')
			let cont = document.createElement('div')
			cont.setAttribute('id','inner')
			let title = document.createElement('h2')
			title.innerHTML = "Add Nodes"
			let l1 = document.createElement('label')
			l1.innerHTML = 'Number of Nodes:'
			let inp1 = document.createElement('input')
			inp1.setAttribute('type','number')
			inp1.setAttribute('min','1')
			let d = document.createElement('div')
			d.appendChild(l1);d.appendChild(inp1)
			let inf = document.createElement('p')

			inf.innerHTML = "This Cluster is using "+type+"typed Nodes. Which means:<br>"
			inf.innerHTML +="Each Node will have<br>"
			inf.innerHTML += "---->CPUs : 16<br>"
			inf.innerHTML += (type == 'Large')?"---->Memory : 560 GB<br>":"---->Memory : 384 GB<br>"
			inf.innerHTML += (type == 'Large')?"---->Disk : 18 TB<br>":"---->Disk : 15 TB<br>"
			
			let data = "addN()"
			
			let btn = document.createElement('button')
			btn.setAttribute('onclick',data)
			btn.setAttribute('id','addNodesBtn')
			btn.innerHTML = "add"

			let close = document.createElement('button')
			close.setAttribute('id','close')
			close.setAttribute('onclick','closeIt()')
			close.innerHTML = "X"

			cont.appendChild(title)
			cont.appendChild(d)
			cont.appendChild(inf)
			cont.appendChild(btn)
			cont.appendChild(close)
			fath.appendChild(cont)
			
			document.body.appendChild(fath)

		}
		function addVMs(){
			let type = "<?=$type;?>"
			window.scrollTo(0, 0);
			let totalHost = "<?= $totalHost;?>"
			let fath = document.createElement('div')
			fath.setAttribute('class','father')
			let cont = document.createElement('div')
			cont.setAttribute('id','inner')
			let title = document.createElement('h2')
			title.innerHTML = "Add Nodes"
			let l1 = document.createElement('label')
			l1.innerHTML = 'Number of VMs:'
			let inp1 = document.createElement('input')
			inp1.setAttribute('type','number')
			inp1.setAttribute('min','1')
			inp1.setAttribute('value',"1")
			let d = document.createElement('div')
			d.appendChild(l1);d.appendChild(inp1)
			let inf = document.createElement('p')

			inf.innerHTML = "This Cluster is using "+type+"typed Nodes. Which means:<br>"
			inf.innerHTML +="Each Node will have<br>"
			inf.innerHTML += "---->CPUs : 16<br>"
			inf.innerHTML += (type == 'Large')?"---->Memory : 560 GB<br>":"---->Memory : 384 GB<br>"
			inf.innerHTML += (type == 'Large')?"---->Disk : 18 TB<br>":"---->Disk : 15 TB<br>"
			
			let btn = document.createElement('button')
			btn.innerHTML = "add"

			let close = document.createElement('button')
			close.setAttribute('id','close')
			close.setAttribute('onclick','closeIt()')
			close.innerHTML = "X"

			cont.appendChild(title)
			cont.appendChild(d)
			cont.appendChild(inf)
			cont.appendChild(btn)
			cont.appendChild(close)
			fath.appendChild(cont)
			
			document.body.appendChild(fath)

		}
		function closeIt(){
			f = document.getElementsByTagName('body')
			let ff = document.querySelector('.father')
			f[0].removeChild(ff)
		}
		function addN(){
			let type = "<?=$type;?>"
			let n = parseInt(document.querySelector("input[type='number']").value)
			closeIt()
			
			let newT = document.createElement('table')
			newT.setAttribute('class','clusterInformations2')

			let newTHead = document.createElement('thead')
			let newTh = document.createElement('th')
			newTh.innerHTML = "After Adding "+n+" Nodes"
			newTh.setAttribute('colspan','2')
			newTHead.appendChild(newTh)

			let newTbody = document.createElement('tbody')
			let newTr1 = document.createElement('tr')
			let newTd11 = document.createElement('td')
			newTd11.innerHTML = "<?=$_GET['building'];?>"
			let newTd12 = document.createElement('td')
			newTd12.innerHTML = parseInt("<?=$totalHost;?>") + n
			newTr1.appendChild(newTd11)
			newTr1.appendChild(newTd12)

			let newTr2 = document.createElement('tr')
			let newTd21 = document.createElement('td')
			newTd21.innerHTML = "Cluster Name"
			let newTd22 = document.createElement('td')
			newTd22.innerHTML = parseInt("<?=$totalHost;?>") + n
			newTr2.appendChild(newTd21)
			newTr2.appendChild(newTd22)

			let newTr3 = document.createElement('tr')
			let newTd31 = document.createElement('td')
			newTd31.innerHTML = "Total VMs"
			let newTd32 = document.createElement('td')
			newTd32.innerHTML = "<?=$totalVMs;?>"
			newTr3.appendChild(newTd31)
			newTr3.appendChild(newTd32)

			let newTr4 = document.createElement('tr')
			let newTd41 = document.createElement('td')
			newTd41.innerHTML = "CPUs Usage"
			let newTd42 = document.createElement('td')
			let newCPUAvailable = n*32 + parseInt("<?=$totalCPUsAvailable;?>")
			newTd42.innerHTML = Math.round(parseInt("<?=$totalCPUsAllocated;?>")*100/newCPUAvailable)+'%'
			newTr4.appendChild(newTd41)
			newTr4.appendChild(newTd42)

			let newTr5 = document.createElement('tr')
			let newTd51 = document.createElement('td')
			newTd51.innerHTML = "Memory Usage"
			let usedMemory = parseInt("<?=$totalMemoryUsed;?>")
			let newTd52 = document.createElement('td')
			let newTotalMemory = (type == 'Large')?parseInt("<?=$totalMemory;?>") + n*560 : parseInt("<?=$totalMemory;?>") + n*384
			newTd52.innerHTML = Math.round(usedMemory*100/newTotalMemory)+'%'
			newTr5.appendChild(newTd51)
			newTr5.appendChild(newTd52)

			let newTr6 = document.createElement('tr')
			let newTd61 = document.createElement('td')
			newTd61.innerHTML = "Disk Usage"
			let newTd62 = document.createElement('td')
			let newTotalDisk = (type == 'Large')?parseInt("<?=$totalDisk;?>") + n*18000 : parseInt("<?=$totalDisk;?>") + n*15000
			let usedDsk = parseInt("<?=$usedDisk;?>")
			newTd62.innerHTML = Math.round(usedDsk*100/newTotalDisk)+'%'
			newTr6.appendChild(newTd61)
			newTr6.appendChild(newTd62)
			
			let newTr7 = document.createElement('tr')
			let newTd71 = document.createElement('td')
			newTd71.innerHTML = "Status"
			let newTd72 = document.createElement('td')
			newTd72.innerHTML = "Good"
			newTr7.appendChild(newTd71)
			newTr7.appendChild(newTd72)

			newTbody.appendChild(newTr1)
			newTbody.appendChild(newTr2)
			newTbody.appendChild(newTr3)
			newTbody.appendChild(newTr4)
			newTbody.appendChild(newTr5)
			newTbody.appendChild(newTr6)
			newTbody.appendChild(newTr7)

			newT.appendChild(newTHead)
			newT.appendChild(newTbody)

			let dd = document.querySelector('#afterTop')
			dd.appendChild(newT)

			// console.log("New CPU available : ",newCPUAvailable)
			// console.log("New Memory available : ",newTotalMemory)
			// console.log("New Disk available : ",newTotalDisk)
			
		}

		let div = document.getElementById('afterTop')
		div.addEventListener("DOMNodeInserted", function () {
		    if(div.children.length <= 2){
					div.style.visibility = 'hidden'
				}
				else{
		 			div.style.visibility = 'visible'
				}
		});
		
		function closeNewStats(){
			let tables = document.querySelectorAll('.clusterInformations2')
			tables.forEach(table =>{
				table.remove()
			})
			div.style.visibility = 'hidden'
		}
		
	</script>

</body>
</html>