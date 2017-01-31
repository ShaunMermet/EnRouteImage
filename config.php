<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'labelImgManager');
define('DB_PASSWORD', 'Y8iRL0yA8zCLbAaV');
define('DB_DATABASE', 'labelimgdb');
define('SESSION_ACTIVE_TIME_IN_SEC',1800);//30 minutes
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}
?>