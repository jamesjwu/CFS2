<?php
	include('database-info.php');
	session_start();
	
	$email = $_SESSION['roar_cfs_email'];
	if($email == ''){header('Location:login.php');}
	else{header('Location:manage.php');}
	
?>