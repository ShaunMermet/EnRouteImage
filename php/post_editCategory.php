<?php
include('../config.php');
if (!empty($_POST))
{
    error_log("Data sended to server\n");
	
	$data = json_decode(($_POST['data']));
	//error_log(implode(';',$data));
	
	// Set autocommit to off
	mysqli_autocommit($db,FALSE);

	$mode = mysqli_real_escape_string($db,($data->mode));
	$catId = mysqli_real_escape_string($db,($data->catId));
	$catText = mysqli_real_escape_string($db,($data->catText));
	$catColor = "#".mysqli_real_escape_string($db,($data->catColor));
	if ($mode == "CREATE"){
		if ($data->catText == ""){
			error_log ("no label");
			echo "FAIL";
			exit;
			
		}
		$sql = "INSERT INTO `labelimgcategories`
				(`Category`, `Color`) 
		VALUES ('$catText','$catColor')";
		
	}else if ($mode == "EDIT"){
		if(intval($catId) < 1){
			error_log ("wrong ID");
			echo "FAIL";
			exit;
		}
		$sql = "UPDATE `labelimgcategories` SET `Category`='$catText',`Color`='$catColor' WHERE id = '$catId'" ;
	}else if ($mode == "DELETE"){
		if(intval($catId) < 1){
			error_log ("wrong ID");
			echo "FAIL";
			exit;
		}
		$sql = "DELETE FROM `labelimgcategories` WHERE `id` = '$catId'" ;
	}else{
		error_log ("wrong mode");
		echo "FAIL";
	}
	if ($db->query($sql) === TRUE) {
		error_log ("Sql success");
		echo ("SUCCESS");
	} else {
		error_log ("Error: " . $sql . "<br>" . $db->error);
		echo("FAIL");
	}
	// Commit transaction
	mysqli_commit($db);
	
}
else // $_POST is empty.
{
    error_log ("No data");
	echo "FAIL";
}

?>