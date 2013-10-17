<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu / (c) 2009 Zhoushi Xie

	//manage.php - only minor additions for displaying more information
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

	if(isset($_GET['issue'])){
		$break=explode('-', $_GET['issue']);
		$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume = '$break[0]' and Issue = '$break[1]' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	}else{
		$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume != 'UPR-2' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	}
	
	$issue_result = mysql_fetch_array($issue_query);

	$volume = $issue_result['Volume'];
	$issue = $issue_result['Issue'];
	
	///////////////////////////////////////////////////////////

	if($section != 'All' && $role != 'Senior Editor'){
	
		$section_makeArray = array($section);
		$sections_array_main = array($section_makeArray);

	}elseif($section == 'All'){
	
		$sections_array1 = array('Web',  'News', 'Features');
		$sections_array2 = array('Editorials','Community','Sports', 'Centerfold', 'Opinions');
		
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

	include('users_form.php');
?>
