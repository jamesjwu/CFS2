<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu

	//register.php - allows users to register a new account on CFS, and sends an email to teh webmaster when someone does so
	include('database-info.php');
	mysql_select_db("thelions_cfs", $connect);

	session_start();
	
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1];
	
	////////////////////////////////////////////
	
	if(isset($_GET['plink'] {
		$passwordlink = $_GET['plink'];
		if(isset($_POST['password'])
		{
			$oldpass = $_POST['plink'];
			$password = crypt($_POST['password']);
			
			$query = "UPDATE users SET password = $password WHERE password = '$oldpass'";
			
			if(mysql_query($query))
				{ 
				$worked = true;
				
				
				
				
				}
			else
				{$error_msg = mysql_error();}	
		}
	}
		else {
			header('Location: login.php');
		}

?>
<?php
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<title>CFS - Register</title>
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
		
		<div id="header">
			<h1>Forgot your password?</h1>
		</div>
		<h2>Type in your email here so we can send you a password reset link. </h2>
		<form action="resetpass2.php" method="post">
<ul>
		<?php if(worked): ?>
		<p class = "error"> Your password has been changed. Please log in again <a href = "login.php"> here </a> </p>
		<?php endif ?>
			
			<li>
				<label for="password">Enter a new password</label>
				<input type="text" name="password" id="password" value="" size="35" />
			</li>
			
			<li>
				<input type="hidden" name = "plink" id = "plink" value = "<?=$passwordlink?>" />
			</li>
			
			<li>
				<input type="submit" name="submit" id="submit" value="Save"/>
			</li>
		</ul>

		
		</form>
		
		
	</body>

</html>