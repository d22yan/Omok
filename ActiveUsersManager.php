<?php 
	require_once(__DIR__.'/ConnectToDatabase.php');

	function updateActive($user, $active) {
		$statementUpdateActive = 'UPDATE active_users SET active='.$active.' WHERE user="'.$user.'"';
		$resultUpdateActive = mysql_query($statementUpdateActive);
	}

	if(!empty($_POST['query'])) {
		if($_POST['query'] == 'updateActive') {
			if(!empty($_POST['user']) && !empty($_POST['active'])) {
				updateActive($_POST['user'], $_POST['active']);
				echo 'notempty';
			} else {
				echo 'empty';
			}
		}
	}

?>