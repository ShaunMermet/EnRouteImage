<?php

if (!empty($_POST))
{
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
	$validated = mysqli_real_escape_string($conn,($data->validated));
	if ($validated)
		$dbValid = 1;
	else{
		$dbValid = 0;
		deleteArea($source);
	}
	
	//Do update
	/////////// UPDATE ////////////////
	//Create sql;
	$sql = "UPDATE `labelimglinks` SET `validated` = $dbValid WHERE `labelimglinks`.`id` = '$source'";
	
	if ($conn->query($sql) === TRUE) {
		echo "record done";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	////////////////////////////////
		
	$conn->close();
	
	
}
else // $_POST is empty.
{
    echo "No data";
}

function deleteArea($source = NULL){
	if(!is_null($source)){
		$servername = "localhost";
		$username = "labelImgManager";
		$password = "Y8iRL0yA8zCLbAaV";
		$dbname = "labelimgdb";
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "DELETE FROM `labelimgarea` WHERE `source`= '$source'";
		if ($conn->query($sql) === TRUE) {
			echo "delete done";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$conn->close();
	}
}

?>