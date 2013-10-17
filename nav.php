<!--	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu / (c) 2009 Zhoushi Xie

	//nav.php - only minor additions for displaying more information
-->
<?php $currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$current_page = $parts[count($parts) - 1]; ?>

<script type="text/javascript" src="../cfs2/bootstrap/js/bootstrap.js"></script>

<ul class = "nav navtabs">

<li><a href="manage.php" <?php if($current_page == "manage.php" || $current_page == "edit.php"){echo 'class="active"';}?>>Home</a> </li>
<li> <a href="new.php" <?php if($current_page == "new.php"){echo 'class="active"';}?>>New Article</a></li>



<?php if($role == "Editor In Chief") $role = "Admin"; ?>

<li> <a href="javascript://" id="see-prev">Previous issues</a></li>

<?php if($role == 'Admin'){?>
	<li> <a href="new-issue.php" <?php if($current_page == "new-issue.php"){echo 'class="active"';}?>>New Issue</a> </li>
<?php } ?>

<li> <a href="users.php" <?php if($current_page == "users.php" || $current_page == "users-edit.php"){echo 'class="active"';}?>>Users</a></li>

<?php if($role== 'Senior Staff' || $role == 'Admin'){?>
	<li> <a href="users-new.php" <?php if($current_page == "users-new.php"){echo 'class="active"';}?>>New User</a> </li>
<?php } ?>

<?php if($role == 'Senior Staff' || $role == 'Admin' || $role == 'Photo Staff'){?>
	<li> <a href="photos.php" <?php if($current_page == "photos.php"){echo 'class="active"';}?>>Photos/Graphics</a> </li>
<?php } ?>
<?php if($role == 'Senior Staff' || $role == 'Admin' || $role == 'Senior Editor'){?>
	<li> <a href="manage-pages.php<?php if(isset($_GET['issue'])) {echo '?issue='. $_GET['issue'];} ?>" <?php if($current_page == "manage-pages.php"){echo 'class="active"';}?>>Pages</a>
<?php } ?>

<!--
<?php if($role == 'Senior Staff' || $role == 'Admin' || $role == 'Senior Editor'){?>
	<li> <a href="scoreboard.php" <?php if($current_page == "scoreboard.php"){echo 'class="active"';}?>>RoarBoard</a> </li>
<?php } ?>
-->

<li> <a href="logout.php" style="margin-right:20px;">Logout</a>  </li>

</ul>