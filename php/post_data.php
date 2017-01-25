<?php

if (!empty($_POST))
{
    echo "Data sended to server\n";
	
	$data = json_decode(($_POST['data']));
	print_r($data);
	print_r("\n");
	
	/*print_r($data->dataSrc."\n");
	
	$rects= $data->rects;
	foreach ($rects as $num => $rect) {
		print_r("\nrectnumber".$num."\n");
		foreach ($rect as $property => $value){
			print_r($property." ".$value."\n");
		}
		

	}*/
	
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
	
	$rects= $data->rects;
	foreach ($rects as $num => $rect) {//for each rectangle
		print_r("\nrectnumber".$num."\n");
		
		//format data to insert
		$rectType = mysqli_real_escape_string($conn,($rect->type));
		$rectLeft = mysqli_real_escape_string($conn,($rect->rectLeft));
		$rectTop = mysqli_real_escape_string($conn,($rect->rectTop));
		$rectRight = mysqli_real_escape_string($conn,($rect->rectRight));
		$rectBottom = mysqli_real_escape_string($conn,($rect->rectBottom));
		
		
		
		/////////////SELECT ////////////////
		/////////check if already created //////////
		
		$sql = "SELECT * FROM 
		`labelimgarea` lia WHERE 
		lia.source='$source' AND 
		lia.rectType='$rectType' AND 
		lia.rectLeft='$rectLeft' AND 
		lia.rectTop='$rectTop' AND 
		lia.rectRight='$rectRight' AND 
		lia.rectBottom='$rectBottom';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// do nothing
			echo "row was already created";
		} else {
			//Do insert
			/////////// INSERT ////////////////
			//Create sql;
			$sql = "
			INSERT INTO labelimgarea (source, rectType, rectLeft,rectTop,rectRight,rectBottom)
			VALUES ('$source','$rectType','$rectLeft','$rectTop','$rectRight','$rectBottom')";

			//check insert
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			////////////////////////////////
		}
		
		///////////////
		
		
		//Print of properties
		foreach ($rect as $property => $value){//for each property
			print_r($property." ".$value."\n");
		}
		

	}
	
	$conn->close();
	
	
	
	
	
}
else // $_POST is empty.
{
    echo "No data";
}

?>