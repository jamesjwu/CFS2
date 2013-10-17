<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu

	//newpage.php - allows users to create a new page - based on new.php
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

	if(isset($_GET['issue'])){
		$break=explode('-', $_GET['issue']);
		$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume = '$break[0]' and Issue = '$break[1]' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	}else{
		$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume != 'UPR-2' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	}
	
	$issue_result = mysql_fetch_array($issue_query);

	$volume = $issue_result['Volume'];
	$issue = $issue_result['Issue'];
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1];
	
	///////////////////////////////////////////////////////////

	$id = $_GET['id'];

	if(isset($_POST['submit']))
	{
		$title = $_POST['title'];
		$authors = $_POST['authors'];
		
		if($_POST['status'] == 'Not Started') {$status = 'Unwritten';}
		if($_POST['status'] == 'Laying') {$status = 'Reporter Draft';}
		if($_POST['status'] == '1st Proof') {$status = 'Reporter to editor';}
		if($_POST['status'] == '2nd Proof') {$status = 'Editor to senior editor';}
		if($_POST['status']== '3rd Proof') {$status = 'Senior editor to senior staff';}
		if($_POST['status'] == 'PDF') {$status = 'Ready to lay';}
		$article = $_POST['article'];
			$article = str_replace("’", "&#39;", $article);
			$article = str_replace("‘", "&#39;", $article);
			$article = str_replace('“', '&quot;', $article);
			$article = str_replace('”', '&quot;', $article);
			$article = str_replace('…', '&hellip;', $article);
			$article = str_replace('–', '&ndash;', $article);
			$article = str_replace('—', '&mdash;', $article);
		if($section == 'All')
			{$section = $_POST['section'];}
		
		$query = "INSERT INTO articles (Title, Authors, Status, Article, Volume, Issue, Section) VALUES ('$title', '$authors', '$status', '$article', '$volume', '$issue', '$section')";
		
		if(mysql_query($query))
			{header('Location:manage-pages.php?saved');}
		else
			{$error_msg = mysql_error();}	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<title>CFS - New Page</title>
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

	<body id="new-page">
		
		<div id="header"><h1>New Page</h1></div>
		
		<form action="newpage.php" method="post">
		
			<ul>
			
				<li>
					<label for="title"><?php echo (!isset($_GET['bulletin']) ? "Page Number" : "Date"); ?></label>
					<input type="text" name="title" id="title" value="<?=$_POST['title']?>" size="35" />
				</li>
				
				<li>
					<label for="authors"><?php echo (!isset($_GET['bulletin']) ? "Section" : "Author"); ?></label>
					<input type="text" name="authors" id="authors" value="<?=$_POST['authors']?>" size="35" />
				</li>
				
				<li>
				<label for="status">Status</label>
				
				<select name="status" id="status">
				
					<option id="status-1" <?php if($row['Status'] == 'Unwritten'){echo 'selected="selected"';}?>>
						Not Started</option>
					<option id="status-2" <?php if($row['Status'] == 'Reporter draft'){echo 'selected="selected"';}?>>
						Laying</option>
					<option id="status-3" <?php if($row['Status'] == 'Reporter to editor'){echo 'selected="selected"';}?>>
						1st Proof</option>
						
					<?php if($role == 'Editor' || $role == 'Senior Editor' || $role == 'Senior Staff' || $role == 'Admin'):?>
						<option id="status-4" <?php if($row['Status'] == 'Editor to senior editor'){echo 'selected="selected"';}?>>
							2nd Proof</option>
						<option id="status-5" <?php if($row['Status'] == 'Senior editor to senior staff'){echo 'selected="selected"';}?>>
							3rd Proof</option>
						<option id="status-6" <?php if($row['Status'] == 'Ready to lay'){echo 'selected="selected"';}?>>
							PDF</option>
					<?php endif; ?>
					
					<?php if(($role == 'Senior Staff' || $role == 'Admin' || $role == 'Senior Editor') && $row['Status'] == 'Bulletin'):?>
						<option id="status-7" <?php if($row['Status'] == 'Bulletin'){echo 'selected="selected"';}?>>
							Bulletin</option>
					<?php endif; ?>
					
				</select>
			</li>
				
				<?php if($section == 'All'){?>
				
					<li>
						<label for="section">Section</label>
						
						<select name="section" id="section">
						
							<option id="section-10" <?php if(isset($_POST['submit'])){if($_POST['section'] == 'Pages'){echo 'selected="selected"';}}elseif($_GET['section'] == 'Pages'){echo 'selected="selected"';}elseif(!isset($_POST['section'])){echo 'selected="selected"';}?>>
								Pages</option>
							
								
						</select>
					</li>
					
				<?php }?>
				
				<li>
					<label for="article">Sketch</label>
					<textarea name="article" id="article" rows="17" cols="80"><?=$_POST['article']?></textarea>
				</li>
		
				
				<li><input type="submit" name="submit" id="submit" value="Create"/></li>
				
			</ul>
		
		</form>
		
		<div id="footer">
			<?php include('nav.php'); ?>
			<?php if(isset($_POST['submit'])){echo '<p class="error">Unable to save. Try again or get help. Error Message: ' . $error_msg . '</p>';}?>
		</div>
		
	</body>
</html>