var imgPathList=[];
var imgPathListIndex = 0;
var imgPath = "../../img/";
var srcName = 0;

var imageLoaded = false;
var rectanglesLoaded = false;
var rectanglesList = [];
var currentRectangle = null;

////////////GET IMG FROM SERVER//////
loadImages();
loadRects();
function handlerLoadsDone(){
	if(imageLoaded && rectanglesLoaded){
		if (imgPathList.length>0)
			addImage();
		imageLoaded = false;
		rectanglesLoaded = false;
	}
}
function loadRects(){
	var http_get_rects = new XMLHttpRequest();
	var url = "get_rects_tobeVerified.php";

	http_get_rects.open("GET", url, true);

	http_get_rects.onreadystatechange = function() {
		if (http_get_rects.readyState == 4 && http_get_rects.status == 200) {
			console.log("reponse rect done");
			console.log(http_get_rects.responseText);
			if(http_get_rects.responseText!=""){
				var res = JSON.parse(http_get_rects.responseText);
				rectanglesList = res;
			}
			else rectanglesList = [];
			rectanglesLoaded = true;
			handlerLoadsDone();
		}
	};
	http_get_rects.send();
}
function loadImages(){
	var http_get_img = new XMLHttpRequest();
	var url = "get_img_tobeVerified.php";

	http_get_img.open("GET", url, true);

	http_get_img.onreadystatechange = function() {
		if (http_get_img.readyState == 4 && http_get_img.status == 200) {
			// Action to be performed when the document is read;
			console.log("select img done");
			console.log(http_get_img.responseText);
			if(http_get_img.responseText!=""){
				var res = JSON.parse(http_get_img.responseText);
				imgPathList = res;
			}
			else imgPathList = [];
			imgPathListIndex = 0;
			imageLoaded = true;
			handlerLoadsDone();//addImage();
		}
	};
	http_get_img.send();
}

function addImage(){
	srcName = imgPathList[imgPathListIndex].id;
	var imgName = imgPathList[imgPathListIndex].path;
	var imgToAdd = imgPath+imgName;
	document.getElementById('image').src = imgToAdd;//$('#preview').html("<img id='image' unselectable='on' src='"+imgToAdd+"' />")
	
	var img = document.getElementById('image');

	function loaded() {
	  //alert('loaded')
	  drawRects(srcName);//initSelection();
	  img.removeEventListener('load', loaded);
	  img.removeEventListener('load', error);
	}
	function error() {
		//alert('error');
	}
	if (img.complete) {
	  loaded();
	} else {
	  img.addEventListener('load', loaded)
	  img.addEventListener('error', error)
	}
	
	document.getElementById('imgCounter').innerHTML = "Image "+(imgPathListIndex+1)+" of "+imgPathList.length;
	document.getElementById("moreButton").style = "DISPLAY: none;";
	document.getElementById("RejectButton").style = "DISPLAY: initial;";
	document.getElementById("ValidateButton").style = "DISPLAY: initial;";
}
function drawRects(idImage){
	for(var i = 0; i < rectanglesList.length; ++i){
		reviewedRect = rectanglesList[i];
		if(parseInt(reviewedRect.source) == idImage){
			var refImage = document.getElementById('image');
			var leftImage = refImage.offsetLeft;
			var topImage = refImage.offsetTop ;
			console.log("matched rect found");
			currentRectangle = document.createElement('div');
			currentRectangle.className = 'rectangle';
			var str = reviewedRect.Category;
			var type = reviewedRect.rectType;
			var color = reviewedRect.Color;
			currentRectangle.rectType = type;
			currentRectangle.style.left = (leftImage+parseInt(reviewedRect.rectLeft)) + 'px';
			currentRectangle.style.top = (topImage+parseInt(reviewedRect.rectTop)) + 'px';
			currentRectangle.style.border= "3px solid "+color;
			currentRectangle.style.color= color;
			var text = document.createElement('div');
			var t = document.createTextNode(str);
			text.className = 'rectangleText';
			text.appendChild(t);
			currentRectangle.appendChild(text);
			currentRectangle.style.width = (reviewedRect.rectRight - reviewedRect.rectLeft) + 'px';
			currentRectangle.style.height = (reviewedRect.rectBottom - reviewedRect.rectTop) + 'px';
			(document.getElementById('preview')).appendChild(currentRectangle);
			adaptText();
		}
	}
}
function adaptText(){
	////  X //////
	var refImage = document.getElementById('image');
	var textWidth = currentRectangle.childNodes[0].scrollWidth;
	var leftImage = refImage.offsetLeft - 0;
	if((parseFloat(currentRectangle.style.left) + textWidth) >= (leftImage+refImage.width)){
		currentRectangle.childNodes[0].style.left = -textWidth + 'px';
	}
	else{
		currentRectangle.childNodes[0].style.left = 0 + 'px';
	}
	////  Y //////
	var refImage = document.getElementById('image');
	var textHeight = currentRectangle.childNodes[0].scrollHeight;
	var topImage = refImage.offsetTop;
	if((parseFloat(currentRectangle.style.top) + textHeight) >= (topImage+refImage.height)){
		currentRectangle.childNodes[0].style.top = -textHeight + 'px';
	}
	else{
		currentRectangle.childNodes[0].style.top = 0 + 'px';
	}
}

function nextImage(){
	if(imgPathList.length>0){
		wipeRectangle();
		removeImage();
		freeImage(imgPathList[imgPathListIndex].id);
		imgPathListIndex++;
		if(imgPathListIndex<imgPathList.length)
			addImage();
		else{
			console.log("no more img");
			document.getElementById("moreButton").style = "DISPLAY: initial;";
			document.getElementById("RejectButton").style = "DISPLAY: none;";
			document.getElementById("ValidateButton").style = "DISPLAY: none;";
		}
	}
}

function removeImage(){
	var refImage = document.getElementById('image');
	if(refImage){
		//refImage.remove();
		refImage.src = "";
	}
}
function wipeRectangle(){
	var elements = document.getElementsByClassName("rectangle");
	while(elements.length>0){
		elements[0].remove();
		
	}
}

/////////////////////////

function onValidateClicked(){
	console.log("Validate");
	//nextImage();
	sendData(true);
}

function onRejectClicked(){
	console.log("Reject");
	//nextImage();
	sendData(false);
}

function sendData(validated){
	var data= {};
	data["dataSrc"]=srcName;
	data["validated"]=validated;
	console.log(data);
	
	////////////////////// POST  //////////////
	var http_post_check = new XMLHttpRequest();
	var url = "post_check.php";

	http_post_check.open("POST", url, true);

	//Send the proper header information along with the request
	http_post_check.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http_post_check.onreadystatechange = function() {//Call a function when the state changes.
		if(http_post_check.readyState == 4 && http_post_check.status == 200) {
			//alert(http_post_check.responseText);
			console.log(http_post_check.responseText);
			nextImage();
		}
	}
	var json = JSON.stringify(data);
	http_post_check.send("data=" +json);
	//////////////////////////////////////////////
}
function freeImage (idImage){
		var data= {};
		data["dataSrc"]=idImage;
		////////////////////// POST  //////////////
		var http_post_data = new XMLHttpRequest();
		var url = "../../label/post_freeImage.php";

		http_post_data.open("POST", url, true);

		//Send the proper header information along with the request
		http_post_data.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		
		http_post_data.onreadystatechange = function() {//Call a function when the state changes.
			if(http_post_data.readyState == 4 && http_post_data.status == 200) {
				//alert(http_post_data.responseText);
				console.log(http_post_data.responseText);
			}
		}
		var json = JSON.stringify(data);
		http_post_data.send("data=" +json);
		//////////////////////////////////////////////
}
function onMoreClicked(){
	loadImages();
	loadRects();
	console.log("Load more");
}
window.onbeforeunload = function(e) {
	for(var i = imgPathListIndex; i < imgPathList.length; ++i){
		freeImage (imgPathList[i].id);
		console.log("Free " +imgPathList[i].id);
	}
};
////////////////////////////////////////////



