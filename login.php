<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu 


	if(isset($_POST['submit']))
	{
		include('database-info.php');
		mysql_select_db("thelions_cfs", $connect);		
		
		$email = $_POST['email'];
		
		$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE email= '$email'"));
		
		

		$password = crypt($_POST['password'], $user['password']);
		

		
		if($user['password'] === $password)
		{	
			
			
			
		
			session_start();
			$_SESSION['roar_cfs_email'] = $_POST['email'];
			header('Location:manage.php');}
		
		else
			{header('Location:login.php?error');}
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
        background-image: url('Colorful_spring_garden.jpg');
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

      <form class="form-signin" action = "login.php" method = "post">
        <h2 class="form-signin-heading">CFS Login</h2>
        <div class ='alert'> 	<a href = "http://www.mediafire.com/?mhv54ometddii"> Photos- click here! </a> </div>
        <?php if(isset($_GET['error'])) { echo  "<div class='alert alert-error'> Invalid username/password. Try again. </div>";} ?>
                <?php if(isset($_GET['error2'])) { echo  "<div class='alert alert-error'> We're sorry, but we are currently only allowing Senior editors and above to use CFS 2.0. It will be made available to you as soon as more features are added. For now, use normal <a href = 'http://thelionsroar.com/cfs'> CFS</a>! </div>";} ?>

     
        <input id = "email" name = "email" type="text" class="input-block-level" placeholder="Email address">
        <input id = "password" name = "password" type="password" class="input-block-level" placeholder="Password">
   
        <input value = "Login" name = "submit" id = "submit" class="btn btn-large btn-primary" type="submit">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
	<input type="button" value="Register" class = "btn btn-large btn-primary" onClick="window.location = 'register.php'" />
      </form>


    </div> <!-- /container -->

  

  </body>
</html>

	
</html>