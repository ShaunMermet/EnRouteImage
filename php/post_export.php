<?php
include('../sessionOnRequest.php');


if (!empty($_POST))
{
	
	$data = json_decode(($_POST['data']));
	

	$category = mysqli_real_escape_string($db,($data->category));
	/////////////SELECT ////////////////
	$sql = "SELECT lnk.path
			FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source
			WHERE are.source IS NOT NULL AND lnk.validated = 1 AND are.rectType = '$category'
			GROUP BY lnk.id";



	$result = $db->query($sql);
	
	$imgFound = $result->num_rows;
	
	if ($imgFound > 0) {
	
		$sql = "SELECT cat.Category FROM labelimgcategories cat WHERE cat.id= '$category'";
		$cat = $db->query($sql);
		$catRes = $cat->fetch_object();
		$tmpFolder = sha1(rand().microtime());
		error_log("Folder : ".$tmpFolder);
		
		if(!saveTmpFolder($tmpFolder,$tmpFolder."/".$catRes->Category.".zip",$db))
			exit;
		
		mkdir("../tmp/".$tmpFolder, 0700);
		$filename = ("../tmp/".$tmpFolder."/".$catRes->Category.".zip");
		$zip = new ZipArchive();
		$zip->open($filename, ZipArchive::CREATE);
		
		$fileNameLink = "";
		/* fetch object array */
		while ($obj = $result->fetch_object()) {
			$path_parts = pathinfo($obj->path);
			$fileNameLink = $path_parts['filename'];
			//error_log("Fill file ".$path_parts['filename'].".txt");
			$txtfile = fopen("../tmp/".$tmpFolder."/".$path_parts['filename'].".txt", "w") or die("Unable to open file!");
			$sql = "SELECT cat.Category,are.rectLeft,are.rectTop,are.rectRight,are.rectBottom
FROM labelimglinks lnk LEFT JOIN labelimgarea are ON lnk.id =are.source LEFT JOIN labelimgcategories cat ON cat.id=are.rectType
WHERE are.source IS NOT NULL AND lnk.validated = 1 AND are.rectType = '$category' AND lnk.path = '$obj->path'";	
			$rows = $db->query($sql);
			$curImg = 0;
			while ($rect = $rows->fetch_object()) {
				$line = $rect->Category." 0 0 0 ".$rect->rectLeft." ".$rect->rectTop." ".$rect->rectRight." ".$rect->rectBottom." 0 0 0 0 0 0 0";
				fwrite($txtfile, $line);
				fwrite($txtfile, "\n");
				//error_log($line);
				$curImg++;
				$prct = $curImg/$imgFound*100;
			}
			fclose($txtfile);
			$zip->addFile("../tmp/".$tmpFolder."/".$path_parts['filename'].".txt", $path_parts['filename'].".txt");
			$zip->addFile("../img/".$obj->path, $obj->path);
		}
		
		/* free result set */
		$zip->close();
		$cat->close();
		$result->close();
		$rows->close();
		
		$res=array("link"=>$tmpFolder,"msg"=>"Download Ready");
		echo json_encode($res);
		
	}
	else
		echo json_encode("No file found");
	
}
else // $_POST is empty.
{
    echo json_encode("No data");
}


//clean tmp folder
$dir    = '../tmp';
$tmpArray = scandir($dir);
foreach($tmpArray as $file){
	$folder = basename($file);
	if(strlen($folder) == 40){
		$sql = "SELECT `token`,`expires` FROM `labelimgexportlinks` WHERE token = '$folder'";
		$tokens = $db->query($sql);
		while ($token = $tokens->fetch_object()) {
			if(date('Y-m-d H:i:s') > $token->expires ){
				$sql = "DELETE FROM `labelimgexportlinks` WHERE token = '$folder'";	
				if ($db->query($sql) === TRUE) {
					if(file_exists ("../tmp/".$token))
						rrmdir("../tmp/".$token);
				} else {
					echo "Error: " . $sql . "<br>" . $db->error;
				}
				error_log("Clean : ".$folder);
			}else{
				error_log(date('Y-m-d H:i:s')." NO Clean : ".$token->expires." ".$folder);
			}
		}
		$count = mysqli_num_rows($tokens);
		if($count == 0) 
			if(file_exists ("../tmp/".$folder))
				rrmdir("../tmp/".$folder);
	}
}
$sql = "SELECT `token`,`expires` FROM `labelimgexportlinks` WHERE 1";
$tokens = $db->query($sql);
while ($token = $tokens->fetch_object()) {
	if(date('Y-m-d H:i:s') > $token->expires ){
		$sql = "DELETE FROM `labelimgexportlinks` WHERE token = '$token->token'";	
		if ($db->query($sql) === TRUE) {
			error_log("Clean DB: ".$token->token);
			if(file_exists ("../tmp/".$token->token))
				rrmdir("../tmp/".$token->token);
		} else {
			echo "Error: " . $sql . "<br>" . $db->error;
		}
	}
}
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

function saveTmpFolder($token, $archivePath,$db){
	//$token = $tmpFolder;
	//$archivePath = $catRes->Category.".zip";
	$nextSix = time() + (6 * 60 * 60);
	$expires = date('Y-m-d H:i:s', $nextSix);
	
	error_log("token : ".$token);
	error_log("archivePath : ".$archivePath);
	error_log("expires : ".$expires);
	//$sql = "";
	$sql = "
			INSERT INTO labelimgexportlinks (token, archivePath, expires)
			VALUES ('$token','$archivePath','$expires')";

	//check insert
	if ($db->query($sql) === TRUE) {
		return true;
	} else {
		return false;
	}
}

?>