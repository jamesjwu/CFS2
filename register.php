<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu 
	
	include('database-info.php');
	mysql_select_db("thelions_cfs", $connect);

	session_start();
	
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1];

	if(isset($_POST['submit']))
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$section = $_POST['section'];
		$bio = $_POST['bio'];
		$yog = $_POST['yog'];
		$password = $_POST['password'];
		$confirm = $_POST['password2'];
		if($password != $confirm || empty($password) || empty($name) || empty($email)) { //check to make sure fields are correct
			header("Location:register.php?error");
		
		}
		$newpassword = crypt($password);
		
		$query = "INSERT INTO users (name, email, phone, section, role, yog, password, bio) VALUES ('$name', '$email', '$phone', '$section', 'Reporter', '$yog', '$newpassword', '$bio')";
		
		if(mysql_query($query))
			{ 
			
			
			//sends an email to web@thelionsroar.com to inform them that a new user has registered(in order to prevent too many emails from being sent. 
			
    			
			 $to = "web@thelionsroar.com";
			 $subject = "New User ". $name ." registered!"; 
			 $body = "Hello Webmaster, \n
			 This is an automatic message informing you that a new reporter, $name, has registered for a new account. His info:
			 Name: $name
			 Email: $email
			 Phone: $phone
			 Section: $section
			 Year of Graduation: $yog
			 Profile into: $bio
			 Please make sure that this person is actually on the Lion's Roar. If not, please feel free to delete the account.
			 
			 Kay Thanks!
			 -James's Email Robot
			 
			 
			 ";
			 if (mail($to, $subject, $body)) { //checks to make sure email was sent
			 
			 
			 	
			 
				$_SESSION['roar_cfs_email'] = $_POST['email'];

				header('Location:manage.php');
				

			  } else {
			  	echo "An email error occured.";
			  }
			
			
			
			
			
			
			
			}
		else
			{$error_msg = mysql_error();}	
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>The Lion&rsquo;s Roar CFS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../cfs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-image: url('../cfs/winter.jpg');
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

                                     <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

    <div class="container">

      <form class = "form-signin" action="register.php" method="post">
      <h2> Register </h2>

		<?php if(isset($_GET["error"])): ?>
		<p class = "error"> Sorry! You seemed to have made an error. Please make sure your passwords match, and that you typed in at the very least your email, name, password and section. </p>
		<?php endif ?>
			
				<label for="name">Name</label>
				<input type="text" name="name" id="name" value="<?php if(isset($_POST['submit'])){echo $_POST['name'];}?>" size="35" />
			
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['submit'])){echo $_POST['email'];}?>" size="35" />
			
				<label for="password">Password</label>
				<input type="password" name="password" id="password" value="<?php if(isset($_POST['submit'])){echo $_POST['password'];}?>" size="35" />
			
				<label for="password2">Confirm Password</label>
				<input type="password" name="password2" id="password2" value="<?php if(isset($_POST['submit'])){echo $_POST['password2'];}?>" size="35" />
			
				<label for="phone">Phone (just numbers, no dashes)</label>
				<input type="text" name="phone" id="phone" value="<?php if(isset($_POST['submit'])){echo $_POST['phone'];}?>" size="35" />
			
				<label for="section">Section</label>
				<select name="section" id="section">
					<option id="section-0" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Web'){echo 'selected="selected"';}?>>Web</option>
					<option id="section-1" <?php if(isset($_POST['submit']) && $_POST['section'] == 'News'){echo 'selected="selected"';}?>>News</option>
					<option id="section-2" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Features'){echo 'selected="selected"';}?>>Features</option>
					<option id="section-3" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Sports'){echo 'selected="selected"';}?>>Sports</option>
					<option id="section-4" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Centerfold'){echo 'selected="selected"';}?>>Centerfold</option>
					<option id="section-5" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Arts, etc.'){echo 'selected="selected"';}?>>Arts</option>
					<option id="section-6" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Editorials'){echo 'selected="selected"';}?>>Editorials</option>
					<option id="section-7" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Opinions'){echo 'selected="selected"';}?>>Opinions</option>
	                                <option id="section-8" <?php if(isset($_POST['submit']) && $_POST['section'] == 'Community'){echo 'selected="selected"';}?>>Community</option>
				</select>
			
				<label for="bio">Profile Info- Tell us a little bit about yourself!</label>
				<?php $bio = $row['bio'];?>
				<textarea name="bio" id="article" rows="10" cols="20"><?=$bio?></textarea>
				<label for="yog">Year of Graduation</label>
				<input type="text" name="yog" id="yog" value="<?php if(isset($_POST['submit'])){echo $_POST['yog'];}?>" size="35" />
			
				<input type="submit" name="submit" id="submit" value="Save"/>
		
		
		</form>


    </div> <!-- /container -->

  

  </body>
</html>

	
</html>