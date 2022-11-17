//make the table dynamic
		
let parent = document.getElementById('vms');
//trs
let tbody = document.createElement('tbody');
tbody.setAttribute('id','VMsBody')
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

