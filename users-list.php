<?php

	$user_query = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
	$user_result = mysql_fetch_array($user_query);
	
	$name = $user_result['name'];
	$role = $user_result['role'];
	if($role == 'Photo Staff'){$role = 'Photo/Graphics';}
	$section = $user_result['section'];
	if($role == 'Editor In Chief') $role = 'Admin';
	/////////////////
	
	$query = mysql_query("SELECT * FROM users ORDER BY name");
	
?>

	<table class = "table table-hover table-condensed">
	
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Section</th>
			<th>Position</th>
			<th>YOG</th>
			<?php if ($role == 'Senior Staff' || $role == 'Admin'){?><th>Actions</th><?php }?>
		</tr>

<?php

	while ($user = mysql_fetch_array($query)){
		echo '<tr class="' . str_replace(' ', '-', strtolower($user['role'])) . '">';
		echo '<td class="name-td">' . $user['name'] . '</td>';
		echo '<td class="email-td">' . $user['email'] . '</td>';
		echo '<td class="phone-td">' . $user['phone'] . '</td>';
		echo '<td class="section-td">' . $user['section'] . '</td>';
		echo '<td class="role-td">' . $user['role'] . '</td>';
		echo '<td class="yog-td">' . $user['yog'] . '</td>';
		
		if ($role == 'Senior Staff' || $role == 'Admin'){echo '<td class="actions-td"><a href="users-edit.php?id=' . $user['id'] . '">Edit</a> | <a onclick="alert(\'Are you sure?\')" href="users-delete.php?id=' . $user['id'] . '">X</a></td>';}
		
		echo '</tr>';
	}
	
	echo '</table>';
	
?>