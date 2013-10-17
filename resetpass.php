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
	
	
	if(isset($_POST['submit']))
	{
		
		$email = $_POST['email'];
		
		$query = "SELECT name, password FROM users WHERE email = '$email'";
		
		if($user = mysql_query($query) && !empty($user))
			{ 
			
			
			//sends an email to web@thelionsroar.com to inform them that a new user has registered(in order to prevent too many emails from being sent. 
			
    			
			 $to = $email;
			 $name = $user['name'];
			 $passwordlink = $user["password"];
			 $subject = "CFS Password reset"; 
			 $body = "Hello $name, \n
			 You(or someone impersonating you) have asked for a password reset. To reset your password, please click on the link below:
			 
			 <a href = 'http://thelionsroar.com/cfs/resetpass2.php?plink=$passwordlink'>  http://thelionsroar.com/cfs/resetpass2.php?plink=$passwordlink </a>
			 
			 Kay Thanks!
			 -James's Email Robot
			 
			 
			 ";
			 if (mail($to, $subject, $body)) { //checks to make sure email was sent
			 
			 
			 	
				header('Location:login.php');
				

			  } else {
			  	echo "An email error occured.";
			  }
			
			
			
			
			
			
			
			}
		else
			{$error_msg = mysql_error();}	
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
		<form action="resetpass.php" method="post">
<ul>
		<?php if(isset($_GET["error"])): ?>
		<p class = "error"> Sorry! You seemed to have made an error. Please make sure your passwords match, and that you typed in at the very least your email, name, password and section. </p>
		<?php endif ?>
			
			<li>
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['submit'])){echo $_POST['email'];}?>" size="35" />
			</li>
			
			<li>
				<input type="submit" name="submit" id="submit" value="Save"/>
			</li>
		</ul>

		
		</form>
		
		
	</body>

</html>