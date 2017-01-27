<?php
include('../session.php');
?>
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
			<button id="ValidateButton"class="button-validate" type="button" onclick="onValidateClicked()">Validate</button>
			<button id="RejectButton"class="button-reject" type="button" onclick="onRejectClicked()">Reject</button>
			<button id="moreButton"class="button" type="button" onclick="onMoreClicked()">More image</button>
			<input class="button-signout" type="button" onclick="location.href='../logout.php';" value="Sign Out" />
			<div id="imgCounter">No image</div>
		</div>
		<div class="column column-four">
			<div id="preview" ondragstart="return false;" ondrop="return false;">
				<img id='image' unselectable='on' src='' />
			</div>
			
		</div>
	</div>
		
	</section>
	<script type="text/javascript" src="../js/validate/validate.js"></script>
</body>
</html>