<?php

if (!empty($_POST))
{
    echo "Data sended to server\n";
	
	$data = json_decode(($_POST['data']));
	
	$servername = "localhost";
	$username = "labelImgManager";
	$password = "Y8iRL0yA8zCLbAaV";
	$dbname = "labelimgdb";

	
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$source = mysqli_real_escape_string($conn,($data->dataSrc));
	$sql = "UPDATE `labelimglinks` SET `available` = 1 WHERE `labelimglinks`.`id` = '$source'";	
	if ($conn->query($sql) === TRUE) {
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	$conn->close();
	
}
else // $_POST is empty.
{
    echo "No data";
}

?>