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
		$title = $_POST['title'];
		$authors = $_POST['authors'];
		$status = $_POST['status'];
		$article = $_POST['article'];
		$duedate = $_POST['duedate'];
		
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
		$section = $_POST['section'];
		$photographer = $_POST['photographer'];
		$photoreqs = $_POST['photoreqs'];
		if($role== "Admin"){
		$query = "UPDATE articles SET Title = '$title', Authors = '$authors',Photographer = '$photographer', Status = '$status', Article = \"$article\", Section = '$section', PhotoReqs = '$photoreqs' , DueDate = '$duedate', Volume = '$volume', Issue = '$issue' WHERE id = $id" ;
				}
		else $query = "UPDATE articles SET Title = '$title', Authors = '$authors',Photographer = '$photographer', Status = '$status', Article = \"$article\", Section = '$section', PhotoReqs = '$photoreqs' , DueDate = '$duedate' WHERE id = $id" ;

		if(mysql_query($query))
			{header('Location:manage.php?issue=' . $volume .'-'. $issue .'&saved');}
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

      <link rel = 'stylesheet' href = 'styles.css'>
	
<div class = "row-fluid" id="prev-issues">
 <h4> Choose an old issue: </h4>  
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
<br />

<table class = "table table-hover">

<thead>

<th>
&nbsp;Section</th>
<th>
Best Pages(3)&nbsp;</th>
<th>
Best Articles(3) &nbsp; &nbsp;</th>
<th>
Best Section(5)</td>
<th>
Pleal Points</th>
<th>
Total</th> </thead>
<tbody>
<tr class = "success">
<td>
1. Features</td>
<td>
1&nbsp;</td>
<td>
1&nbsp;</td>
<td>
2&nbsp;</td>
<td>
 10 &nbsp;&nbsp; &nbsp;</td>
<td>
<strong>26</strong></td></tr>

<tr class = "info">
<td>
2. News</td>
<td>
1&nbsp;</td>
<td>
2&nbsp;</td>
<td>
0&nbsp;</td>
<td>
8&nbsp;</td>
<td>
<strong>15&nbsp;</strong></td></tr>

<tr class = "info" >
<td>
2. Opinions </td>
<td>
0</td>
<td>
0</td>
<td>
1</td>
<td>
10</td>
<td>
<strong>15</strong></td></tr>




<tr >
<td>
4. Sports</td>
<td>
0</td>
<td>
0</td>
<td>
1</td>
<td>
8</td>
<td>
<strong>13</strong></td></tr>


<tr  class = "warning" >
<td>
5. Centerfold</td>
<td>
2</td>
<td>
0</td>
<td>
0</td>
<td>
5</td>
<td>
<strong>11</strong></td></tr>




<tr  class = "error">
<td>
6. Community</td>
<td>
0</td>
<td>
0</td>
<td>
0</td>
<td>
3</td>
<td>
<strong>3</strong></td></tr></tbody></table>

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