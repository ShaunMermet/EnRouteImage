////  COMBO    //////////////////
var catId = [];
var catText=[];
var catColor= [];

loadCategories();
function loadCategories(){
	var http_get_cat = new XMLHttpRequest();
	var url = "../../php/get_category.php";

	http_get_cat.open("GET", url, true);

	http_get_cat.onreadystatechange = function() {
		if (http_get_cat.readyState == 4 && http_get_cat.status == 200) {
			var res = JSON.parse(http_get_cat.responseText);
			for(i = 0; i < res.length; i++){
				catId[i] = parseInt(res[i].id);
				catText[i] = res[i].Category;
				catColor[i] = res[i].Color;
			}
			initCombo();
		}
	};
	http_get_cat.send();
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
	var url = "../../php/post_nbrImgInCat.php";

	http_req.open("POST", url, true);

	http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http_req.onreadystatechange = function() {
		if(http_req.readyState == 4 && http_req.status == 200) {
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
	var url = "../../php/post_export.php";

	http_req.open("POST", url, true);

	http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http_req.onreadystatechange = function() {
		if(http_req.readyState == 4 && http_req.status == 200) {
			var res = JSON.parse(http_req.responseText);
			
			
		}
	}
	var json = JSON.stringify(data);
	http_req.send("data=" +json);
}