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
	$id = $user_result['id'];
	$editid = $id;
	$section = $user_result['section'];
	if($role == "Editor In Chief") $role = "Admin";
	$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume != 'UPR-2' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	$issue_result = mysql_fetch_array($issue_query);

	$volume = $issue_result['Volume'];
	$issue = $issue_result['Issue'];

	
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1];
	
	///////////////////////////////////////////////////

	if(isset($_POST['submit']))
	{
		if(!empty($_POST['oldpassword'])) { //if the user is not bad at life
		$inputedpassword = crypt($_POST['oldpassword'], $oldpassword); //user inputed old password, check to see if it is the same
		}
		else
		{
			header("Location:settings.php?error");	//give him the error
		}
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$section = $_POST['section'];
		$role = $_POST['role'];
		$picture = $_POST['picture'];
		$bio = $_POST['bio'];
		$yog = $_POST['yog'];
		$newpassword = $_POST['password'];
		if(!empty($newpassword)) {
		$newpassword = crypt($_POST['password']); //new password
		$query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone', yog = '$yog', password = '$newpassword', bio = '$bio', picture = '$picture' WHERE id = '$id'"; //set things to other things
		}
		else {
		$query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone', yog = '$yog', bio = '$bio', picture = '$picture' WHERE id = '$id'"; //set things to other things
		}
		
		if($oldpassword == $inputedpassword) {
			if(mysql_query($query))
			{
			header('Location:settings.php?saved');
			}
			else
			{
			$error_msg = mysql_error();
			}	
		}
		else
			{
			header("Location:settings.php?error");	
			}
			
	}	
	///////////////////////////////////////////////////////////

	if($section != 'All' && $role != 'Senior Editor'){
	
		$section_makeArray = array($section);
		$sections_array_main = array($section_makeArray);

	}elseif($section == 'All'){
	
		$sections_array1 = array('Web',  'News', 'Features', 'Centerfold');
		$sections_array2 = array('Editorials','Community','Sports', 'Opinions');
		
		$sections_array_main = array($sections_array1, $sections_array2);

	}elseif($role == 'Senior Editor'){
		
		if($section == 'Web' || $section == 'News' || $section == 'Features' || $section == 'Community'){
			
			switch($section):
				case 'Web':
					$sections_array1 = array('Web', 'News', 'Features','Community');
					break;
                                 case 'Community':
					$sections_array1 = array('Community','Web', 'News', 'Features');
					break;
				case 'News':
					$sections_array1 = array('News', 'Web', 'Community','Features');
					break;
				case 'Features':
					$sections_array1 = array('Features', 'Web', 'News','Community');
					break;
				
			endswitch;
			
			$sections_array2 = array('Sports', 'Opinions', 'Editorials', 'Centerfold');
			
		} //end if section == web, news, feats, fold
		
		if($section == 'Sports' || $section == 'Arts' || $section == 'Opinions' || $section == 'Editorials' || $section == 'Centerfold'){
			
			switch($section):
				case 'Sports':
					$sections_array1 = array('Sports',  'Opinions', 'Editorials', 'Centerfold');
					break;
				case 'Arts':
					$sections_array1 = array( 'Sports', 'Opinions', 'Editorials', 'Centerfold');
					break;
				case 'Opinions':
					$sections_array1 = array('Opinions', 'Sports', 'Editorials', 'Centerfold');
					break;
				case 'Editorials':
					$sections_array1 = array('Editorials', 'Sports', 'Opinions', 'Centerfold');
					break;
				case 'Centerfold':
					$sections_array1 = array('Centerfold', 'Editorials', 'Sports',  'Opinions');
					break;
			endswitch;
			
			$sections_array2 = array('Web', 'News', 'Features');
			
		} //end if section == sports, arts, ops, edits
		
		$sections_array_main = array($sections_array1, $sections_array2);
			
	} //end if/elseif/elseif
	///////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Lion&rsquo;s Roar CFS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->

    <link href="../cfs2/bootstrap/css/bootstrap.css" rel="stylesheet">
        <script src="../cfs2/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="jquery-1.4.2.min.js"></script>

    	<script type="text/javascript">
	      	$(document).ready(function(){
				$("#see-prev").click(function(){
					$('#prev-issues').show('fast');
				});
			});
			function confirmation(loc) {
				if (confirm('Really delete?')){
					location.href = loc;
				}
			}
			
	    </script>
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons     <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/bootstrap/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/bootstrap/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/bootstrap/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="/bootstrap/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="/bootstrap/ico/favicon.png">
                                   -->

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="manage.php">Lion&rsquo;s Roar CFS</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="settings.php" class="navbar-link"><?=$name ?> </a>
            </p>
               <?php include('nav.php'); ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
            	<?php  
           
		
		foreach($sections_array_main as $column){
	
		
		foreach($column as $row_section)
		{
            		$article_query = mysql_query("SELECT * FROM articles WHERE Volume = '$volume' and Issue = $issue and Section = '$row_section' and Status != 'Bulletin'") or die(mysql_error());
			echo "<li class = 'nav-header'> $row_section </li>";
			while($row = mysql_fetch_array($article_query)) {
				$title = $row['Title']; //article title
				$id = $row['id'];
				$author = $row['Authors']; //article's author
				echo "<li><a href='edit.php?id=$id'>$title - $author</a></li>";

			
			}
			}
			}
            	
            	
            	?>
  
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
      
	<div class = "span10">
	
<div class = "row-fluid" id="prev-issues">
<h4> Choose an old issue: </h4>
<link rel = 'stylesheet' href = 'styles.css'>
	<?php
		
		$issue_query = mysql_query("
			SELECT Volume, Issue
			FROM articles 
			WHERE Volume != 'UPR-2' 
			ORDER BY Volume desc, Issue desc
			") or die(mysql_error());
			
		$queried_issues = array();
		while($result = mysql_fetch_array($issue_query)){
			$queried_issues[] = $result[0] . '-' . $result[1];
		}
		
		$issues = array();
		for($i=0; $i<3000; $i++){
			$j = $i - 1;
			if($queried_issues[$i] != $queried_issues[$j]){$issues[] = $queried_issues[$i];}
		}
		
		foreach ($issues as $iss){
			if($iss < $issues)
			echo '<a href="manage.php?issue=' . $iss . '">' . $iss . '</a> ';
		}
		
	?>
</div>
                
                
            <link href="../cfs2/bootstrap/css/bootstrap.css" rel="stylesheet">
		<form method="get" action="../reporter.php"> 
					<input type = "hidden" name = "id" id = "id" value = "<?=$editid?>" />	
					<input type="submit" class = "btn btn-primary" value="Go to your Profile on thelionsroar.com" />
	
		</form>	
          <form action="settings.php" method="post">
		
		<?php
			
			$user_query = mysql_query("SELECT * FROM users WHERE id = '$editid'") or die(mysql_error());
			
			while($row = mysql_fetch_array($user_query))
			{
				if(isset($_POST['submit']))
				{
					$row['name'] = $_POST['name'];
					$row['email'] = $_POST['email'];
					$row['phone'] = $_POST['phone'];
					$row['bio'] = $_POST['bio'];
					$row['yog'] = $_POST['yog'];
					$row['picture'] = $_POST['picture'];
				}
		?>
		
		<ul>
			
			<li>
				<label for="password">Enter your old Password</label>
				<input type="password" name="oldpassword" id="oldpassword" value="" size="35" />
			
			</li>
			<li>
				<label for="name">Change Name</label>
				<input type="text" name="name" id="name" value="<?=$row['name']?>" size="35" />
			</li>
			<li>
				<label for="email">Change Email</label>
				<input type="text" name="email" id="email" value="<?=$row['email']?>" size="35" />
			</li>
			<li>
				<label for="password">Change Password</label>
				<input type="password" name="password" id="password" value="" size="35" />
			</li>
			<li>
				<label for="phone">Change Phone (just numbers, no dashes)</label>
				<input type="text" name="phone" id="phone" value="<?=$row['phone']?>" size="35" />
			</li>
			
			<li>
				<label for="yog">Change Year of Graduation</label>
				<input type="text" name="yog" id="yog" value="<?=$row['yog']?>" size="35" />
			</li>
			<li>
				<label for="bio">Profile Info</label>
				<?php $bio = $row['bio'];?>
				<textarea name="bio" id="bio" rows="10" cols="40"><?=$bio?></textarea>
			</li>
			<li>
				<label for="picture">URL of a picture of you. Please include the ENTIRE url of the picture. That includes http://!</label>
				<input type="text" name="picture" id="picture" value="<?=$row['picture']?>" size="35" />
			</li>
			<li>
				<input type="submit" name="submit" id="submit" value="Save"/>
			</li>
			
		</ul>
		
		<?php
			}
		?>
		</form>
		</div>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; James Wu 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster 
    <script src="/bootstrap/js/jquery.js"></script>
    <script src="/bootstrap/js/bootstrap-transition.js"></script>ŒQ
    <script src="/bootstrap/js/bootstrap-alert.js"></script>
    <script src="/bootstrap/js/bootstrap-modal.js"></script>
    <script src="/bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="/bootstrap/js/bootstrap-scrollspy.js"></script>
    <script src="/bootstrap/js/bootstrap-tab.js"></script>
    <script src="/bootstrap/js/bootstrap-tooltip.js"></script>
    <script src="/bootstrap/js/bootstrap-popover.js"></script>
    <script src="/bootstrap/js/bootstrap-button.js"></script>
    <script src="/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="/bootstrap/js/bootstrap-carousel.js"></script>
    <script src="/bootstrap/js/bootstrap-typeahead.js"></script>
-->
  </body>
</html>