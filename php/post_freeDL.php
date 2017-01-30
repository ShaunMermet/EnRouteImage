<?php
include('../config.php');
if (!empty($_POST))
{
    echo "Data sended to server\n";
	
	$data = json_decode(($_POST['data']));

	$token = mysqli_real_escape_string($db,($data->token));
	$sql = "DELETE FROM `labelimgexportlinks` WHERE token = '$token'";	
	if ($db->query($sql) === TRUE) {
		if($token)
			rrmdir("../tmp/".$token);
	} else {
		echo "Error: " . $sql . "<br>" . $db->error;
	}
	
	$db->close();
	
}
else // $_POST is empty.
{
    echo "No data";
}

function rrmdir($src) {
	$dir = opendir($src);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			$full = $src . '/' . $file;
			if ( is_dir($full) ) {
				rrmdir($full);
			}
			else {
				unlink($full);
			}
		}
	}
	closedir($dir);
	rmdir($src);
}

?>