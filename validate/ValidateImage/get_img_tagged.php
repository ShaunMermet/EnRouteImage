<?php
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

/////////////SELECT ////////////////

$sql = "SELECT lnk.id,lnk.path
FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source
WHERE are.source IS NULL
GROUP BY lnk.id
LIMIT 100";
$result = $conn->query($sql);
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

$conn->close();
?>