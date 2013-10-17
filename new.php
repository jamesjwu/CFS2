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
	$current_page = "new.php";
	
	///////////////////////////////////////////////////

	
	if(isset($_POST['submit']))
	{
		$title = $_POST['title'];
		$authors = $_POST['authors'];
		$status = $_POST['status'];
		$article = $_POST['article'];
		if(isset($_POST['volume'])&& ($_POST['volume'] <= $volume)) {
		$volume = $_POST['volume'];}
		if(isset($_POST['issue']) && ($_POST['issue'] <= $issue)) {
		$issue = $_POST['issue']; 
		}
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
			{header('Location:manage.php?saved');}
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
      
          
	
           <form action="new.php" method="post">
		
		         	 <div class="row-fluid">

				<span class = "span9">
				<input type="text" name="title" id="title" value="<?=$row['Title']?>" placeholder = "Title" size="55" /> &nbsp; by  &nbsp;<input type="text" name="authors" id="authors" value="<?=$row['Authors']?>" placeholder = "Author" size="50" /> 
				</span>
				<span class = "span3 offset 1">
				<input type = "submit" id = "submit" name = "submit" value = "Save" class="btn btn-primary btn-large" />
	
				</span>
				</div>
				<div class = "row-fluid">
				<span class = "span11">
				<select name="status" id="status">
				
					<option id="status-1" <?php if($row['Status'] == 'Unwritten'){echo 'selected="selected"';}?>>
						Unwritten</option>
					<option id="status-2" <?php if($row['Status'] == 'Reporter draft'){echo 'selected="selected"';}?>>
						Reporter draft</option>
					<option id="status-3" <?php if($row['Status'] == 'Reporter to editor'){echo 'selected="selected"';}?>>
						Reporter to editor</option>
						
					<?php if($role == 'Editor' || $role == 'Senior Editor' || $role == 'Senior Staff' || $role == 'Admin'):?>
						<option id="status-4" <?php if($row['Status'] == 'Editor to senior editor'){echo 'selected="selected"';}?>>
							Editor to senior editor</option>
						<option id="status-5" <?php if($row['Status'] == 'Senior editor to senior staff'){echo 'selected="selected"';}?>>
							Senior editor to senior staff</option>
						<option id="status-6" <?php if($row['Status'] == 'Ready to lay'){echo 'selected="selected"';}?>>
							Ready to lay</option>
						<option id="status-8" <?php if($row['Status'] == 'Not Running'){echo 'selected="selected"';}?>>
							Not Running</option>
						<option id="status-9" <?php if($row['Status'] == 'To Web'){echo 'selected="selected"';}?>>
							To Web</option>
					<?php endif; ?>
					
					
				</select>
				 &nbsp; &nbsp;
			<?php if($role == 'Admin' || $role == 'Senior Staff' || $role = 'Senior Editor'){?>
				
			
					<select name="section" id="section">
					
						<option id="section-0" <?php if($row['Section'] == 'Web'){echo 'selected="selected"';}?>>
							Web</option>
						<option id="section-1" <?php if($row['Section'] == 'News'){echo 'selected="selected"';}?>>
							News</option>
						<option id="section-2" <?php if($row['Section'] == 'Features'){echo 'selected="selected"';}?>>
							Features</option>
						<option id="section-3" <?php if($row['Section'] == 'Sports'){echo 'selected="selected"';}?>>
							Sports</option>
						<option id="section-4" <?php if($row['Section'] == 'Centerfold'){echo 'selected="selected"';}?>>
							Centerfold</option>
						<option id="section-5" <?php if($row['Section'] == 'Arts'){echo 'selected="selected"';}?>>
							Arts</option>
						<option id="section-6" <?php if($row['Section'] == 'Editorials'){echo 'selected="selected"';}?>>
							Editorials</option>
						<option id="section-7" <?php if($row['Section'] == 'Opinions'){echo 'selected="selected"';}?>>
							Opinions</option>
						<option id="section-8" <?php if($row['Section'] == 'Community'){echo 'selected="selected"';}?>>
							Community</option>
					
							
					</select>
					 &nbsp; &nbsp;
				<input type="text" class = "field span2" name="duedate" id="duedate" value="<?=$row['DueDate']?>" size="10" placeholder = "Due date"/>   &nbsp; &nbsp;
				
				
				
			<input type="text" class = "field span1" name="volume" id="volume"  value ="<?=$volume ?>" size="1" />
				-
				<input type="text" class = "field span1" name="issue" id="issue"  value="<?=$issue?>" size="1" />
								
			<?php }else{ echo '<input type="hidden" name="section" id="section" value="' . $row['Section'] . '"/>
			
				<input type="hidden" name="volume" id="volume"  value =' . $row['Volume'] .'size="1" />
				-
				<input type="hidden" name="issue" id="issue"  value=' . $row['Issue'] .' size="1" />
		'; }?>
			
				</div>
			<div class = "row-fluid">

					<span class = "span10">
				<label for="article">Article</label>
				<?php $article = $row['Article'];?>
				<link rel="stylesheet" href="../cfs2/ckeditor/contents.css">

				<textarea class = 'ckeditor' name="article" id="article"  placeholder = "Write your article here!"><?=$article?></textarea>
				
				
				</span>
			
	    <link href="../cfs2/bootstrap/css/bootstrap.css" rel="stylesheet">

				
			</div>
			<br/>
			<div class = "row-fluid">
			
			<?php if($role == "Senior Editor" || $role == "Senior Staff" || $role == "Admin" || $role == "Photo"): ?>
			<span class = "span11">
			<label for="photoreqs"></label>
				<input type="text" name="photographer" id="photographer" value="<?=$row['Photographer']?>" placeholder = "Photographer/Artist" size="20" />
				</span>
			</div>
			<div class = "row-fluid">
			<span class = "span9">
				<label for="photoreqs"></label>
				<textarea name="photoreqs" id="photoreqs" class = "field span10" placeholder = "Photo/Graphic Request" rows="5" cols="100"><?=$row['PhotoReqs']?></textarea>
		
				
	
			</span>
			
			<?php endif ?>
			
			
			<span class = "span2">
				<input type = "submit" id = "submit" name = "submit" value = "Save" class="btn btn-primary btn-large" />
				</span>
				</div>
		
		</form>
          </div><!--/row-->
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
    <script src="/bootstrap/js/bootstrap-transition.js"></script>
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