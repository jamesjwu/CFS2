<?php

	
	$volume = $_GET['v'];
	$issue = $_GET['i'];
	$publish = 'no';
	
	if((!isset($_GET['v']) || !isset($_GET['i'])) && !isset($_GET['transfer'])){
		echo "Please enter issue.";}
	else{
		echo "To be transfered: " . $volume . "-" . $issue;}
	
	//====No=Edit======//
	
	mysql_connect("localhost", "thelions", "welch");

    include('markdown.php');
	
	function insert_articles($title, $authors, $article, $section, $issue, $publish){
		
		mysql_select_db("thelions_sym");
		
		$query = mysql_query("SELECT id FROM sym_entries ORDER BY id desc LIMIT 1");
		$result = mysql_fetch_array($query);
		$next_id = $result[0] + 1;
		
		$date = date('Y-m-d H:i:s');
		$hour = (date('H')+4) % 24;
		$date_gmt = date('Y-m-d') . $hour . ':' . date('i:s');
		
		$posted = str_replace(" ", "T", $date);
		$posted = $posted . "-04:00";
		
		$title = addslashes($title);
		
		$article = addslashes($article);
		$article = str_replace("\n\n", "\n", $article);
		$article = str_replace("\n", "\n\n", $article);
		$article = str_replace("\t", "", $article);
		$article = str_replace("**", "", $article);
		
		$article = str_replace("Õ", "&#39;", $article);
		$article = str_replace("Ô", "&#39;", $article);
		$article = str_replace('Ò', '&quot;', $article);
		$article = str_replace('Ó', '&quot;', $article);
		$article = str_replace('É', '&hellip;', $article);
		$article = str_replace('Ð', '&ndash;', $article);
		$article = str_replace('Ñ', '&mdash;', $article);
		
		$article_html = Markdown($article);
		
		$insertEntry = mysql_query("INSERT INTO sym_entries (id, section_id, author_id, creation_date, creation_date_gmt) VALUES('$next_id','12','1','$date','$date_gmt')");
		$insertTitle = mysql_query("INSERT INTO sym_entries_data_58 (entry_id, value) VALUES ('$next_id', '$title')");
		$insertType = mysql_query("INSERT INTO sym_entries_data_138 (entry_id, handle, value) VALUES ('$next_id', 'print-article', 'Print Article')");
		$insertPublish = mysql_query("INSERT INTO sym_entries_data_64 (entry_id, value) VALUES ('$next_id', '$publish')");
		$insertSpecial = mysql_query("INSERT INTO sym_entries_data_162 (entry_id, handle , value) VALUES ('$next_id', 'none', 'None')");
		$insertFeatured = mysql_query("INSERT INTO sym_entries_data_139 (entry_id, handle , value) VALUES ('$next_id', 'not-featured', 'Not Featured')");

		
		foreach($authors as $name){
			$handle = strtolower($name);
			$handle = str_replace(' ', '-', $handle);
			$handle = str_replace('\'', '', $handle);
			$name = addslashes($name);
			mysql_query("INSERT INTO sym_entries_data_76 (entry_id, handle, value) VALUES ('$next_id', '$handle', '$name')");
		}
		
		$teaser = substr($article, 0, 200);
		$teaser_html = "<p>" . $teaser . "</p>";
		$section_handle = strtolower($section);
		
		$insertBody = mysql_query("INSERT INTO sym_entries_data_59 (entry_id, value, value_formatted) VALUES ('$next_id', '$article', '$article_html')");
		$insertTeaser = mysql_query("INSERT INTO sym_entries_data_185 (entry_id, value, value_formatted) VALUES ('$next_id', '$teaser', '$teaser_html')");
		$insertDate = mysql_query("INSERT INTO sym_entries_data_62 (entry_id, value, local, gmt) VALUES ('$next_id', '$posted', UNIX_TIMESTAMP('$date'), UNIX_TIMESTAMP('$date'))");
		$insertSections = mysql_query("INSERT INTO sym_entries_data_140 (entry_id, handle, value) VALUES ('$next_id', '$section_handle', '$section')");

		$issue_query = mysql_query("SELECT entry_id FROM sym_entries_data_115 WHERE value = '$issue'");
		$issue_result = mysql_fetch_array($issue_query);
		$in_issue = $issue_result[0];
		
		$insertIssue = mysql_query("INSERT INTO sym_entries_data_117 (entry_id, relation_id) VALUES ('$next_id', '$in_issue')");
		
			
		return true;
	}
			
	mysql_select_db("thelions_cfs");
			
	$query = mysql_query("SELECT * from articles WHERE Volume = '$volume' AND Issue = '$issue' AND Status = 'ready to lay' AND Section != 'Web'");
	
	if(!isset($_GET['transfer'])){echo '<p><a href="?transfer&amp;v=' . $volume . '&i=' . $issue . '">Transfer</a></p>';}
			
	while($row = mysql_fetch_array($query)){
			
		if($row['Section'] == "Arts etc."):
			$section = "Arts";
		else:
			$section = $row['Section'];
		endif;
		
		$title = $row['Title'];
		$authors = str_replace(" and ", ', ', $row['Authors']);
		$authors2 = explode(", ", $authors);
		$article = $row['Article'];
		$this_issue = $volume . '-' . $issue;
		$id = $row['id'];
		$publish = 'no';
		
		if(isset($_GET['transfer'])){
			if(insert_articles($title, $authors2, $article, $section, $this_issue, $publish)==true){
				echo "<br/>Good.";
			}
			else{
				echo "<br/>Bad.";
			}
		}else{
			echo $id . ' | ' . $title . ' | ' . $authors2[0];
			if(isset($authors2[1])){echo ' + ' . $authors2[1];}
			echo '<br/>';
		}
	
	} // end while
		
?>