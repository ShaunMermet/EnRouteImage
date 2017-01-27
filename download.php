<?php
include('session.php');

if (session_status() !== PHP_SESSION_ACTIVE){
	error_log("No session");
	exit;
}

error_log("enter dl");

if(!isset($_GET['id'])) {
	error_log("No token");
	exit;
}
else{
	error_log($_GET['id']);
}

//$token = $_GET['id'];
$token = mysqli_real_escape_string($db,$_GET['id']);
$sql = "SELECT exlk.archivePath FROM labelimgexportlinks exlk WHERE exlk.token = '$token'";
$res = $db->query($sql);
$tmpLink = $res->fetch_object();

$count = mysqli_num_rows($res);
if($count != 1) 
	exit;


$filename = "tmp/".$tmpLink->archivePath;

if (!file_exists($filename)) 
	exit;

error_log($filename);
// send $filename to browser
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filename);
$size = filesize($filename);
$name = basename($filename);
 
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
	// cache settings for IE6 on HTTPS
	header('Cache-Control: max-age=120');
	header('Pragma: public');
} else {
	header('Cache-Control: private, max-age=120, must-revalidate');
	header("Pragma: no-cache");
}
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // long ago
header("Content-Type: $mimeType");
header('Content-Disposition: attachment; filename="' . $name . '";');
header("Accept-Ranges: bytes");
header('Content-Length: ' . filesize($filename));
 
print readfile($filename);

rrmdir("tmp/".$token);

$sql = "DELETE FROM `labelimgexportlinks` WHERE `labelimgexportlinks`.`token` = '$token'";
if(!$db->query($sql))
	error_log("Delete failed in labelimgexportlinks");
function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}


exit;
?>