<?php

	include('database-info.php');
	mysql_select_db("thelions_cfs", $connect);

	session_start();
	
	$email = $_SESSION['roar_cfs_email'];
	if($email == ''){header('Location:login.php');}
	
	$user_query = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
	$user_result = mysql_fetch_array($user_query);
	
	$name = $user_result['name'];
	$role = $user_result['role'];
	$section = $user_result['section'];

	$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume != 'UPR-2' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	$issue_result = mysql_fetch_array($issue_query);

	$volume = $issue_result['Volume'];
	$issue = $issue_result['Issue'];

	$id = $_GET['id'];
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1];
	
	///////////////////////////////////////////////////

	if(isset($_POST['submit']))
	{
		$title = $_POST['title'];
		$authors = $_POST['authors'];
		$status = $_POST['status'];
		$article = $_POST['article'];
		$section = $_POST['section'];
		$photoreqs = $_POST['photoreqs'];
		$query = "UPDATE articles SET Title = '$title', Authors = '$authors', Status = '$status', Article = '$article', Section = '$section', PhotoReqs = '$photoreqs' WHERE id = $id";
		
		if(mysql_query($query))
			{header('Location:manage.php?saved');}
		else
			{$error_msg = mysql_error();}	
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>CFS - View Article ID <?=$_GET['id']?></title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		
		<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
		<script type="text/javascript">
	      	$(document).ready(function(){
				$("#see-prev").click(function(){
					$('#prev-issues').show('fast');
				});
			});
	    </script>

	</head>

	<body id="view-page">
		
		<div id="header">
			<h1>View Article ID <?=$_GET['id']?></h1>
		</div>
				
		<?php
		
			$article_query = mysql_query("SELECT * FROM articles WHERE id = $id") or die(mysql_error());
			
			while($row = mysql_fetch_array($article_query))
			{
		?>
		
		<ul id="view-list">
		
			<li><strong>Photo/Graphic Request: </strong><?=$row['PhotoReqs']?></li>
		
			<li><strong>Title: </strong><?=$row['Title']?></li>
			
			<li><strong>Authors: </strong><?=$row['Authors']?></li>
			
			<li><strong>Status: </strong><?=$row['Status']?></li>
			
			<li><strong>Section: </strong><?php echo $row['Section'];?></li>
			
			<li style="margin-top:10px;"><?php include_once "markdown.php";$my_html = Markdown($row['Article']);echo $my_html;?></li>			
		</ul>
		
		<?php
			}
		?>
		
		<div id="footer">
			<?php include('nav.php'); ?>
		</div>
	</body>

</html>