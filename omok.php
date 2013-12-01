<!-- http://google-styleguide.googlecode.com/svn/trunk/htmlcssguide.xml --><!DOCTYPE html><?php 	require_once(__DIR__.'/ConnectToDatabase.php');?> <script src="Utilities.js"></script><script> //http://stackoverflow.com/questions/547384/where-do-you-include-the-jquery-library-from-google-jsapi-cdn    document.write([        "\<script src='",        ("https:" == document.location.protocol) ? "https://" : "http://",        "ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'>\<\/script>" // https://developers.google.com/speed/libraries/devguide#jquery    ].join(''));</script><script>	var grid;	var Point = (function() { // http://stackoverflow.com/questions/12610394/javascript-classes/12610555#12610555	    function Point(){	        this._state = "";	    };	    Point.prototype.getState = function() {	        return this._state;	    };	    Point.prototype.setState = function(state) {	        this._state = state;	    };	    return Point;	})();			function assert(condition, message) {		if(!condition) {			throw message || "Assertion failed";		}	}	function createSSE() {		assert(!!window.EventSource);// http://stackoverflow.com/questions/9284664/double-exclamation-points		var source = new EventSource('Source.php'); //http://www.html5rocks.com/en/tutorials/eventsource/basics/		source.addEventListener('message', function(e) {			console.log(e.data);		}, false);		source.addEventListener('open', function(e) {			//Connection was opened.		}, false);		source.addEventListener('error', function(e) {			if (e.readyState == EventSource.CLOSED) {				// Connection was closed.			}		}, false);	}	function drawGrid(canvasId){		var canvas = document.getElementById(canvasId);		assert(!!canvas.getContext);		var ctx = canvas.getContext('2d');		var offset = 1;		ctx.lineWidth = 2;		for (var i = 0; i < 16; i++) {			ctx.beginPath();			ctx.moveTo(offset + 40*i, 0);			ctx.lineTo(offset + 40*i, 602);			ctx.stroke();		}		for (var i = 0; i < 16; i++) {			ctx.beginPath();			ctx.moveTo(0, offset + 40*i);			ctx.lineTo(602, offset + 40*i);			ctx.stroke();		}	}	function drawStars(canvasId) {		var mycanvas = document.getElementById(canvasId);		assert(!!mycanvas.getContext);		var ctx = mycanvas.getContext('2d');		ctx.fillRect(116, 116, 10, 10);			ctx.fillRect(116, 436, 10, 10);			ctx.fillRect(276, 276, 10, 10);		ctx.fillRect(436, 116, 10, 10);		ctx.fillRect(436, 436, 10, 10);	}	function getPoint(elementPoint) {		var pointId = $(elementPoint).attr("id");		var coordinate = pointId.split("-");		var x = parseInt(coordinate[1],10);		var y = parseInt(coordinate[2],10);		return grid[x][y];	}	function initialize() {		grid = new Array(15);		for(var i = 0; i < 15; i++) {			grid[i] = new Array(15);			for(var j = 0; j < 15; j++) {				grid[i][j] = new Point();			}		}	}	$(document).ready(function() {		$('.point').hover (			function() {				$(this).find('div[id^="stone"]').toggleClass('stone-empty' + ' stone-black');			},			function() {				$(this).find('div[id^="stone"]').toggleClass('stone-empty' + ' stone-black');			}		);		drawGrid("board-canvas");		drawStars("board-canvas");		createSSE();		initialize();	});</script><link rel="stylesheet" href="omok.css"></link><meta charset="utf-8"> <!-- General Meta Rules - Encoding --><title>Omok</title><div id="background"></div><div id="container">	<div id="board-container">		<canvas id="board-canvas" width="562" height="562"></canvas>		<table id="board-table" cellpadding="0" cellspacing="0"><?php	for ($i = 0; $i < 15; $i++) {		echo '			<tr>		';		for($j = 0; $j < 15; $j++) {			echo '				<td>					<div id="point-'.$i.'-'.$j.'" class="point">						<div id="stone-'.$i.'-'.$j.'" class="stone-empty"></div>					</div>				</td>			';		}		echo '			</tr>		';	}?>		</table>	</div></div>