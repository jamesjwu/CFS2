<?php

	include('database-info.php');
	mysql_select_db("thelions_cfs", $connect);

	session_start();
	
	$email = $_SESSION['roar_cfs_email'];
	if($email == ''){header('Location:login.php');}
	
	$id = $_GET['id'];
	
	$query = "DELETE FROM users WHERE id = $id";
	
	if(mysql_query($query))
		{header('Location:users.php?deleted');}
	else
		{echo mysql_error();}
	
?>