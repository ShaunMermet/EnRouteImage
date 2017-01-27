<?php
include('../sessionOnRequest.php');

/////////////SELECT ////////////////

$sql = "SELECT are.source,are.rectType,cat.Category,cat.Color,are.rectLeft,are.rectTop,are.rectRight,are.rectBottom
FROM labelimglinks lnk 
LEFT JOIN labelimgarea are ON lnk.id =are.source
LEFT JOIN labelimgcategories cat ON are.rectType = cat.id
WHERE are.source IS NOT NULL AND lnk.validated = 0";
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