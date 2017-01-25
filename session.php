<?php
	include('config.php');
	session_start();


	if(!isset($_SESSION['login_user'])){
		session_destroy();
		header("location:http://" . $_SERVER['SERVER_NAME']."/login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
	}
	else{
		$user_check = $_SESSION['login_user'];
	   
		$ses_sql = mysqli_query($db,"select username from labelimgusers where username = '$user_check' ");
	   
		$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
	   
		$login_session = $row['username'];
	   
		$count = mysqli_num_rows($ses_sql);
		if($count != 1) {
			session_destroy();
			header("location:http://" . $_SERVER['SERVER_NAME']."/login.php");
		}
	}
?>