<?php 
	require_once(__DIR__.'/ConnectToDatabase.php');

	function getScore($color) {
		$statementGetScoreBlack = 'SELECT COUNT(win) FROM omok_games WHERE win="'.$color.'"';
		// $resultGetScore = mysql_query($statementGetScore);
		//$rowGetScore = mysql_fetch_array($resultGetScore);
		//return $rowGetScore[0];
		return 123;
	}

	function UpdateMove() {
		
	}

	if(!empty($_POST['query'])) {
		if($_POST['query'] == 'getScore') {
			$blackScore = getScore('black');
			$whiteScore = getScore('white');
			echo $blackScore;
		} elseif($_POST['query'] == 'updateMove') {
			echo 'asd';
		} else {
			echo 'error';
		}
	} else {
		echo 'error';
	}

?>