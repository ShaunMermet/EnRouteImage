<?php
	include('config.php');
	session_start();
	
	//check auto-timeout
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_ACTIVE_TIME_IN_SEC)) {
		// last request was more than (X minutes) ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		echo("session_closed");
		exit;
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	

	if(!isset($_SESSION['login_user'])){
		session_destroy();
		echo("session_closed");
		exit;
	}
	else{
		$user_check = $_SESSION['login_user'];
		//error_log("session exist !! ".$_SESSION['login_user'] );
		$ses_sql = mysqli_query($db,"select username from labelimgusers where username = '$user_check' ");
	   
		$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
	   
		$login_session = $row['username'];
	   
		$count = mysqli_num_rows($ses_sql);
		if($count != 1) {
			session_destroy();
			echo("session_closed");
			exit;
		}
	}
?>