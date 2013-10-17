<?php
	//The Lions Roar Archives (CFS)

	//Copyright (c) 2012 James Wu 
	//pages.php - displays all pages from manage_pages.php
	
	$user_query = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
	$user_result = mysql_fetch_array($user_query);
	
	$name = $user_result['name'];
	$role = $user_result['role'];
	$section = $user_result['section'];

	if(isset($_GET['issue'])){
		$break=explode('-', $_GET['issue']);
		$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume = '$break[0]' and Issue = '$break[1]' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	}else{
		$issue_query = mysql_query("SELECT Volume, Issue FROM articles WHERE Volume != 'UPR-2' ORDER BY Volume desc, Issue desc LIMIT 1") or die(mysql_error());
	}
	
	$issue_result = mysql_fetch_array($issue_query);

	$volume = $issue_result['Volume'];
	$issue = $issue_result['Issue'];
	

		
	$sections_array_main = array(array('Pages'));

	
	
	foreach($sections_array_main as $column){
	
		echo "<div id='list-wrapper'>";
		
		foreach($column as $row_section)
		{
		
			$article_query = mysql_query("SELECT * FROM articles WHERE Volume = '$volume' and Issue = $issue and Section = '$row_section' and Status != 'Bulletin' ORDER BY id") or die(mysql_error());
			
			echo '<div class="table-wrapper" id="' . $row_section . '">';
			echo '<h2>' . $row_section . '</h2>';
			echo '<table>';
			
			$article_num = mysql_num_rows($article_query);
			
			
			
						
			////// grab the pages
			
			while($row = mysql_fetch_array($article_query))
			{
				////// shorter statuses:
				$status = $row['Status'];
				if($row['Status'] == 'Ready to lay'){ $status = 'PDF';}
				elseif($row['Status'] == 'Senior editor to senior staff'){$row['Status']='To Senior staff'; $status = 'Third Proof';}
				elseif($row['Status'] == 'Editor to senior editor'){$row['Status']='To Senior editor'; $status = 'Second Proof';}
				elseif($row['Status'] == 'Reporter to editor'){$row['Status']='To Editor'; $status = 'First Proof';}
				elseif($row['Status'] == 'Reporter draft'){$status = 'Laying';}
				elseif($row['Status'] == 'Unwritten'){$status ='Not Started';}
				
				$row_class = str_replace(' ', '-', $row['Status']); // make the css class
				
				echo "<tr class=\"" . $row_class . "\">";
				
				echo "<td class=\"title-td\">" . $row['Title'] . "</td>";
				echo "<td class=\"authors-td\">" . $row['Authors'] . "</td>";
				echo "<td class=\"status-td\">" . $status . "</td>";

					
				echo '<td class="actions-td">';
	
			
				//// if is is sr. ed, sr. staff, or admin of this section, then can edit up everything:

				if(($role == 'Senior Editor' || $role == 'Senior Staff' || $role == 'Admin')){
					echo '<a href="edit-page.php?id=' . $row['id'] . '">Edit</a>';}

				echo "</td></tr>";
				
				
			} // end $row = mysql_fetch_array($article_query)
			
				echo '</table></div><!--/.table-wrapper-->';
			
		}//end foreach column as row_section
		
		echo '</div><!--/.list-wrapper-->';
		
	}//end foreach section_array_main as column

?>