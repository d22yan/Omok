<!-- http://google-styleguide.googlecode.com/svn/trunk/htmlcssguide.xml --><!DOCTYPE html><?php 	require_once(__DIR__.'/ConnectToDatabase.php');?> <script src="Utilities.js"></script><script> //http://stackoverflow.com/questions/547384/where-do-you-include-the-jquery-library-from-google-jsapi-cdn    document.write([        "\<script src='",        ("https:" == document.location.protocol) ? "https://" : "http://",        "ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'>\<\/script>" // https://developers.google.com/speed/libraries/devguide#jquery    ].join(''));</script><script>	var aiMode = 0;	var colorTurn = 'black';	var connectedFiveStones = new Array(); 	var gameId;	var gameInProcess = true;;	var grid;	var moveCounter = 0;	var Point = (function() { // http://stackoverflow.com/questions/12610394/javascript-classes/12610555#12610555	    function Point(){	        this._state = undefined;	    };	    Point.prototype.getState = function() {	        return this._state;	    };	    Point.prototype.setState = function(state) {	        this._state = state;	    };	    return Point;	})();	function assert(condition, message) {		if(!condition) {			throw message || 'Assertion failed';		}	}	function changeColorTurn() {		if(colorTurn == 'black') {			colorTurn = 'white';		} else {			colorTurn = 'black';		}	}	function checkConnectFive(selectedColor, selectedX, selectedY) {		var selectedCounter = 0;		connectedFiveStones[0] = convertToDecimal(selectedX, selectedY);		var checkConnectFiveDirection = function(currentX, currentY, directionX, directionY, directionX2, directionY2) {			selectedCounter = 0;			return checkConnectFiveDirectionRecurse(currentX, currentY, directionX, directionY) + checkConnectFiveDirectionRecurse(currentX, currentY, directionX2, directionY2);		}		var checkConnectFiveDirectionRecurse = function(currentX, currentY, directionX, directionY) {			var nextX = currentX*1 + directionX*1; // http://stackoverflow.com/questions/4841373/how-to-force-js-to-do-math-instead-of-putting-two-strings-together			var nextY = currentY*1 + directionY*1;			if (nextX < 0 || nextX > 14 || nextY < 0 || nextY > 14) {				return 0;			}			if (selectedColor == grid[nextX][nextY].getState()) {				selectedCounter++;				connectedFiveStones[selectedCounter] = convertToDecimal(nextX, nextY);				return checkConnectFiveDirectionRecurse(nextX, nextY, directionX, directionY) + 1;			}			return 0;		}		if (checkConnectFiveDirection(selectedX, selectedY, -1, 0, 1, 0) == 4 ||			checkConnectFiveDirection(selectedX, selectedY, -1, 1, 1, -1) == 4 ||			checkConnectFiveDirection(selectedX, selectedY, 0, 1, 0, -1) == 4 ||			checkConnectFiveDirection(selectedX, selectedY, 1, 1, -1, -1) == 4) {			return true;		}		return false;	}	function clearGrid() {		for(var i = 0; i < 15; i++) {			for(var j = 0; j < 15; j++) {				grid[i][j] = new Point();				getStoneElementByCoordinate(i, j).attr('class', 'stone-empty');			}		}	}	function convertToDecimal(x, y) {		return x*15 + y*1 + 1;	}	function convertToCoordinateX(decimal) {		return Math.floor(decimal/15);	}	function convertToCoordinateY(decimal) {		return decimal%15 - 1;	}	function displayConnectedFiveStones() {		for(var i = 0; i < 5; i++) {			var coordinateX = convertToCoordinateX(connectedFiveStones[i]);			var coordinateY = convertToCoordinateY(connectedFiveStones[i]);			var $elementStone = $('#stone-' + coordinateX + '-' + coordinateY);			$elementStone.css('box-shadow', 'inset 0 0 8px #808080, 0px 0px 3px 1px #fff');			$elementStone.css('-moz-box-shadow', 'inset 0 0 8px #808080, 0px 0px 3px 1px #fff');			$elementStone.css('-webkit-box-shadow', 'inset 0 0 8px #808080, 0px 0px 3px 1px #fff');		}	}	function drawGrid(canvasId){		var canvas = document.getElementById(canvasId);		assert(!!canvas.getContext);		var ctx = canvas.getContext('2d');		var offset = 1;		ctx.lineWidth = 2;		for(var i = 0; i < 16; i++) {			ctx.beginPath();			ctx.moveTo(offset + 40*i, 0);			ctx.lineTo(offset + 40*i, 602);			ctx.stroke();		}		for(var i = 0; i < 16; i++) {			ctx.beginPath();			ctx.moveTo(0, offset + 40*i);			ctx.lineTo(602, offset + 40*i);			ctx.stroke();		}	}	function drawStars(canvasId) {		var mycanvas = document.getElementById(canvasId);		assert(!!mycanvas.getContext);		var ctx = mycanvas.getContext('2d');		ctx.fillRect(116, 116, 10, 10);			ctx.fillRect(116, 436, 10, 10);			ctx.fillRect(276, 276, 10, 10);		ctx.fillRect(436, 116, 10, 10);		ctx.fillRect(436, 436, 10, 10);	}	function fetchGameId() {		$.ajax({			type: 'POST',			url: 'OmokGamesManager.php',			data: 'query=fetchGameId',			success: function(data) {				gameId = data;			},			ajax: false		});	}	function fetchScore() {		$.ajax({			type: 'POST',			url: 'OmokGamesManager.php',			data: 'query=fetchScore',			success: function(data) {				var json = $.parseJSON(data);				$('#label-blackscore').html(json.black);				$('#label-whitescore').html(json.white);			}		});	}	function gameBegin() {		fetchGameId();		clearGrid();		colorTurn = 'black';		gameInProcess = true;		moveCounter = 0;	}	function gameEnd() {		displayConnectedFiveStones();		incrementScorePoint();		gameInProcess = false;	}	function getCurrentStone() {		return colorTurn;	}		function getCurrentStoneClass() {		if(colorTurn == 'black') {			return 'stone-black';		} 		return 'stone-white';	}	function getGridPoint(element) {		var x = parseInt($(element).attr('x'),10);		var y = parseInt($(element).attr('y'),10);		return grid[x][y];	}	function getStoneElementByCoordinate(coordinateX, coordinateY) {		return $('#stone-' + coordinateX + "-" + coordinateY);	}		function getStoneElementByPointElement(element) {		return $(element).find('div[id^="stone"]');	}	function incrementScorePoint() {		var score = $('#label-' + colorTurn + 'score').html()		$('#label-' + colorTurn + 'score').html(score*1 + 1);	}	function initializeGrid() {		grid = new Array(15);		for(var i = 0; i < 15; i++) {			grid[i] = new Array(15);			for(var j = 0; j < 15; j++) {				grid[i][j] = new Point();			}		}	}	function removeDisplayConnectedFiveStones() {		for(var i = 0; i < 5; i++) {			var coordinateX = convertToCoordinateX(connectedFiveStones[i]);			var coordinateY = convertToCoordinateY(connectedFiveStones[i]);			var $elementStone = $('#stone-' + coordinateX + '-' + coordinateY);			$elementStone.css('box-shadow', '');			$elementStone.css('-moz-box-shadow', '');			$elementStone.css('-webkit-box-shadow', '');		}	}	function updateMove(coordinateX, coordinateY, win) {		var black = 0;		var white = 0;		var coordinateDecimal = convertToDecimal(coordinateX, coordinateY);		if(getCurrentStone() == 'black') {			black = coordinateDecimal;			} else {			white = coordinateDecimal;		}		$.ajax({			type: 'POST',			url: 'OmokGamesManager.php',			data: 'query=updateMove'+				'&gameid='+gameId+				'&move='+moveCounter+				'&black='+black+				'&white='+white+				'&win='+(win*1),			success: function(data) {				 //alert(data);			}		});		}	$(document).ready(function() {		$('#label-newgame').click(			function() {				removeDisplayConnectedFiveStones();				gameBegin();			}		);		$('.label-hover').hover(			function() {				$(this).css('text-shadow','0px 0px 5px #fff');			},			function() {				$(this).css('text-shadow','');			}		);		$('.point').click(			function() {				if(gameInProcess) {					var gridPoint = getGridPoint(this);					if(typeof(gridPoint.getState()) === 'undefined') {						gridPoint.setState(getCurrentStone());						getStoneElementByPointElement(this).attr('class', getCurrentStoneClass());						var connectedFive = checkConnectFive(colorTurn, $(this).attr('x'), $(this).attr('y'))						if(connectedFive) {							gameEnd();						}						moveCounter++;						updateMove($(this).attr('x'), $(this).attr('y'), connectedFive);						changeColorTurn();					}				}			}		);		$('.point').hover(			function() {				if(gameInProcess) {					var $stoneElement = getStoneElementByPointElement(this);					if($stoneElement.attr('class') == 'stone-empty') {						getStoneElementByPointElement(this).attr('class', getCurrentStoneClass());					}				}			},			function() {				if(gameInProcess) {					var $stoneElement = getStoneElementByPointElement(this);					if(typeof(getGridPoint(this).getState()) === 'undefined' && $stoneElement.attr('class') == getCurrentStoneClass()) {						getStoneElementByPointElement(this).attr('class', 'stone-empty');					}				}			}		);		drawGrid('board-canvas');		drawStars('board-canvas');		fetchScore();		fetchGameId();		initializeGrid();	});</script><link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'> <!-- https://developers.google.com/fonts/docs/getting_started --><link href="omok.css" rel="stylesheet"> <!-- http://stackoverflow.com/questions/9943981/can-self-closing-link-tags-be-problematic --><meta charset="utf-8"> <!-- General Meta Rules - Encoding --><title>omok</title><div id="background"></div><div id="container">	<div id="menu">		<div id="menu-leftcontainer">			<div id="label-black" class="label" color="black">				black			</div>			<div id="label-blackscore" class="label" color="black"></div>			<div id="label-whitescore" class="label" color="white"></div>			<div id="label-white" class="label" color="white">				white			</div>		</div> 		<div id="board-container">			<canvas id="board-canvas" width="562" height="562"></canvas>			<table id="board-table" cellpadding="0" cellspacing="0"><?php	for($i = 0; $i < 15; $i++) {		echo '			<tr>		';		for($j = 0; $j < 15; $j++) {			echo '				<td>					<div id="point-'.$i.'-'.$j.'" class="point" x="'.$i.'" y="'.$j.'">						<div id="stone-'.$i.'-'.$j.'" class="stone-empty" x="'.$i.'" y="'.$j.'"></div>					</div>				</td>			';		}		echo '			</tr>		';	}?>			</table>		</div>		<div id="menu-rightcontainer">			<div id="label-newgame" class="label label-hover">				new game			</div>		</div>	</div></div>