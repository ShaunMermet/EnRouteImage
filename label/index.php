

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
</head>

<body>
	<section>
	<div class="container">
		<div class="column column-one">
			<select class="js-basic-single" id="combo" style="max-width:90%;" style="width: 150px;"></select>
			<label class="btn-upload">
				<input type="file" name="fileupload" id="the-photo-file-field" >
				<button id="openButton" class="button" type="button">Open image</button>
			</label>
			<button id="nextButton"class="button" type="button" onclick="onNextClicked()">Next image</button>
			<button id="moreButton"class="button" type="button" onclick="onMoreClicked()">More image</button>
			<button id="drawButton" class="btn-mode" type="button"onclick="onDrawClicked()">Draw</button>
			<button id="eraseButton"class="btn-mode" type="button" onclick="onEraseClicked()">Delete</button>
			<button id="moveButton"class="btn-mode" type="button" onclick="onMoveClicked()">Move</button>
			<div id="imgCounter">No image</div>
		</div>
		<div class="column column-four">
			<div id="preview" class="imgcontainer" ondragstart="return false;" ondrop="return false;">
				<img id='image' unselectable='on' onresize='onImgResize()' src='' />
			</div>
			
		</div>
	</div>
		
	</section>
	<script type="text/javascript" src="../js/label/label.js"></script>
</body>
</html>