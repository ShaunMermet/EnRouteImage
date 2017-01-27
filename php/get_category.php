<?php
include('../config.php');


/////////////SELECT ////////////////

$sql = "SELECT id,Category,Color FROM `labelImgCategories` WHERE 1";
$result = $db->query($sql);
header('Content-type: application/json');
if ($result->num_rows > 0) {
	
    $res=array();
	/* fetch object array */
    while ($obj = $result->fetch_object()) {
		array_push($res,$obj);
    }
	echo json_encode($res);

    /* free result set */
    $result->close();
	
} else {
	
}
///////////////

$db->close();
?>