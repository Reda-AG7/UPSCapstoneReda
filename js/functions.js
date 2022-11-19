function displayAddItems(){
	let p = document.querySelector('.addItems')
	if(window.getComputedStyle(p).visibility === "hidden"){
		p.style.visibility = 'visible'
	}
	else{
		p.style.visibility = 'hidden'
	}
}



function update(){
	let middle = document.querySelector('.middle')
	let t = document.querySelector('.clusterInformationsUpdated')
	let checknewStats = document.getElementById("newStats")
	if(checknewStats){
		checknewStats.remove()
	}
	
	// let totalVMs = parseInt("<?= $totalVMs;?>");
	// let totalCPUsAvailable = parseInt("<?= $totalCPUsAvailable;?>");
	// let totalMemoryUsed = parseInt("<?= $totalMemoryUsed;?>");
	// let totalDisk = parseInt("<?=$totalDisk;?>");
	// let totalCPUsAllocated = parseInt("<?= $totalCPUsAllocated;?>");
	// let totalMemory = parseInt("<?= $totalMemory;?>");
	// let usedDisk = parseInt("<?=$usedDisk;?>");
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
			deletedVMs+=vms[checkBox.id].serverName+","
			checkBox.parentElement.parentElement.remove()
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
	//update the updated table data
	t.children[1].children[3].lastChild.innerHTML = totalVMs
	let tb10 = document.createElement('td')
	tb10.setAttribute('width','30%')
	let p = document.createElement('p')
	p.innerHTML = (deletedVMs.length > 0) ? deletedVMs.slice(0,-1) : s
	tb10.appendChild(p)
	let tb2 = document.createElement('td')
	tb2.setAttribute('width','15%')
	tb2.innerHTML = Math.round(totalCPUsAllocated*100/totalCPUsAvailable)+'%'
	t.children[1].children[4].lastChild.innerHTML = tb2.innerHTML
	let tb3 = document.createElement('td')
	tb3.setAttribute('width','15%')
	tb3.innerHTML = Math.round(totalMemoryUsed*100/totalMemory)+'%'
	t.children[1].children[5].lastChild.innerHTML = tb3.innerHTML
	let tb4 = document.createElement('td')
	tb4.setAttribute('width','15%')
	tb4.innerHTML = Math.round(usedDisk*100/totalDisk)+'%'
	t.children[1].children[6].lastChild.innerHTML = tb4.innerHTML
	let tb5 = document.createElement('td')
	tb5.setAttribute('width','15%')
	tb5.innerHTML = "Good"
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


function addNodes(){
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

function closeIt(){
	f = document.getElementsByTagName('body')
	let ff = document.querySelector('.father')
	f[0].removeChild(ff)
}
function addN(){
	let t = document.querySelector('.clusterInformationsUpdated')
	let n = parseInt(document.querySelector("input[type='number']").value)
	closeIt()

	t.children[1].children[1].lastChild.innerHTML = parseInt(totalHost) + n

	totalCPUsAvailable += (n*32)
	t.children[1].children[4].lastChild.innerHTML = Math.round(totalCPUsAllocated*100/totalCPUsAvailable)+'%'

	
	totalMemory = (type == 'Large')? (totalMemory + n*560) : (totalMemory + n*384)
	t.children[1].children[5].lastChild.innerHTML = Math.round(totalMemoryUsed*100/totalMemory)+'%'
	
	totalDisk = (type == 'Large')? (totalDisk + n*18000) : (totalDisk + n*15000)
	t.children[1].children[6].lastChild.innerHTML = Math.round(usedDisk*100/totalDisk)+'%'
	
	console.log("After adding ("+n+" nodes) CPU available : ",totalCPUsAvailable)
	console.log("After adding ("+n+" nodes) Memory available : ",totalMemory)
	console.log("After adding ("+n+" nodes) Disk available : ",totalDisk)
	
}

	
function addToVMs(){
	let serverNs = document.querySelectorAll('input[name="serverN"]')
	let cpus = document.querySelectorAll('input[name="cpus"]')
	let memory = document.querySelectorAll('input[name="memory"]')
	let disk = document.querySelectorAll('input[name="disk"]')
	let totCpus = 0; totMemory = 0; totDisk = 0;
	closeIt()
	let fathe = document.getElementById('VMsBody')
	console.log('cpus length: '+cpus.length)
	console.log(fathe)
	for(let i=0;i<cpus.length;i++){
		totCpus+=parseInt(cpus[i].value)
		totMemory+=parseInt(memory[i].value)
		totDisk+=parseInt(disk[i].value)
		let tr = document.createElement('tr')
		let td0 = document.createElement('td');
		let innertd0 = document.createElement('input')
		innertd0.setAttribute('type','checkbox')
		innertd0.setAttribute('id',i)
		td0.appendChild(innertd0)
		let td = document.createElement('td');
		td.innerHTML = serverNs[i].value
		// let td1 = document.createElement('td');
		// td1.innerHTML = "";
		// let td2 = document.createElement('td');
		// td2.innerHTML = "";
		let td3 = document.createElement('td');
		let td4 = document.createElement('td');
		td4.innerHTML = "";
		let td5 = document.createElement('td');
		td5.innerHTML = "";
		let td6 = document.createElement('td');
		td6.innerHTML = cpus[i].value
		let td7 = document.createElement('td');
		td7.innerHTML = memory[i].value;
		let td8 = document.createElement('td');
		td8.innerHTML = disk[i].value;
		let td9 = document.createElement('td');
		td9.innerHTML = ""
		// let td10 = document.createElement('td');
		// td10.innerHTML = ""
		// let td11 = document.createElement('td');
		// td11.innerHTML = ""
		let td12 = document.createElement('td');
		td12.innerHTML = ""
		tr.appendChild(td0)
		tr.appendChild(td);
		// tr.appendChild(td1);
		// tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		tr.appendChild(td5);
		tr.appendChild(td6);
		tr.appendChild(td7);
		tr.appendChild(td8);
		tr.appendChild(td9);
		// tr.appendChild(td10);
		// tr.appendChild(td11);
		tr.appendChild(td12);

		fathe.appendChild(tr);

	}
	let t = document.querySelector('.clusterInformationsUpdated')
	console.log("cpus.length"+cpus.length)
	t.children[1].children[3].lastChild.innerHTML = totalVMs + cpus.length

	totalCPUsAllocated += totCpus
	t.children[1].children[4].lastChild.innerHTML = Math.round(totalCPUsAllocated*100/totalCPUsAvailable)+'%'

	
	totalMemoryUsed += totMemory
	t.children[1].children[5].lastChild.innerHTML = Math.round(totalMemoryUsed*100/totalMemory)+'%'
	
	usedDisk += totDisk
	t.children[1].children[6].lastChild.innerHTML = Math.round(usedDisk*100/totalDisk)+'%'
	status = checkStatus()
	t.children[1].children[7].lastChild.innerHTML = (status.length > 0) ? "Warning<br>"+status : "Good"
	t.children[1].children[7].lastChild.style.color = (status.length > 0) ? "red" : "green"
	console.log(totCpus)
	console.log(totMemory)
	console.log(totDisk)
	console.log("Used after adding VMs CPU available : ",totalCPUsAllocated)
	console.log("Used after adding VMs Memory available : ",totalMemoryUsed)
	console.log("Used after adding VMs Disk available : ",usedDisk)

}

//add extra Tr for more VM
function addTr(){
	let tbody = document.getElementById('addVMTable')
	let tr = document.createElement('tr')
	let td1 = document.createElement('td')
	let inp1 = document.createElement('input')
	inp1.setAttribute('name','serverN')
	inp1.setAttribute('type','text')
	td1.appendChild(inp1)

	let td2 = document.createElement('td')
	let inp2 = document.createElement('input')
	inp2.setAttribute('name','cpus')
	inp2.setAttribute('type','number')
	inp2.setAttribute('max',(totalCPUsAvailable - totalCPUsAllocated))
	td2.appendChild(inp2)

	let td3 = document.createElement('td')
	let inp3 = document.createElement('input')
	inp3.setAttribute('name','memory')
	inp3.setAttribute('type','number')
	inp3.setAttribute('max',(totalMemory - totalMemoryUsed))
	td3.appendChild(inp3)

	let td4 = document.createElement('td')
	let inp4 = document.createElement('input')
	inp4.setAttribute('name','disk')
	inp4.setAttribute('type','number')
	inp4.setAttribute('max',(totalDisk - usedDisk))
	td4.appendChild(inp4)

	tr.appendChild(td1)
	tr.appendChild(td2)
	tr.appendChild(td3)
	tr.appendChild(td4)

	tbody.appendChild(tr)
}

function subTr(){
	let tbody = document.getElementById('addVMTable')
	let lastChild = tbody.lastChild
	if(lastChild != tbody.firstChild){
		lastChild.remove()
	}
}
function addVMs(){
	window.scrollTo(0, 0);
	let title = document.createElement('h1')
	title.innerHTML = "Add VMs"

	let close = document.createElement('button')
	close.setAttribute('id','close')
	close.setAttribute('onclick','closeIt()')
	close.innerHTML = "X"

	let fath = document.createElement('div')
	fath.setAttribute('class','father')

	let container = document.createElement('div')
	container.setAttribute('id','inner2')

	let table = document.createElement('table')
	let thead = document.createElement('thead')
	let th1 = document.createElement('th')
	th1.innerHTML = "Server Name"

	// let th2 = document.createElement('th')
	// th2.innerHTML = "Guest OS"

	// let th3 = document.createElement('th')
	// th3.innerHTML = "IP Address"

	let th4 = document.createElement('th')
	th4.innerHTML = "vCPUs"

	let th5 = document.createElement('th')
	th5.innerHTML = "Memory(GB)"

	let th6 = document.createElement('th')
	th6.innerHTML = "Disk Space(GB)"

	thead.appendChild(th1);
	// thead.appendChild(th2);thead.appendChild(th3);
	thead.appendChild(th4);thead.appendChild(th5);thead.appendChild(th6)


	let tbody = document.createElement('tbody')
	tbody.setAttribute('id','addVMTable')
	
	table.appendChild(thead)
	table.appendChild(tbody)

	let addButton = document.createElement('button')
	addButton.setAttribute('onclick','addTr()')
	addButton.setAttribute('id','addVM')
	addButton.innerHTML = "+"

	let subButton = document.createElement('button')
	subButton.setAttribute('onclick','subTr()')
	subButton.setAttribute('id','subVM')
	subButton.innerHTML = "-"

	let div = document.createElement('div')
	div.setAttribute('id','addSub')
	div.appendChild(subButton)
	div.appendChild(addButton)


	let changeButton = document.createElement('button')
	changeButton.setAttribute('onclick','addToVMs()')
	changeButton.setAttribute('name','addToVMs')
	changeButton.innerHTML = 'Add'

	container.appendChild(title)
	container.appendChild(close)
	container.appendChild(table)
	container.appendChild(div)
	container.appendChild(changeButton)

	fath.appendChild(container)
	document.body.appendChild(fath)
	addTr()


}

function checkStatus(){
	let status = ""
	// Slack space for disk
	if(Math.round((usedDisk*100)/totalDisk)>70){
		status+="Disk is overloaded<br>"
	}
	// Slack space for Memory
	if(Math.round((totalMemoryUsed*100)/totalMemory)>100){
		status+="Memory exceeded 100%<br>"
	}
	if(Math.round((totalCPUsAllocated*100)/totalCPUsAvailable)>125){
		status+="\nCPU exceeded 125%"
	}
	return status
}

function exportEXCEL(){
	// var table2excel = new Table2Excel();
	// table2excel.export(document.querySelectorAll("#vms"));

}
function exportPDF(){
	// var table2excel = new Table2Excel();
	// table2excel.export(document.querySelectorAll("#vms"));

}