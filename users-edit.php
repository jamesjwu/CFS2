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
	if($role == "Editor In Chief") $role = "Admin";
	$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume != 'UPR-2' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	$issue_result = mysql_fetch_array($issue_query);

	$volume = $issue_result['Volume'];
	$issue = $issue_result['Issue'];

	$id = $_GET['id'];
	$editid = $id; //for editing page
	
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1];
	
	///////////////////////////////////////////////////

	if(isset($_POST['submit']))
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$section = $_POST['section'];
		$role = $_POST['role'];
		$yog = $_POST['yog'];
		$bio = $_POST['bio'];
		if($_POST['password'] != "") {
		$password = crypt($_POST['password']);
		}
		$query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone', section = '$section', role = '$role', yog = '$yog', password = '$password', bio = '$bio' WHERE id = '$id'";
		
		if(mysql_query($query))
			{header('Location:users.php?saved');}
		else
			{$error_msg = mysql_error();}	
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
            <ul class="nav">
              <li><a href="manage.php">Home</a></li>
              <li><a href="new.php">New Article</a></li>
               <li> <a href="javascript://" id="see-prev">Previous issues</a> </li>

              <li><a href="users.php">Users</a></li>

              <li><a href="users-new.php">New User</a></li>

              <li><a href="manage-pages.php">Pages</a></li>

              <li><a href="scoreboard.php">Roarboard</a></li>

              <li><a href="photos.php">Photos</a></li>
              <li><a href="new-issue.php">New Issue</a></li>
               <li><a href="logout.php">Logout</a></li>


            </ul>
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

          <form action="users-edit.php?id=<?=$editid?>" method="post">
		<?php
		
			$article_query = mysql_query("SELECT * FROM users WHERE id = $editid") or die(mysql_error());
			
			while($row = mysql_fetch_array($article_query))
			{
				if(isset($_POST['submit']))
				{
					$row['name'] = $_POST['name'];
					$row['email'] = $_POST['email'];
					$row['phone'] = $_POST['phone'];
					$row['section'] = $_POST['section'];
					$row['role'] = $_POST['role'];
					$row['yog'] = $_POST['yog'];
					$row['bio'] = $_POST['bio'];
				}
		?>
		
		<ul>
			<li>
				<label for="name">Name</label>
				<input type="text" name="name" id="name" value="<?=$row['name']?>" size="35" />
			</li>
			<li>
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="<?=$row['email']?>" size="35" />
			</li>
			<li>
				<label for="password">Password</label>
				<input type="password" name="password" id="password" value="" size="35" />
			</li>
			<li>
				<label for="phone">Phone (just numbers, no dashes)</label>
				<input type="text" name="phone" id="phone" value="<?=$row['phone']?>" size="35" />
			</li>
			<li>
				<label for="section">Section</label>
				<select name="section" id="section">
					<option id="section-0" <?php if($row['section'] == 'Web'){echo 'selected="selected"';}?>>Web</option>
					<option id="section-1" <?php if($row['section'] == 'News'){echo 'selected="selected"';}?>>News</option>
					<option id="section-2" <?php if($row['section'] == 'Features'){echo 'selected="selected"';}?>>Features</option>
					<option id="section-3" <?php if($row['section'] == 'Sports'){echo 'selected="selected"';}?>>Sports</option>
					<option id="section-4" <?php if($row['section'] == 'Centerfold'){echo 'selected="selected"';}?>>Centerfold</option>
					<option id="section-5" <?php if($row['section'] == 'Arts, etc.'){echo 'selected="selected"';}?>>Arts</option>
					<option id="section-6" <?php if($row['section'] == 'Editorials'){echo 'selected="selected"';}?>>Editorials</option>
					<option id="section-7" <?php if($row['section'] == 'Opinions'){echo 'selected="selected"';}?>>Opinions</option>
					<option id="section-8" <?php if($row['section'] == 'All'){echo 'selected="selected"';}?>>All</option>
				</select>
			</li>
			<li>
				<label for="role">Position</label>
				<select name="role" id="role">
					<option id="role-5" <?php if($row['role'] == 'Photo Staff'){echo 'selected="selected"';}?>>Photo/Graphics Staff</option>
					<option id="role-0" <?php if($row['role'] == 'Reporter'){echo 'selected="selected"';}?>>Reporter</option>
					<option id="role-1" <?php if($row['role'] == 'Editor'){echo 'selected="selected"';}?>>Editor</option>
					<option id="role-2" <?php if($row['role'] == 'Senior Editor'){echo 'selected="selected"';}?>>Senior Editor</option>
					<option id="role-3" <?php if($row['role'] == 'Senior Staff'){echo 'selected="selected"';}?>>Senior Staff</option>
					<option id="role-4" <?php if($row['role'] == 'Editor In Chief'){echo 'selected="selected"';}?>>Editor In Chief</option>

					<option id="role-5" <?php if($row['role'] == 'Admin'){echo 'selected="selected"';}?>>Admin</option>
				</select>
			</li>
			
			<li>
				<label for="yog">Year of Graduation</label>
				<input type="text" name="yog" id="yog" value="<?=$row['yog']?>" size="35" />
			</li>
			<?php if($role == "Admin"): ?>
			<li>
				<label for="bio">Profile Info</label>
				<?php $bio = $row['bio'];?>
				<textarea name="bio" id="article" rows="10" cols="40"><?=$bio?></textarea>
			</li>
			<?php endif ?>
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
      	<div id = "previssues"> <?php include('nav.php'); ?> </div>
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