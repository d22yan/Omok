<?php 
	require_once(__DIR__.'/ConnectToDatabase.php');

	function fetchGameId() {
		$statementFetchGameId = 'SELECT MAX(game_id) FROM omok_games';
		$resultFetchGameId = mysql_query($statementFetchGameId);
		$rowFetchGameId = mysql_fetch_array($resultFetchGameId);
		$gameId = $rowFetchGameId[0] + 1;
		$statementCreateGameId = 'INSERT INTO omok_games(game_id) VALUES ("'.$gameId.'")';
		mysql_query($statementCreateGameId);
		return $gameId;
	}

	function fetchScore($color) {
		$statementFetchScore = 'SELECT COUNT(win) FROM omok_games WHERE win=1 AND '.$color.' <> 0';
		$resultFetchScore = mysql_query($statementFetchScore);
		$rowFetchScore = mysql_fetch_array($resultFetchScore);
		return $rowFetchScore[0];
	}

	function UpdateMove($gameid, $move, $black, $white, $win) {
		$statementUpdateMove = 'INSERT INTO omok_games(game_id, move, black, white, win) VALUES ("'.$gameid.'","'.$move.'","'.$black.'","'.$white.'","'.$win.'")';
		mysql_query($statementUpdateMove);
	}

	if(!empty($_POST['query'])) {
		if($_POST['query'] == 'fetchGameId') {
			echo fetchGameId();
		} elseif($_POST['query'] == 'fetchScore') {
			$colorScore = array(
				"black" => fetchScore('black'),
				"white" => fetchScore('white'),
			);
			echo json_encode($colorScore);
		} elseif($_POST['query'] == 'updateMove') {
			if (!empty($_POST['gameid'])) {
				UpdateMove($_POST['gameid'], $_POST['move'], $_POST['black'], $_POST['white'], $_POST['win']);
				echo $_POST['gameid'];
				echo " ";
				echo $_POST['move'];
				echo " ";
				echo $_POST['black']; 
				echo " ";
				echo $_POST['white'];
				echo " ";
				echo $_POST['win'];
			} else {
				echo "empty";
			}
		} else {
			echo 'error';
		}
	} else {
		echo 'error';
	}

?>