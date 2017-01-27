<?php
include('../sessionOnRequest.php');

if (!empty($_POST))
{
	
	$data = json_decode(($_POST['data']));
	

	$category = mysqli_real_escape_string($db,($data->category));
	
	/////////////SELECT ////////////////
	$sql = "SELECT lnk.id,are.rectType
			FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source
			WHERE are.source IS NOT NULL AND lnk.validated = 1 AND are.rectType = '$category'
			GROUP BY lnk.id";



	$result = $db->query($sql);
	header('Content-type: application/json');
	$res=array();
	array_push($res,$result->num_rows);
	echo json_encode($res);
	$result->close();
	///////////////
	$db->close();
	
}
else // $_POST is empty.
{
    echo "No data";
}

?>