<?php
include('../sessionOnRequest.php');

/////////////SELECT ////////////////

$sql = "SELECT lnk.id,lnk.path
FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source
WHERE are.source IS NOT NULL AND lnk.validated = 0 AND lnk.available = 1
GROUP BY lnk.id
ORDER BY RAND()
LIMIT 20";
$result = $db->query($sql);
header('Content-type: application/json');
if ($result->num_rows > 0) {
	
    $res=array();
	/* fetch object array */
    while ($obj = $result->fetch_object()) {
		$sql = "UPDATE `labelimglinks` SET `available` = 0 WHERE `labelimglinks`.`id` = '$obj->id'";	
		if ($db->query($sql) === TRUE) {
		} else {
			echo "Error: " . $sql . "<br>" . $db->error;
		}
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