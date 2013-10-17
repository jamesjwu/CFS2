<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu / 2007 Zhoushi Xie

	//login.php- only added minor changes to support encrypted passwords

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
			header('Location:manage.php');
		}
		else
			{header('Location:login.php?error');}
	}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
			<title>CFS - Login</title>
			<link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
      	background: url('winter.jpg');
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        background-image: url('..winter.jpg');
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
    <link href="bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

	<?php
  	include('login-form.php');
		?>

	
	</body>
	
</html>