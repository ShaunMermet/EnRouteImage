<?php
include('../config.php');
if (!empty($_POST))
{
    echo "Data sended to server\n";
	
	$data = json_decode(($_POST['data']));

	$source = mysqli_real_escape_string($db,($data->dataSrc));
	$sql = "UPDATE `labelimglinks` SET `available` = 1 WHERE `labelimglinks`.`id` = '$source'";	
	if ($db->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $db->error;
	}
	
	$db->close();
	
}
else // $_POST is empty.
{
    echo "No data";
}

?>