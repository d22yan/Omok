<?php 
	require_once('/ConnectToDatabase.php');

	function fetchGameId($computerMode) {
		$statementFetchGameId = 'SELECT MAX(game_id) FROM omok_game';
		$resultFetchGameId = mysql_query($statementFetchGameId);
		$rowFetchGameId = mysql_fetch_array($resultFetchGameId);
		$gameId = $rowFetchGameId[0] + 1;
		$statementCreateGameId = 'INSERT INTO omok_game(game_id) VALUES ("'.$gameId.'")';
		mysql_query($statementCreateGameId);
		$statementInsertGameType = 'INSERT INTO omok_game_type(game_id, computer_mode) VALUES ("'.$gameId.'", '.$computerMode.')';
		mysql_query($statementInsertGameType);
		return $gameId;
	}

	function fetchScore($computerMode, $color) {
		$statementFetchScore = 'SELECT COUNT(win) FROM omok_game, omok_game_type WHERE omok_game.game_id = omok_game_type.game_id AND omok_game_type.computer_mode = '.$computerMode.' AND omok_game.win = 1 AND omok_game.'.$color.' <> 0';
		$resultFetchScore = mysql_query($statementFetchScore);
		$rowFetchScore = mysql_fetch_array($resultFetchScore);
		return $rowFetchScore[0];
	}

	function UpdateMove($gameid, $move, $black, $white, $win) {
		$statementUpdateMove = 'INSERT INTO omok_game(game_id, move, black, white, win) VALUES ("'.$gameid.'","'.$move.'","'.$black.'","'.$white.'","'.$win.'")';
		mysql_query($statementUpdateMove);
	}

	if (!empty($_POST['query'])) {
		if($_POST['query'] == 'fetchGameId') {
			echo fetchGameId($_POST['computerMode']);
		} elseif ($_POST['query'] == 'fetchScore') {
			$colorScore = array(
				"black" => fetchScore($_POST['computerMode'], 'black'),
				"white" => fetchScore($_POST['computerMode'], 'white')
			);
			echo json_encode($colorScore);
		} elseif ($_POST['query'] == 'updateMove') {
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