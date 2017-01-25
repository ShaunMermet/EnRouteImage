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
			<select class="js-basic-single" id="combo" style="max-width:90%;" style="width: 150px;" onchange="onComboChanged()"></select>
			<br/>
			<div id="imgCounter"></div>
			<br/>
			<br/>
			<div><a href = "../logout.php">Sign Out</a></div>
		</div>
		<div class="column column-two">
			<button id="moreButton"class="button" type="button" onclick="onExportClicked()">Export</button>
		</div>
	</div>
		
	</section>
	<script type="text/javascript" src="../js/export/export.js"></script>
</body>
</html>
