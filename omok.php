<!-- http://google-styleguide.googlecode.com/svn/trunk/htmlcssguide.xml --><!DOCTYPE html><?php // TODO: possible deprication of php	require_once(__DIR__.'/ConnectToDatabase.php');?> <script src="Utilities.js"></script><script> //http://stackoverflow.com/questions/547384/where-do-you-include-the-jquery-library-from-google-jsapi-cdn    document.write([        "\<script src='",        ("https:" == document.location.protocol) ? "https://" : "http://",        "ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' type='text/javascript'>\<\/script>" // https://developers.google.com/speed/libraries/devguide#jquery    ].join(''));</script><script>	$(document).ready(function() {		// document loaded	});	assert(!!window.EventSource);// http://stackoverflow.com/questions/9284664/double-exclamation-points	var source = new EventSource('Source.php'); //http://www.html5rocks.com/en/tutorials/eventsource/basics/	source.addEventListener('message', function(e) {		var asd = document.getElementById("container");		asd.innerHTML += e.data + "<br/>";		//alert(e.data);	}, false);	source.addEventListener('open', function(e) {		//Connection was opened.	}, false);	source.addEventListener('error', function(e) {		if (e.readyState == EventSource.CLOSED) {			// Connection was closed.		}	}, false);</script><link rel="stylesheet" href="omok.css"></link><meta charset="utf-8"> <!-- General Meta Rules - Encoding --><title>Omok</title><div id="background"></div><div id="container"></div>