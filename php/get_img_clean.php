<?php
include('../config.php');

// Set autocommit to off
mysqli_autocommit($db,FALSE);

/////////////SELECT ////////////////

$sql = "SELECT lnk.id,lnk.path
FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source
WHERE are.source IS NULL AND lnk.available = 1
GROUP BY lnk.id
ORDER BY RAND()
LIMIT 20";
$result = $db->query($sql);
header('Content-type: application/json');
$res=array();
if ($result->num_rows > 0) {
	
    
	/* fetch object array */
    while ($obj = $result->fetch_object()) {
		//$sql = "UPDATE `labelimglinks` SET `available` = 0 WHERE `labelimglinks`.`id` = '$obj->id'";	
		/*if ($db->query($sql) === TRUE) {
		} else {
			echo "Error: " . $sql . "<br>" . $db->error;
		}*/
		
		array_push($res,$obj);
		error_log("label : found row");
    }
	error_log("label : ".count($res));
	echo json_encode($res);
	
	
	// Commit transaction
	mysqli_commit($db);

    /* free result set */
    $result->close();
	
} else {
	
}

foreach($res as $img){
	$sql = "UPDATE `labelimglinks` SET `available` = 0 WHERE `labelimglinks`.`id` = '$img->id'";
	if ($db->query($sql) === TRUE) {
		error_log("label : set 0 ".$img->id);
	} else {
		echo "Error: " . $sql . "<br>" . $db->error;
	}
}
///////////////

$db->close();
?>