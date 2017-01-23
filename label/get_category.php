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

$sql = "SELECT id,Category,Color FROM `labelImgCategories` WHERE 1";
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