<?php

	foreach($sections_array_main as $column){
	
		echo '<div id="list-wrapper">';
		
		foreach($column as $row_section)
		{
		
			$article_query = mysql_query("SELECT * FROM articles WHERE Volume = '$volume' and Issue = $issue and Section = '$row_section' and Status != 'Bulletin'") or die(mysql_error());
			$bulletin_query = mysql_query("SELECT * FROM articles WHERE Volume = '$volume' and Issue = $issue and Section = '$row_section' and Status = 'Bulletin'") or die(mysql_error());
			
			echo '<div class="table" id="' . $row_section . '">';
			echo '<h2>' . $row_section . '</h2>';
			echo '<table>';
			
			$article_num = mysql_num_rows($article_query);
			$bulletin_num = mysql_num_rows($bulletin_query);
			
			////// if there isn't anything:
			
			if($article_num == 0 && $bulletin_num==0){
			
				echo "<tr class=\"empty\"><td>";
				
				if($section == $row_section || ($section == 'All' && $role != "Photo Staff")): echo "<a href=\"new.php?section=" . $row_section . "\">New Article</a>";
				else: echo "No Articles";
				endif;
				
				echo "</td></tr>";
				
			} //end if article and bulletin nums == 0
			
			////// grab the bulletin:
			
			while($row = mysql_fetch_array($bulletin_query)){
			
				$row_class = str_replace(' ', '-', $row['Status']);
				
				echo "<tr class=\"" . $row_class . "\">";
				echo "<td class=\"title-td\" colspan=\"2\">" . $row['Title'] . ': ' . $row['Article'] . "</td>";
				echo "<td class=\"authors-td\" valign=\"top\"> by " . $row['Authors'] ."</td>";
				
				echo '<td class="actions-td" valign="top">';
				
				if($role == 'Senior Staff' || $role == 'Admin'):
					echo '<a href="edit.php?id=' . $row['id'] . '"><font color = #000000> Edit</font></a> | <a href="javascript:confirmation(\'delete.php?id=' . $row['id'] . '\')">X</a>';
				endif;
				
				echo "</td></tr>";
				
			} //end while row = mysql_fetch_array($bulletin_query)
			
			////// grab the articles
			
			while($row = mysql_fetch_array($article_query))
			{
				////// shorter statuses:
				
				if($row['Status'] == 'Senior editor to senior staff'){$row['Status']='To Senior staff';}
				else if($row['Status'] == 'Editor to senior editor'){$row['Status']='To Senior editor';}
				else if($row['Status'] == 'Reporter to editor'){$row['Status']='To Editor';}
				else if($role == 'Reporter' || $role == 'Editor') { //prevents hurting of feelings
					if($row['Status'] == 'Not Running') {
						$row['Status'] = 'To Senior editor';
					}
					
				}
				
				$row_class = str_replace(' ', '-', $row['Status']); // make the css class
				
				echo "<tr class=\"" . $row_class . "\">";
				
				echo "<td class=\"title-td\">" . $row['Title'] . "</td>";
				echo "<td class=\"authors-td\">" . $row['Authors'] . "</td>";
				echo "<td class=\"status-td\">" . $row['Status'] . "</td>";
				echo "<td class=\"photo-td\">";
					if($row['PhotoReqs'] != '0' && $row['PhotoReqs'] != ''){echo 'Photo Req\'d';}
				echo "<td class=\"status-td\">" . $row['DueDate'] . "</td>";
					
				echo "</td>";

				echo '<td class="actions-td">';
	
				//// if is reporter of this section, then can edit up to reporter to editor:
				
				if($role == 'Reporter' &&
				  ($row['Status'] == 'Unwritten' || 
				   $row['Status'] == 'Reporter draft' || 
				   $row['Status'] == 'To Editor') && 
				  ($section == $row_section || $section == 'All')){
					echo '<a href="edit.php?id=' . $row['id'] . '">Edit</a> | <a href="javascript:confirmation(\'delete.php?id=' . $row['id'] . '\')">X</a>';}
	
				//// if is editor of this section, then can edit up to editor to senior editor:
				
				else if($role == 'Editor' && 
				  ($row['Status'] == 'Unwritten' || 
				   $row['Status'] == 'Reporter draft' || 
				   $row['Status'] == 'To Editor' || 
				   $row['Status'] == 'To Senior Editor') &&
				  ($section == $row_section || $section == 'All')){
					echo '<a href="edit.php?id=' . $row['id'] . '">Edit</a> | <a href="javascript:confirmation(\'delete.php?id=' . $row['id'] . '\')">X</a>';}
				
				//// if is is sr. ed, sr. staff, or admin of this section, then can edit up everything:

				else if(($role == 'Senior Editor') && 
				   ($section == $row_section || $section == 'All') && ($row['id'] != 5571) ){
					echo '<a href="edit.php?id=' . $row['id'] . '">Edit</a> | <a href="javascript:confirmation(\'delete.php?id=' . $row['id'] . '\')">X</a>';}
					
				else if( $role == 'Admin' || $role == 'Senior Staff')
				{
					echo '<a href="edit.php?id=' . $row['id'] . '">Edit</a> | <a href="javascript:confirmation(\'delete.php?id=' . $row['id'] . '\')">X</a>';}

				echo "</td></tr>";
				
			} // end $row = mysql_fetch_array($article_query)
			
			echo '</table></div><!--/.table-wrapper-->';
			
		}//end foreach column as row_section
		
		echo '</div><!--/.list-wrapper-->';
		
	}//end foreach section_array_main as column
	
?>