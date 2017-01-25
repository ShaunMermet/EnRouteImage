<?php
include('../session.php');

if (!empty($_POST))
{
	
	$data = json_decode(($_POST['data']));
	

	$category = mysqli_real_escape_string($db,($data->category));
	error_log("category ".$category);
	/////////////SELECT ////////////////
	$sql = "SELECT lnk.path
			FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source
			WHERE are.source IS NOT NULL AND lnk.validated = 1 AND are.rectType = '$category'
			GROUP BY lnk.id";



	$result = $db->query($sql);
	
	///////////////////
	/*header('Content-type: application/json');
	$res=array();
	array_push($res,$result->num_rows);
	echo json_encode($res);
	$result->close();
	$db->close();*/
	///////////////

	if ($result->num_rows > 0) {
	
		$res=array();
		/* fetch object array */
		while ($obj = $result->fetch_object()) {
			$path_parts = pathinfo($obj->path);
			error_log("Fill file ".$path_parts['filename'].".txt");
			$txtfile = fopen($path_parts['filename'].".txt", "w") or die("Unable to open file!");
			$sql = "SELECT cat.Category,are.rectLeft,are.rectTop,are.rectRight,are.rectBottom
FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source LEFT JOIN labelimgcategories cat ON cat.id=are.rectType
WHERE are.source IS NOT NULL AND lnk.validated = 1 AND are.rectType = '1'AND lnk.path = '$obj->path'";	
			$rows = $db->query($sql);
			while ($rect = $rows->fetch_object()) {
				$line = "0 0 0 ".$rect->Category." ".$rect->rectLeft." ".$rect->rectTop." ".$rect->rectRight." ".$rect->rectBottom." 0 0 0 0 0 0 0";
				fwrite($txtfile, $line);
				fwrite($txtfile, "\n");
				error_log($line);
			}
			fclose($txtfile);
			//array_push($res,$obj);*/
		}
		echo json_encode($res);

		/* free result set */
		$result->close();
		$rows->close();
	}
	
}
else // $_POST is empty.
{
    echo "No data";
}

?>