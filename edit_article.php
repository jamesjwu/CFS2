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
              Logged in as <a href="#" class="navbar-link"><?=$name ?> </a>
            </p>
            <ul class="nav">
              <li class="active"><a href="manage.php">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
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
     
              <li><a href="#">Link</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
      
          <div class="row-fluid">
           <form action="edit.php?id=<?=$id?>" method="post">
		<?php
		
			$article_query = mysql_query("SELECT * FROM articles WHERE id = $editid") or die(mysql_error());
			
			while($row = mysql_fetch_array($article_query))
			{
				if(isset($_POST['submit']))
				{
					$row['Title'] = $_POST['title'];
					$row['Authors'] = $_POST['authors'];
					$row['Status'] = $_POST['status'];
					$row['Section'] = $_POST['section'];
					$row['Article'] = $_POST['article'];
					
					$row['Volume'] = $_POST['volume'];
					$row['Issue'] = $_POST['issue'];
					
					
					$row['PhotoReqs'] = $_POST['photoreqs'];
					$row['DueDate'] = $_POST['duedate'];
				}
			
		?>
		
				
				<input type="text" name="title" id="title" value="<?=$row['Title']?>" placeholder = "Title" size="55" /> &nbsp; by  &nbsp;<input type="text" name="authors" id="authors" value="<?=$row['Authors']?>" placeholder = "author" size="50" /> 
				 &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; 
				<input type = "submit" id = "submit" name = "submit" value = "Save" class="btn btn-primary btn-large" />

				<br />
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
				<input type="text" name="duedate" id="duedate" value="<?=$row['DueDate']?>" size="10" placeholder = "Due date"/>   &nbsp; &nbsp;
				
				
				<input type="text" name="photographer" id="photographer" value="<?=$row['Photographer']?>" placeholder = "Photographer/Artist" size="20" />
			
				
				
			<?php }else{ echo '<input type="hidden" name="section" id="section" value="' . $row['Section'] . '"/>
			
				<input type="hidden" name="volume" id="volume"  value =' . $row['Volume'] .'size="1" />
				-
				<input type="hidden" name="issue" id="issue"  value=' . $row['Issue'] .' size="1" />
		'; }?>
			
			
				<label for="article">Article</label>
				<?php $article = $row['Article'];?>
				<textarea name="article" id="article" rows="20" cols = "156" placeholder = "Write your article here!"><?=$article?></textarea>
				
				
			
	
			
				
			
			
			<?php if($role == "Senior Editor" || $role == "Senior Staff" || $role == "Admin" || $role == "Photo"): ?>
		
			
				<label for="photoreqs">Photo/Graphic Request</label>
				<textarea name="photoreqs" id="photoreqs" rows="5" cols="100"><?=$row['PhotoReqs']?></textarea>
			
			<?php endif ?>
							 &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; 

				<input type = "submit" id = "submit" name = "submit" value = "Save" class="btn btn-primary btn-large" />
		
		<?php
			}
		?>
		</form>
          </div><!--/row-->
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