////  COMBO    //////////////////
var catId = [];
var catText=[];
var catColor= [];
var phpPath = "../../php/";
document.getElementById("dlButton").disabled = true;
document.getElementById("dlButton").style.opacity = 0.5;
var token = "";

loadCategories();
function loadCategories(){
	var http_req = new XMLHttpRequest();
	var url = phpPath+"get_category.php";

	http_req.open("GET", url, true);

	http_req.onreadystatechange = function() {
		if (http_req.readyState == 4 && http_req.status == 200) {
			if(http_req.responseText == "session_closed")
				window.location.replace("http://"+location.hostname+"/login.php?location="+location.pathname);
			var res = JSON.parse(http_req.responseText);
			for(i = 0; i < res.length; i++){
				catId[i] = parseInt(res[i].id);
				catText[i] = res[i].Category;
				catColor[i] = res[i].Color;
			}
			initCombo();
		}
	};
	http_req.send();
}

function initCombo(){
	$("#combo").append("<option></option>");
	for (i = 0; i < catId.length; i++) {
		appendToCombo(catText[i],catId[i]);
	}


	function appendToCombo(category,type){
		$("#combo").append("<option value=\""+type+"\">"+category+"</option>");
	}


	$(".js-basic-single").select2({ width: '100px' });
	
	$('#combo').select2({placeholder: 'Select a category'});

}
function onComboChanged(){
	getNbrInCat();
}

function getNbrInCat(){
	
	var data= {};
	var combo = document.getElementById("combo");
	data["category"]=combo.value;
	var http_req = new XMLHttpRequest();
	var url = phpPath+"post_nbrImgInCat.php";

	http_req.open("POST", url, true);

	http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http_req.onreadystatechange = function() {
		if(http_req.readyState == 4 && http_req.status == 200) {
			console.log(location.hostname);
			console.log(window.location.pathname);
			if(http_req.responseText == "session_closed")
				window.location.replace("http://"+location.hostname+"/login.php?location="+location.pathname);
			var res = JSON.parse(http_req.responseText);
			console.log(res[0]);
			document.getElementById('imgCounter').innerHTML = res[0]+" Image(s) found";
		}
	}
	var json = JSON.stringify(data);
	http_req.send("data=" +json);
}
///////////////////////////////

function onExportClicked(){
	var selectedCat = document.getElementById("combo").value;
	console.log("Export : "+selectedCat);
	var data= {};
	data["category"]=selectedCat;
	var http_req = new XMLHttpRequest();
	var url = phpPath+"post_export.php";

	http_req.open("POST", url, true);

	http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http_req.onreadystatechange = function() {
		if(http_req.readyState == 4 && http_req.status == 200) {
			if(http_req.responseText == "session_closed")
				window.location.replace("http://"+location.hostname+"/login.php?location="+location.pathname);
			console.log(http_req.responseText);
			console.log("Response Over");
			var res = JSON.parse(http_req.responseText);
			if(typeof res === 'object' && "link" in res){
				document.getElementById("dlButton").disabled = false;
				document.getElementById("dlButton").style.opacity = 1;
				token = res.link;
				document.getElementById('imgCounter').innerHTML = "Download ready";
				//document.getElementById('dlLink').innerHTML = "<a href='../download.php?id="+token+"'>Download ready</a>";
			}
			else if( res == "No file found")
				document.getElementById('imgCounter').innerHTML = "No file";
			
		}
	}
	var json = JSON.stringify(data);
	http_req.send("data=" +json);
	document.getElementById('imgCounter').innerHTML = "Preparing download...";
}
function onDlClicked(){
	document.getElementById("dlButton").disabled = true;
	document.getElementById("dlButton").style.opacity = 0.5;
	window.location.href = "../download.php?id="+token;
	document.getElementById('imgCounter').innerHTML = "";
}
window.onbeforeunload = function(e) {
	freeDL (token);
		console.log("Free " +token);
};
function freeDL (token){
		var data= {};
		data["token"]=token;
		////////////////////// POST  //////////////
		var http_req = new XMLHttpRequest();
		var url = phpPath+"post_freeDL.php";

		http_req.open("POST", url, true);

		//Send the proper header information along with the request
		http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		
		http_req.onreadystatechange = function() {//Call a function when the state changes.
			if(http_req.readyState == 4 && http_req.status == 200) {
				if(http_req.responseText == "session_closed")
					window.location.replace("http://"+location.hostname+"/login.php?location="+location.pathname);
				console.log(http_req.responseText);
			}
		}
		var json = JSON.stringify(data);
		http_req.send("data=" +json);
		////////////////////////////////////////////////
}