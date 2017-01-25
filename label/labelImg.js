var imgPathList=[];
var imgPathListIndex = 0;
var imgPath = "../img/";
var srcName = 0;

document.getElementById("openButton").style = "DISPLAY: none;";
//////  upload script  ////
//check if browser supports file api and filereader features
if (window.File && window.FileReader && window.FileList && window.Blob) {

//this function is called when the input loads an image

function renderImage(file){
	var reader = new FileReader();
	reader.onload = function(event){
		the_url = event.target.result
		srcName = 0;
		$('#preview').html("<img id='image' unselectable='on' src='"+the_url+"' />")
		//init selection rectangle on image
		initSelection();
		imgPathList=[the_url];
		imgPathListIndex = 0;
		document.getElementById('imgCounter').innerHTML = "Image "+(imgPathListIndex+1)+" on "+imgPathList.length;
		document.getElementById("moreButton").style = "DISPLAY: none;";
	}

//when the file is read it triggers the onload event above.
	reader.readAsDataURL(file);

}

//watch for change on the 
$("#the-photo-file-field").change(function() {
	console.log("photo file has been chosen")
	//grab the first image in the fileList
	if(this.files[0])
		renderImage(this.files[0])

});

} else {
  alert('The File APIs are not fully supported in this browser.');
}
////////////////////////////////////////


////////////GET IMG FROM SERVER//////
loadImages();
function loadImages(){
	var http_get_img = new XMLHttpRequest();
	var url = "php/get_img_clean.php";

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
			addImage();
		}
	};
	http_get_img.send();
}

function addImage(){
	if(imgPathList.length>0){
		srcName = imgPathList[imgPathListIndex].id;
		var imgName = imgPathList[imgPathListIndex].path;
		var imgToAdd = imgPath+imgName;
		document.getElementById('image').src = imgToAdd;//$('#preview').html("<img id='image' unselectable='on' onresize='"onImgResize()"' src='"+imgToAdd+"' />")
		initSelection();
		document.getElementById('imgCounter').innerHTML = "Image "+(imgPathListIndex+1)+" of "+imgPathList.length;
		document.getElementById("moreButton").style = "DISPLAY: none;";
		document.getElementById("nextButton").style = "DISPLAY: initial;";
	}
}

function onImgResize(){
	console.log("resize");
}
window.addEventListener("resize", function(){
   if(window.innerWidth < 768){
      console.log('narrow');
   }
   else{
       console.log('wide');
   }
});

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
			document.getElementById("nextButton").style = "DISPLAY: none;";
			//loadImages();
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


////  COMBO    //////////////////
//creating categories 

//var catId = [1,2,3,4];
//var catText=["cow","car","person","apple"];
//var catColor= ["#FFF8DC","#00008B","#800080","#DC143C"];
var catId = [];
var catText=[];
var catColor= [];

loadCategories();
function loadCategories(){
	var http_get_cat = new XMLHttpRequest();
	var url = "php/get_category.php";

	http_get_cat.open("GET", url, true);

	http_get_cat.onreadystatechange = function() {
		if (http_get_cat.readyState == 4 && http_get_cat.status == 200) {
			// Action to be performed when the document is read;
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
	for (i = 0; i < catId.length; i++) {
		appendToCombo(catText[i],catId[i]);
	}


	function appendToCombo(category,type){
		console.log("creating "+category)
		$("#combo").append("<option value=\""+type+"\">"+category+"</option>");
	}


	$(".js-basic-single").select2({ width: '100px' });
}
///////////////////////////////


//////  Column 3 button management  ////////

// init
var drawMode =true;
var eraseMode = false;
var moveMode = false;
updateButtons();

document.getElementById("moveButton").style = "DISPLAY: none;";

function onEraseClicked(){
		drawMode =false;
		eraseMode = true;
		moveMode = false;
		updateButtons();
};
function onDrawClicked(){
		drawMode =true;
		eraseMode = false;
		moveMode = false;
		updateButtons();
};
function onMoveClicked(){
		drawMode =false;
		eraseMode = false;
		moveMode = true;
		updateButtons();
};

function updateButtons(){
	if(drawMode)
		$("#drawButton").toggleClass("selected", true);
	else
		$("#drawButton").toggleClass("selected", false);
	
	if(eraseMode)
		$("#eraseButton").toggleClass("selected", true);
	else
		$("#eraseButton").toggleClass("selected", false);
	if(moveMode)
		$("#moveButton").toggleClass("selected", true);
	else
		$("#moveButton").toggleClass("selected", false);
}
///////////////////////////





////  Draw square management  /////////////////////////////////////////////////

function initSelection(){
	initDraw(document.getElementById('preview'));
};
	
function initDraw(canvas) {
	var mouse = {
		x: 0,
		y: 0,
		// coord in image ref
		startX: 0,
		startY: 0
	};

	var element = null;
	var minSize = 10;
	

	function onMoveHandler(e) {
		var refImage = document.getElementById('image');
		if(drawMode== true){
			if (element !== null) {
			
				if(e.type == "mousemove"){
					var pageX = e.pageX;
					var pageY = e.pageY;
				}
				else if(e.type == "touchmove"){
					var pageX = e.targetTouches[0].pageX;
					var pageY = e.targetTouches[0].pageY;
				}
				else{
					var pageX = 0;
					var pageY = 0;
					console.log("no event recognized");
				}
				////////   X   /////////////////////////
				var leftImage = refImage.offsetLeft;
				var tmpWidth = Math.abs((pageX - leftImage) - mouse.startX);
				var normalLeft = (mouse.startX+leftImage);
				var reverseLeft = pageX;
				if(((pageX - leftImage) - mouse.startX) < 0)
					var reverseX = true; else var reverseX = false;
				
				if (reverseX == false && ((normalLeft + tmpWidth) <= leftImage+refImage.width))
					element.style.width = tmpWidth + 'px';
				if (reverseX == true && reverseLeft >= leftImage)
					element.style.width = tmpWidth + 'px';
				//Rectangle is moved if going on left
				if(reverseX == true && reverseLeft >= leftImage)
					element.style.left = reverseLeft + 'px';
				if(reverseX == false)
					element.style.left = normalLeft + 'px';
				
				
				////////   Y   /////////////////////////
				var topImage = refImage.offsetTop ;
				var tmpHeight = Math.abs((pageY - topImage) - mouse.startY);
				var normalTop = (mouse.startY+topImage);
				var reverseTop = pageY;
				if(((pageY - topImage) - mouse.startY) < 0)
					var reverseY = true; else var reverseY = false;
				
				if (reverseY == false && ((normalTop + tmpHeight) <= topImage+refImage.height))
					element.style.height = tmpHeight + 'px';
				if (reverseY == true && reverseTop >= topImage)
					element.style.height = tmpHeight + 'px';
				//Rectangle is moved if going on top
				if(reverseY == true && reverseTop >= topImage)
					element.style.top = reverseTop + 'px';
				if(reverseY == false)
					element.style.top = normalTop + 'px';
				if(parseFloat(element.style.width) >= minSize && parseFloat(element.style.height) >= minSize)
					canvas.appendChild(element);
				else if(element.parentElement)
					canvas.removeChild(element);
				adaptText();
			}
		}
	}
	
	
	function onClickHandler(e) {
		if(eraseMode== true){
			//console.log("go pour effacement"+e.target);
			if(e.target.className == "rectangle"){
				//console.log("effacement");
				e.target.remove();
			}
			else if(e.target.className == "rectangleText"){
				e.target.parentElement.remove();
			}
		}
	}
	
	function onDownHandler(e) {
		var refImage = document.getElementById('image');
		if(drawMode== true && element == null && refImage !== null){
			if(e.type == "mousedown"){
				var pageX = e.pageX;
				var pageY = e.pageY;
			}
			else if(e.type == "touchstart"){
				var pageX = e.targetTouches[0].pageX;
				var pageY = e.targetTouches[0].pageY;
			}
			else{
				var pageX = 0;
				var pageY = 0;
				console.log("no event recognized");
			}
			mouse.startX = (pageX - refImage.offsetLeft);
			mouse.startY = (pageY - refImage.offsetTop);
			element = document.createElement('div');
			element.className = 'rectangle';
			var combo = document.getElementById("combo");
			var str = combo.options[combo.selectedIndex].text;
			var type = combo.options[combo.selectedIndex].value;
			var color = catColor[catId.indexOf(parseInt(type))]
			element.rectType = type;
			element.style.left = pageX + 'px';
			element.style.top = pageY + 'px';
			element.style.border= "3px solid "+color;
			element.style.color= color;
			var text = document.createElement('div');
			var t = document.createTextNode(str);
			text.className = 'rectangleText';
			text.appendChild(t);
			element.appendChild(text);
			//canvas.appendChild(element);
			//adaptText();
			element.style.width = 0;
			element.style.height = 0;
			canvas.style.cursor = "crosshair";
			element.onmouseover = function(e){
				//console.log("mouse over");
			}
			element.onmouseout = function(e){
				//console.log("mouse out");
			}
			
		}
	}
	
	function onUpHandler(e) {
		if(drawMode== true){
			element = null;
			canvas.style.cursor = "default";
			//console.log("mouse up");
		}
	}
	function adaptText(){
		////  X //////
		var refImage = document.getElementById('image');
		var textWidth = element.childNodes[0].scrollWidth;
		var leftImage = refImage.offsetLeft - 0;
		if((parseFloat(element.style.left) + textWidth) >= (leftImage+refImage.width)){
			element.childNodes[0].style.left = -textWidth + 'px';
		}
		else{
			element.childNodes[0].style.left = 0 + 'px';
		}
		////  Y //////
		var refImage = document.getElementById('image');
		var textHeight = element.childNodes[0].scrollHeight;
		var topImage = refImage.offsetTop;
		if((parseFloat(element.style.top) + textHeight) >= (topImage+refImage.height)){
			element.childNodes[0].style.top = -textHeight + 'px';
		}
		else{
			element.childNodes[0].style.top = 0 + 'px';
		}
	}
	/////  CLICK  ///////////////////////////
	document.onmousemove = function (e) {
		onMoveHandler(e);
	}
	canvas.onclick = function(e){
		onClickHandler(e);
	}
	canvas.onmousedown = function(e){
		onDownHandler(e);
	}
	document.onmouseup = function(e){
		onUpHandler(e);
	}
	
	/////  TOUCH  ////////////////////////////
	
	image.addEventListener("touchstart", handleStart, false);
	image.addEventListener("touchend", handleEnd, false);
	image.addEventListener("touchcancel", handleCancel, false);
	image.addEventListener("touchmove", handleMove, false);
	
	
	function handleStart(e) {
		if(!moveMode)
			e.preventDefault();
		console.log("touchstart");
		onClickHandler(e);
		onDownHandler(e);
	}
	function handleEnd(e) {
		if(!moveMode)
			e.preventDefault();
		console.log("touchend");
		onUpHandler(e);
	}
	function handleCancel(e) {
		e.preventDefault();
		console.log("touchcancel");
	}
	function handleMove(e) {
		if(!moveMode)
			e.preventDefault();
		onMoveHandler(e);
		console.log("touchmove");
	}
	///////////////////////////////////////////

}





function onNextClicked(){
	var elements = document.getElementsByClassName("rectangle");
	if(elements.length>0){
		console.log("prepare request");
		var data= {};
		data["rects"]=[];
		for (var i = 0; i < elements.length; ++i) {
			var rectLeft = elements[i].offsetLeft - elements[i].parentElement.offsetLeft ;
			var rectTop = elements[i].offsetTop - elements[i].parentElement.offsetTop;
			var rectRight = rectLeft + elements[i].clientWidth;
			var rectBottom = rectTop + elements[i].clientHeight;
			var rectType = elements[i].rectType;
			data["rects"][i]={type:rectType,rectLeft:rectLeft,rectTop:rectTop,rectRight:rectRight,rectBottom:rectBottom}
		}
		data["dataSrc"]=srcName;
		console.log(data);
		
		////////////////////// POST  //////////////
		var http_post_data = new XMLHttpRequest();
		var url = "php/post_data.php";

		http_post_data.open("POST", url, true);

		//Send the proper header information along with the request
		http_post_data.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		
		http_post_data.onreadystatechange = function() {//Call a function when the state changes.
			if(http_post_data.readyState == 4 && http_post_data.status == 200) {
				//alert(http_post_data.responseText);
				console.log(http_post_data.responseText);
				nextImage();
			}
		}
		var json = JSON.stringify(data);
		http_post_data.send("data=" +json);
		//////////////////////////////////////////////
	}
	else{
		nextImage();
	}
}
function freeImage (idImage){
		var data= {};
		data["dataSrc"]=idImage;
		////////////////////// POST  //////////////
		var http_post_data = new XMLHttpRequest();
		var url = "php/post_freeImage.php";

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
	console.log("Load more");
}
window.onbeforeunload = function(e) {
	for(var i = imgPathListIndex; i < imgPathList.length; ++i){
		freeImage (imgPathList[i].id);
		console.log("Free " +imgPathList[i].id);
	}
};
////////////////////////////////////////////



